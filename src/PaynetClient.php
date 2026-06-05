<?php

namespace Paynet;

use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Support\Facades\Http;
use Paynet\DTOs\{
    BaseResponse,
    CardDescUpdateParams,
    CardListParams,
    CaptureReversalResponse,
    ChargeParams,
    ChargeResponse,
    CheckTransactionParams,
    CheckTransactionResponse,
    DeleteCardParams,
    GenericResponse,
    MailOrderParams,
    MailOrderResponse,
    PaymentParams,
    PaymentResponse,
    PaymentVerificationResponse,
    RatioCodeParams,
    RatioDefinitionParams,
    RatioParams,
    RatioResponse,
    RatioTypeParams,
    ReversalListParams,
    ReversedRequestParams,
    SavedCardListResponse,
    SavedCardOtpResponse,
    SavedCardResponse,
    TdsChargeResponse,
    SaveCardParams,
    TdsChargeParams,
    TdsInitialParams,
    TdsInitialResponse,
    TransactionIdParams,
    SavedCardOtpParams,
    SavedCardOtpCheckParams
};
use Paynet\Enums\ResultCode;

class PaynetClient
{
    private const TEST_URL = 'https://pts-api.paynet.com.tr/';
    private const LIVE_URL = 'https://api.paynet.com.tr/';

    private string $baseUrl;

    public function __construct(
        private readonly string $secretKey,
        private readonly bool $isLive = false,
    ) {
        $this->baseUrl = $this->isLive ? self::LIVE_URL : self::TEST_URL;
    }

    /**
     * API'ye istek gönderir.
     *
     * @template T of BaseResponse
     * @param class-string<T> $responseClass
     * @return T
     */
    private function request(string $endpoint, array|object $data, string $responseClass = GenericResponse::class): BaseResponse
    {
        try {
            return $responseClass::fromResponse($this->send($endpoint, $data));
        } catch (\Throwable $throwable) {
            return $responseClass::fromThrowable($throwable);
        }
    }

