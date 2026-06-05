<?php

namespace Paynet\Tests\Unit;

use Paynet\DTOs\PaymentResponse;
use Paynet\DTOs\RatioResponse;
use Paynet\Enums\ResultCode;
use Paynet\Tests\TestCase;

class CleanV1ResponseTest extends TestCase
{
    public function test_payment_success_requires_paynet_success_code_and_is_succeed_true(): void
    {
        $response = PaymentResponse::fromJson((object) [
            'object_name' => 'payment_response',
            'code' => 0,
            'message' => 'Basarili Islem',
            'is_succeed' => false,
            'bank_error_message' => 'Banka islemi reddetti',
        ]);

        $this->assertTrue($response->apiSuccessful());
        $this->assertFalse($response->successful());
        $this->assertTrue($response->failed());
        $this->assertSame('Banka islemi reddetti', $response->errorMessage());
    }

    public function test_payment_response_preserves_raw_data_and_string_codes(): void
    {
        $response = PaymentResponse::fromJson((object) [
            'object_name' => 'payment_response',
            'code' => 100,
            'message' => 'Onceki Basarili Islem',
            'is_succeed' => true,
            'xact_id' => 'xk_123',
            'bank_id' => 'ZDGR',
            'card_bank_id' => 'AXSS',
            'net_amount' => '20,50',
        ]);

        $this->assertTrue($response->successful());
        $this->assertSame(ResultCode::OldSuccessful, $response->code);
        $this->assertSame('ZDGR', $response->bankId);
        $this->assertSame('AXSS', $response->cardBankId);
        $this->assertSame('20,50', $response->netAmount);
        $this->assertSame('ZDGR', $response->get('bank_id'));
        $this->assertSame('xk_123', $response->toArray()['xact_id']);
    }

    public function test_ratio_response_maps_tds_required_and_campaign_fields(): void
    {
        $response = RatioResponse::fromJson((object) [
            'object_name' => 'ratio_get',
            'code' => 0,
            'message' => 'Basarili Islem',
            'tds_required' => true,
            'data' => [
                (object) [
                    'bank_id' => 'AXSS',
                    'bank_name' => 'Axess',
                    'card_type' => 'cc',
                    'tds_required' => false,
                    'ratio' => [
                        (object) [
                            'ratio' => 0.26,
                            'instalment_key' => 'ji_1',
                            'instalment' => 0,
                            'is_has_campaign' => true,
                            'plus_installment' => 2,
                            'post_pone' => 1,
                            'campaign_note' => '1+2',
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertTrue($response->tdsRequired);
        $this->assertSame('AXSS', $response->banks[0]->bankId);
        $this->assertSame('cc', $response->banks[0]->cardType);
        $this->assertFalse($response->banks[0]->tdsRequired);
        $this->assertTrue($response->banks[0]->ratios[0]->isHasCampaign);
        $this->assertSame(2, $response->banks[0]->ratios[0]->plusInstallment);
        $this->assertSame(1, $response->banks[0]->ratios[0]->postPone);
        $this->assertSame('1+2', $response->banks[0]->ratios[0]->campaignNote);
    }

    public function test_capture_reversal_success_requires_is_succeed_true(): void
    {
        $failed = \Paynet\DTOs\CaptureReversalResponse::fromJson((object) [
            'object_name' => 'transaction',
            'code' => 0,
            'message' => 'Basarili Islem',
            'xact_id' => 'xk_1',
            'bank_id' => 'VAKF',
            'authorization_code' => '',
            'reference_code' => '',
            'order_id' => '',
            'is_succeed' => false,
            'bank_error_message' => 'Banka iptali reddetti',
        ]);

        $this->assertTrue($failed->apiSuccessful());
        $this->assertFalse($failed->successful());
        $this->assertSame('VAKF', $failed->bankId);
        $this->assertSame('Banka iptali reddetti', $failed->errorMessage());

        $successful = \Paynet\DTOs\CaptureReversalResponse::fromJson((object) [
            'object_name' => 'transaction',
            'code' => 0,
            'message' => 'Basarili Islem',
            'xact_id' => 'xk_1',
            'is_succeed' => true,
        ]);

        $this->assertTrue($successful->successful());
    }

    public function test_saved_card_response_maps_documented_fields(): void
    {
        $response = \Paynet\DTOs\SavedCardResponse::fromJson((object) [
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
        ]);

        $this->assertTrue($response->successful());
        $this->assertSame('owner-1', $response->cardOwnerId);
        $this->assertSame('hash-1', $response->cardHash);
        $this->assertSame('435508', $response->cardBin);
        $this->assertSame('cc', $response->cardType);
    }

    public function test_saved_card_list_response_maps_cards(): void
    {
        $response = \Paynet\DTOs\SavedCardListResponse::fromJson((object) [
            'object_name' => 'card_list',
            'code' => 0,
            'message' => null,
            'total' => 1,
            'total_count' => 2,
            'limit' => 10,
            'ending_before' => 0,
            'starting_after' => 0,
            'has_more' => false,
            'Data' => [
                (object) [
                    'card_owner_id' => 'owner-1',
                    'user_unique_id' => 'user-1',
                    'card_hash' => 'hash-1',
                    'card_desc' => 'Paynet Odeme',
                    'card_holder' => 'RQ*****QR',
                    'card_no' => '434678********0002',
                    'card_bin' => '434678',
                    'card_type' => 'cc',
                    'card_bank_id' => 'AXSS',
                    'card_bank_name' => 'Axess',
                    'card_logo_url' => 'https://example.test/logo.png',
                    'card_brand_name' => 'VISA',
                    'expire_month' => 12,
                    'expire_year' => 2030,
                ],
            ],
        ]);

        $this->assertTrue($response->successful());
        $this->assertSame(1, $response->total);
        $this->assertSame(2, $response->totalCount);
        $this->assertFalse($response->hasMore);
        $this->assertSame('AXSS', $response->cards[0]->cardBankId);
        $this->assertSame('hash-1', $response->cards[0]->cardHash);
        $this->assertSame('hash-1', $response->get('Data.0.card_hash'));
    }

    public function test_saved_card_otp_response_maps_expire_time(): void
    {
        $response = \Paynet\DTOs\SavedCardOtpResponse::fromJson((object) [
            'object_name' => 'card_send_otp',
            'code' => 0,
            'message' => 'Basarili Islem',
            'otp_expire' => '2017-07-17T15:30:15.0581408+03:00',
        ]);

        $this->assertTrue($response->successful());
        $this->assertSame('2017-07-17T15:30:15.0581408+03:00', $response->otpExpire);
    }
}
