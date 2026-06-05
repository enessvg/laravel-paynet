<?php

namespace Paynet\Tests\Unit;

use Illuminate\Support\Facades\Http;
use Paynet\DTOs\CardListParams;
use Paynet\DTOs\CaptureReversalResponse;
use Paynet\DTOs\SavedCardListResponse;
use Paynet\DTOs\SavedCardOtpCheckParams;
use Paynet\DTOs\SavedCardOtpParams;
use Paynet\DTOs\SavedCardOtpResponse;
use Paynet\DTOs\SavedCardResponse;
use Paynet\DTOs\SaveCardParams;
use Paynet\DTOs\PaymentParams;
use Paynet\DTOs\PaymentResponse;
use Paynet\DTOs\PaymentVerificationResponse;
use Paynet\DTOs\RatioCodeParams;
use Paynet\DTOs\RatioDefinitionParams;
use Paynet\DTOs\RatioParams;
use Paynet\DTOs\RatioTypeParams;
use Paynet\DTOs\ReversalListParams;
use Paynet\DTOs\TransactionIdParams;
use Paynet\Enums\ResultCode;
use Paynet\PaynetClient;
use Paynet\Tests\TestCase;

class CleanV1ClientTest extends TestCase
{
    public function test_payment_returns_typed_response_and_sends_documented_payload(): void
    {
        Http::fake([
            'https://pts-api.paynet.com.tr/v2/transaction/payment' => Http::response([
                'object_name' => 'payment_response',
                'code' => 0,
                'message' => 'Basarili Islem',
                'xact_id' => 'xk_123',
                'is_succeed' => true,
                'bank_id' => 'ZDGR',
                'card_bank_id' => 'AXSS',
                'amount' => '20,50',
                'currency' => 'TRY',
                'reference_no' => 'REF-1',
            ]),
        ]);

        $client = new PaynetClient('secret');

        $response = $client->payment(new PaymentParams(
            amount: '20,50',
            referenceNo: 'REF-1',
            domain: 'www.acme.com',
            pan: '5400617004770430',
            month: '12',
            year: '2030',
            cvc: '123',
            cardHolder: 'John Doe',
        ));

        $this->assertInstanceOf(PaymentResponse::class, $response);
        $this->assertTrue($response->successful());
        $this->assertSame('xk_123', $response->xactId);
        $this->assertSame('ZDGR', $response->bankId);
        $this->assertSame('AXSS', $response->cardBankId);
        $this->assertSame('ZDGR', $response->get('bank_id'));
        $this->assertNotNull($response->raw());

        Http::assertSent(fn ($request) => $request->url() === 'https://pts-api.paynet.com.tr/v2/transaction/payment'
            && $request->hasHeader('Authorization', 'Basic secret')
            && $request['reference_no'] === 'REF-1'
            && $request['domain'] === 'www.acme.com');
    }

    public function test_http_error_object_is_normalized_to_failed_typed_response(): void
    {
        Http::fake([
            'https://pts-api.paynet.com.tr/v2/transaction/payment' => Http::response([
                'type' => 'authentication_error',
                'message' => 'API anahtariniz yanlis',
                'code' => 401,
                'result_code' => 7,
            ], 401),
        ]);

        $client = new PaynetClient('wrong-secret');

        $response = $client->payment(new PaymentParams(
            amount: '20,50',
            referenceNo: 'REF-1',
            domain: 'www.acme.com',
            cardHash: 'card-token',
        ));

        $this->assertInstanceOf(PaymentResponse::class, $response);
        $this->assertFalse($response->successful());
        $this->assertFalse($response->apiSuccessful());
        $this->assertSame(ResultCode::Unauthorized, $response->code);
        $this->assertSame('API anahtariniz yanlis', $response->errorMessage());
        $this->assertSame(401, $response->get('code'));
    }

    public function test_verify_payment_checks_transaction_result_and_expected_values(): void
    {
        Http::fake([
            'https://pts-api.paynet.com.tr/v1/transaction/check' => Http::response([
                'object_name' => 'transaction_check',
                'code' => 0,
                'message' => 'Basarili Islem',
                'Data' => [[
                    'xact_id' => 'xk_123',
                    'reference_no' => 'REF-1',
                    'amount' => '20,50',
                    'currency' => 'TRY',
                    'net_amount' => '20,50',
                    'is_succeed' => true,
                    'bank_id' => 'WRLD',
                ]],
            ]),
        ]);

        $client = new PaynetClient('secret');

        $response = $client->verifyPayment(
            referenceNo: 'REF-1',
            expectedAmount: '20,50',
            expectedCurrency: 'TRY',
            expectedReferenceNo: 'REF-1',
        );

        $this->assertInstanceOf(PaymentVerificationResponse::class, $response);
        $this->assertTrue($response->successful());
        $this->assertSame('xk_123', $response->transaction?->xactId);
        $this->assertSame('WRLD', $response->transaction?->bankId);
    }