    private function send(string $endpoint, array|object $data): HttpResponse
    {
        $timeout = config('paynet.timeout', 30);
        $verify = config('paynet.verify_ssl', true);

        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'Authorization' => 'Basic ' . $this->secretKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json; charset=UTF-8',
            ])
            ->timeout($timeout)
            ->withOptions([
                'verify' => $verify,
            ])
            ->post($endpoint, $data instanceof \JsonSerializable ? $data : (array) $data);
    }

    /**
     * V2 Payment - 3D Secure olmadan direkt ödeme
     */
    public function payment(PaymentParams $params): PaymentResponse
    {
        /** @var PaymentResponse */
        return $this->request('v2/transaction/payment', $params->toArray(), PaymentResponse::class);
    }

    /**
     * V2 TDS Initial - 3D Secure başlatma
     */
    public function tdsInitial(TdsInitialParams $params): TdsInitialResponse
    {
        /** @var TdsInitialResponse */
        return $this->request('v2/transaction/tds_initial', $params->toArray(), TdsInitialResponse::class);
    }

    /**
     * V2 TDS Charge - 3D Secure sonrası ödeme onaylama
     */
    public function tdsCharge(TdsChargeParams $params): TdsChargeResponse
    {
        /** @var TdsChargeResponse */
        return $this->request('v2/transaction/tds_charge', $params->toArray(), TdsChargeResponse::class);
    }

    /**
     * V1 Charge - Paynet JS widget sonrası ödeme onaylama
     */
    public function charge(ChargeParams $params): ChargeResponse
    {
        /** @var ChargeResponse */
        return $this->request('v1/transaction/charge', $params->toArray(), ChargeResponse::class);
    }

    /**
     * İşlem sorgulama
     */
    public function checkTransaction(CheckTransactionParams $params): CheckTransactionResponse
    {
        try {
            $raw = $this->send('v1/transaction/check', $params->toArray());
            $data = $this->responseData($raw);

            if ($raw->successful() && isset($data['Data'][0]) && is_array($data['Data'][0])) {
                $data = array_merge([
                    'object_name' => $data['object_name'] ?? null,
                    'code' => $data['code'] ?? ResultCode::Successful->value,
                    'message' => $data['message'] ?? null,
                ], $data['Data'][0]);
            }

            return CheckTransactionResponse::fromArray($data, $raw);
        } catch (\Throwable $throwable) {
            return CheckTransactionResponse::fromThrowable($throwable);
        }
    }

    /**
     * Oran/Taksit bilgilerini getir
     */
    public function getRatios(RatioParams $params): RatioResponse
    {
        /** @var RatioResponse */
        return $this->request('v1/ratio/Get', $params->toArray(), RatioResponse::class);
    }

    public function getPublicRatios(RatioParams $params): RatioResponse
    {
        /** @var RatioResponse */
        return $this->request('v1/ratio/Get_public', $params->toArray(), RatioResponse::class);
    }

    public function setRatioType(RatioTypeParams $params): GenericResponse
    {
        /** @var GenericResponse */
        return $this->request('v1/ratio/set_type', $params->toArray());
    }

    public function deleteRatioType(RatioCodeParams $params): GenericResponse
    {
        /** @var GenericResponse */
        return $this->request('v1/ratio/delete_type', $params->toArray());
    }

    public function defineRatio(RatioDefinitionParams $params): GenericResponse
    {
        /** @var GenericResponse */
        return $this->request('v1/ratio/define', $params->toArray());
    }

    /**
     * Mail/SMS ile ödeme linki oluştur
     */
    public function createMailOrder(MailOrderParams $params): MailOrderResponse
    {
        /** @var MailOrderResponse */
        return $this->request('v1/mailorder/create', $params->toArray(), MailOrderResponse::class);
    }

    /**
     * İade/İptal talebi
     */
    public function requestReversal(ReversedRequestParams $params): GenericResponse
    {
        /** @var GenericResponse */
        return $this->request('v1/transaction/reversed_request', $params->toArray());
    }

    public function reversedRequest(ReversedRequestParams $params): GenericResponse
    {
        return $this->requestReversal($params);
    }

    public function listReversals(ReversalListParams $params): GenericResponse
    {
        /** @var GenericResponse */
        return $this->request('v1/transaction/reversal_list', $params->toArray());
    }

    public function cancelPreAuthorization(TransactionIdParams $params): GenericResponse
    {
        /** @var GenericResponse */
        return $this->request('v1/transaction/preauth_reversal', $params->toArray());
    }

    public function cancelCapture(TransactionIdParams $params): CaptureReversalResponse
    {
        /** @var CaptureReversalResponse */
        return $this->request('v1/transaction/capture_reversal', $params->toArray(), CaptureReversalResponse::class);
    }

    /**
     * İşlemi transfer edildi olarak işaretle
     */
    public function markTransferred(array $params): GenericResponse
    {
        return $this->request('v1/transaction/mark_transferred', $params);
    }

    /**
     * İşlem detaylarını getir
     */
    public function getTransactionDetail(array $params): GenericResponse
    {
        return $this->request('v1/transaction/detail', $params);
    }

    /**
     * Kart Bilgisi Saklama
     */
    public function saveCard(SaveCardParams $params): SavedCardResponse
    {
        /** @var SavedCardResponse */
        return $this->request('v1/card/save', $params->toArray(), SavedCardResponse::class);
    }

    /**
     * Saklanmis karti sil
     */
    public function deleteCard(DeleteCardParams $params): GenericResponse
    {
        return $this->request('v1/card/delete', $params->toArray());
    }

    /**
     * Sakli kartin aciklamasini guncelle
     */
    public function updateCardDescription(CardDescUpdateParams $params): GenericResponse
    {
        return $this->request('v1/card/desc_update', $params->toArray());
    }

    /**
     * Sakli kartlari listele
     */
    public function listCards(CardListParams $params): SavedCardListResponse
    {
        /** @var SavedCardListResponse */
        return $this->request('v1/card/list', $params->toArray(), SavedCardListResponse::class);
    }

    /**
     * Sakli Kart - OTP Gönderme
     */
    public function sendCardOtp(SavedCardOtpParams $params): SavedCardOtpResponse
    {
        /** @var SavedCardOtpResponse */
        return $this->request('v1/card/send_otp', $params->toArray(), SavedCardOtpResponse::class);
    }

    public function sendOtpForSavedCard(SavedCardOtpParams $params): SavedCardOtpResponse
    {
        return $this->sendCardOtp($params);
    }

    /**
     * Sakli Kart - OTP Kontrol
     */
    public function checkOtpForSavedCard(SavedCardOtpCheckParams $params): GenericResponse
    {
        return $this->request('v1/card/check_otp', $params->toArray());
    }

    /**
     * İşlem listesi
     */
    public function listTransactions(array $params = []): GenericResponse
    {
        $defaults = [
            'agent_id' => '',
            'bank_id' => '',
            'show_unsucceed' => true,
            'limit' => 1000,
            'ending_before' => 0,
            'starting_after' => 0,
            'datab' => date('Y-m-d', strtotime('-10 days')),
            'datbi' => date('Y-m-d', strtotime('+1 day')),
        ];

        return $this->request('v1/transaction/list', array_merge($defaults, $params));
    }

    /**
     * Otomatik giriş linki oluştur
     */
    public function autoLogin(string $userName, ?string $agentId = null): GenericResponse
    {
        return $this->request('v1/agent/autologin', [
            'user_name' => $userName,
            'agent_id' => $agentId,
        ]);
    }

    /**
     * Entegrasyon bilgilerini kontrol et
     */
    public function checkIntegration(string $agentId, string $publishableKey, string $secretKey): GenericResponse
    {
        return $this->request('v1/agent/check_integration_info', [
            'agent_id' => $agentId,
            'publishable_key' => $publishableKey,
            'secret_key' => $secretKey,
        ]);
    }

    /**
     * Test modu mu?
     */
    public function isTestMode(): bool
    {
        return !$this->isLive;
    }

    /**
     * Canlı mod mu?
     */
    public function isLiveMode(): bool
    {
        return $this->isLive;
    }

    /**
     * API URL'ini döndürür
     */
    public function getApiUrl(): string
    {
        return $this->baseUrl;
    }

    public function verifyPayment(
        ?string $xactId = null,
        ?string $referenceNo = null,
        string|int|float|null $expectedAmount = null,
        ?string $expectedCurrency = null,
        ?string $expectedReferenceNo = null,
    ): PaymentVerificationResponse {
        if (!$xactId && !$referenceNo) {
            return new PaymentVerificationResponse(
                objectName: 'payment_verification',
                code: ResultCode::BadRequest,
                message: 'xactId veya referenceNo zorunludur.',
                data: [
                    'object_name' => 'payment_verification',
                    'code' => ResultCode::BadRequest->value,
                    'message' => 'xactId veya referenceNo zorunludur.',
                ],
            );
        }

        $transaction = $this->checkTransaction(new CheckTransactionParams(
            xactId: $xactId,
            referenceNo: $referenceNo,
        ));

        $mismatches = [];

        if ($expectedAmount !== null && $this->normalizeAmount($transaction->amount) !== $this->normalizeAmount($expectedAmount)) {
            $mismatches[] = 'Tutar eslesmedi.';
        }

        if ($expectedCurrency !== null && $transaction->currency !== $expectedCurrency) {
            $mismatches[] = 'Para birimi eslesmedi.';
        }

        if ($expectedReferenceNo !== null && $transaction->get('reference_no') !== $expectedReferenceNo) {
            $mismatches[] = 'Referans numarasi eslesmedi.';
        }

        return PaymentVerificationResponse::fromTransaction($transaction, $mismatches);
    }

    private function responseData(HttpResponse $response): array
    {
        $data = $response->json();

        if (!is_array($data)) {
            return [
                'code' => ResultCode::ServerError->value,
                'message' => 'Gecersiz JSON yaniti',
            ];
        }

        if (!$response->successful() && !isset($data['result_code'])) {
            $data['result_code'] = match ($response->status()) {
                401, 403 => ResultCode::Unauthorized->value,
                400 => ResultCode::BadRequest->value,
                default => ResultCode::ServerError->value,
            };
        }

        return $data;
    }

    private function normalizeAmount(string|int|float|null $amount): ?string
    {
        if ($amount === null || $amount === '') {
            return null;
        }

        $normalized = str_replace(',', '.', (string) $amount);

        return is_numeric($normalized)
            ? number_format((float) $normalized, 2, '.', '')
            : (string) $amount;
    }
}
