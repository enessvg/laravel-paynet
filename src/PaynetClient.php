<?php

namespace Paynet;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Paynet\DTOs\{
    ChargeParams,
    ChargeResponse,
    CheckTransactionParams,
    CheckTransactionResponse,
    MailOrderParams,
    MailOrderResponse,
    PaymentParams,
    PaymentResponse,
    RatioParams,
    RatioResponse,
    ReversedRequestParams,
    TdsChargeParams,
    TdsChargeResponse,
    TdsInitialParams,
    TdsInitialResponse
};
use Paynet\Enums\ResultCode;

class PaynetClient
{
    private const TEST_URL = 'https://pts-api.paynet.com.tr/';
    private const LIVE_URL = 'https://api.paynet.com.tr/';

    private Client $httpClient;
    private string $baseUrl;

    public function __construct(
        private readonly string $secretKey,
        private readonly bool $isLive = false,
        ?Client $httpClient = null,
    ) {
        $this->baseUrl = $this->isLive ? self::LIVE_URL : self::TEST_URL;
        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
            'verify' => true,
        ]);
    }

    /**
     * API'ye istek gönderir
     * 
     * @return object API yanıtı (başarılı/başarısız fark etmez)
     */
    private function request(string $endpoint, array|object $data): object
    {
        try {
            $response = $this->httpClient->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Basic ' . $this->secretKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json; charset=UTF-8',
                ],
                'json' => $data instanceof \JsonSerializable ? $data : (array) $data,
                'http_errors' => false,
            ]);

            $body = $response->getBody()->getContents();
            $result = json_decode($body);

            // JSON decode başarısız olursa hata objesi döndür
            if ($result === null) {
                return (object) [
                    'code' => ResultCode::ServerError->value,
                    'message' => 'Geçersiz JSON yanıtı',
                ];
            }

            return $result;

        } catch (GuzzleException $e) {
            // Bağlantı hatası durumunda hata objesi döndür
            return (object) [
                'code' => ResultCode::ServerError->value,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * V2 Payment - 3D Secure olmadan direkt ödeme
     */
    public function payment(PaymentParams $params): PaymentResponse
    {
        $result = $this->request('v2/transaction/payment', $params->toArray());
        return PaymentResponse::fromJson($result);
    }

    /**
     * V2 TDS Initial - 3D Secure başlatma
     */
    public function tdsInitial(TdsInitialParams $params): TdsInitialResponse
    {
        $result = $this->request('v2/transaction/tds_initial', $params->toArray());
        return TdsInitialResponse::fromJson($result);
    }

    /**
     * V2 TDS Charge - 3D Secure sonrası ödeme onaylama
     */
    public function tdsCharge(TdsChargeParams $params): TdsChargeResponse
    {
        $result = $this->request('v2/transaction/tds_charge', $params->toArray());
        return TdsChargeResponse::fromJson($result);
    }

    /**
     * V1 Charge - Paynet JS widget sonrası ödeme onaylama
     */
    public function charge(ChargeParams $params): ChargeResponse
    {
        $result = $this->request('v1/transaction/charge', $params->toArray());
        return ChargeResponse::fromJson($result);
    }

    /**
     * İşlem sorgulama
     */
    public function checkTransaction(CheckTransactionParams $params): CheckTransactionResponse
    {
        $result = $this->request('v1/transaction/check', $params->toArray());

        // API başarılı ise Data[0]'ı kullan
        if (isset($result->code) && (int) $result->code === ResultCode::Successful->value && isset($result->Data[0])) {
            $result = $result->Data[0];
        }

        return CheckTransactionResponse::fromJson($result);
    }

    /**
     * Oran/Taksit bilgilerini getir
     */
    public function getRatios(RatioParams $params): RatioResponse
    {
        $result = $this->request('v1/ratio/Get', $params->toArray());
        return RatioResponse::fromJson($result);
    }

    /**
     * Mail/SMS ile ödeme linki oluştur
     */
    public function createMailOrder(MailOrderParams $params): MailOrderResponse
    {
        $result = $this->request('v1/mailorder/create', $params->toArray());
        return MailOrderResponse::fromJson($result);
    }

    /**
     * İade/İptal talebi
     */
    public function reversedRequest(ReversedRequestParams $params): object
    {
        return $this->request('v1/transaction/reversed_request', $params->toArray());
    }

    /**
     * İşlemi transfer edildi olarak işaretle
     */
    public function markTransferred(array $params): object
    {
        return $this->request('v1/transaction/mark_transferred', $params);
    }

    /**
     * İşlem detaylarını getir
     */
    public function getTransactionDetail(array $params): object
    {
        return $this->request('v1/transaction/detail', $params);
    }

    /**
     * İşlem listesi
     */
    public function listTransactions(array $params = []): object
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
    public function autoLogin(string $userName, ?string $agentId = null): object
    {
        return $this->request('v1/agent/autologin', [
            'user_name' => $userName,
            'agent_id' => $agentId,
        ]);
    }

    /**
     * Entegrasyon bilgilerini kontrol et
     */
    public function checkIntegration(string $agentId, string $publishableKey, string $secretKey): object
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
}