    public function test_client_exposes_documented_ratio_and_reversal_endpoints(): void
    {
        Http::fake([
            'https://pts-api.paynet.com.tr/v1/ratio/Get_public' => Http::response(['code' => 0, 'data' => []]),
            'https://pts-api.paynet.com.tr/v1/ratio/set_type' => Http::response(['code' => 0, 'ratio_code' => 'R1']),
            'https://pts-api.paynet.com.tr/v1/ratio/delete_type' => Http::response(['code' => 0]),
            'https://pts-api.paynet.com.tr/v1/ratio/define' => Http::response(['code' => 0]),
            'https://pts-api.paynet.com.tr/v1/transaction/preauth_reversal' => Http::response(['code' => 0]),
            'https://pts-api.paynet.com.tr/v1/transaction/capture_reversal' => Http::response([
                'code' => 0,
                'object_name' => 'transaction',
                'xact_id' => 'xk_1',
                'bank_id' => 'VAKF',
                'is_succeed' => false,
                'bank_error_message' => 'Banka iptali reddetti',
            ]),
            'https://pts-api.paynet.com.tr/v1/transaction/reversal_list' => Http::response(['code' => 0, 'Data' => []]),
        ]);

        $client = new PaynetClient('secret');

        $this->assertTrue($client->getPublicRatios(new RatioParams(bin: '540061'))->apiSuccessful());
        $this->assertTrue($client->setRatioType(new RatioTypeParams(description: 'API oran'))->apiSuccessful());
        $this->assertTrue($client->deleteRatioType(new RatioCodeParams(ratioCode: 'R1'))->apiSuccessful());
        $this->assertTrue($client->defineRatio(new RatioDefinitionParams(
            ratioCode: 'R1',
            banks: [
                [
                    'bank_id' => 'DENZ',
                    'instalments' => [
                        ['instalment' => 0, 'ratio' => 0.1],
                    ],
                ],
            ],
        ))->apiSuccessful());
        $this->assertTrue($client->cancelPreAuthorization(new TransactionIdParams(xactId: 'xk_1'))->apiSuccessful());
        $capture = $client->cancelCapture(new TransactionIdParams(xactId: 'xk_1'));
        $this->assertInstanceOf(CaptureReversalResponse::class, $capture);
        $this->assertTrue($capture->apiSuccessful());
        $this->assertFalse($capture->successful());
        $this->assertSame('VAKF', $capture->bankId);
        $this->assertTrue($client->listReversals(new ReversalListParams(datab: '2026-01-01', datbi: '2026-01-02'))->apiSuccessful());
    }

    public function test_client_exposes_typed_saved_card_endpoints_and_otp_check_payload(): void
    {
        Http::fake([
            'https://pts-api.paynet.com.tr/v1/card/save' => Http::response([
                'object_name' => 'card_save',
                'code' => 0,
                'message' => 'Basarili Islem',
                'card_owner_id' => 'owner-1',
                'user_unique_id' => 'user-1',
                'card_desc' => 'Kartim',
                'card_hash' => 'hash-1',
                'card_no' => '435508******8053',
                'card_bin' => '435508',
                'card_type' => 'cc',
                'card_bank_name' => 'WorldCard',
                'card_logo_url' => 'https://example.test/logo.png',
                'card_brand_name' => 'VISA',
            ]),
            'https://pts-api.paynet.com.tr/v1/card/list' => Http::response([
                'object_name' => 'card_list',
                'code' => 0,
                'total' => 1,
                'total_count' => 1,
                'limit' => 10,
                'ending_before' => 0,
                'starting_after' => 0,
                'has_more' => false,
                'Data' => [[
                    'card_owner_id' => 'owner-1',
                    'user_unique_id' => 'user-1',
                    'card_hash' => 'hash-1',
                    'card_desc' => 'Kartim',
                    'card_no' => '435508******8053',
                    'card_bin' => '435508',
                    'card_type' => 'cc',
                    'card_bank_id' => 'AXSS',
                    'card_bank_name' => 'Axess',
                    'card_logo_url' => 'https://example.test/logo.png',
                    'card_brand_name' => 'VISA',
                ]],
            ]),
            'https://pts-api.paynet.com.tr/v1/card/send_otp' => Http::response([
                'object_name' => 'card_send_otp',
                'code' => 0,
                'message' => 'Basarili Islem',
                'otp_expire' => '2017-07-17T15:30:15.0581408+03:00',
            ]),
            'https://pts-api.paynet.com.tr/v1/card/check_otp' => Http::response([
                'code' => 0,
                'message' => 'Basarili Islem',
            ]),
        ]);

        $client = new PaynetClient('secret');

        $save = $client->saveCard(new SaveCardParams(
            cardDesc: 'Kartim',
            cardHolder: 'John Doe',
            cardNumber: '4355080000000000',
            expireMonth: '12',
            expireYear: '2030',
            userUniqueId: 'user-1',
        ));

        $cards = $client->listCards(new CardListParams(cardOwnerId: 'owner-1'));
        $otp = $client->sendCardOtp(new SavedCardOtpParams(
            userGsm: '5324818942',
            otpSessionId: 'otp-session',
        ));
        $check = $client->checkOtpForSavedCard(new SavedCardOtpCheckParams(
            userGsm: '5324818942',
            otpSessionId: 'otp-session',
            otpCode: 'ART2',
        ));

        $this->assertInstanceOf(SavedCardResponse::class, $save);
        $this->assertSame('owner-1', $save->cardOwnerId);
        $this->assertInstanceOf(SavedCardListResponse::class, $cards);
        $this->assertSame('AXSS', $cards->cards[0]->cardBankId);
        $this->assertInstanceOf(SavedCardOtpResponse::class, $otp);
        $this->assertSame('2017-07-17T15:30:15.0581408+03:00', $otp->otpExpire);
        $this->assertTrue($check->successful());

        Http::assertSent(fn ($request) => $request->url() === 'https://pts-api.paynet.com.tr/v1/card/check_otp'
            && $request['user_gsm'] === '5324818942'
            && $request['otp_session_id'] === 'otp-session'
            && $request['otp_code'] === 'ART2');
    }
}
