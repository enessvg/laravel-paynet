<?php

namespace Paynet;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Paynet\DTOs\{
    CardDescUpdateParams,
    CardListParams,
    ChargeParams,
    CheckTransactionParams,
    DeleteCardParams,
    MailOrderParams,
    PaymentParams,
    RatioParams,
    ReversedRequestParams,
    SaveCardParams,
    TdsChargeParams,
    TdsInitialParams,
    SavedCardOtpParams,
    SavedCardOtpCheckParams
};

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
     * API'ye istek gönderir
     */
    private function request(string $endpoint, array|object $data): Response
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
    public function payment(PaymentParams $params): Response
    {
        return $this->request('v2/transaction/payment', $params->toArray());
    }

    /**
     * V2 TDS Initial - 3D Secure başlatma
     */
    public function tdsInitial(TdsInitialParams $params): Response
    {
        return $this->request('v2/transaction/tds_initial', $params->toArray());
    }

    /**
     * V2 TDS Charge - 3D Secure sonrası ödeme onaylama
     */
    public function tdsCharge(TdsChargeParams $params): Response
    {
        return $this->request('v2/transaction/tds_charge', $params->toArray());
    }

    /**
     * V1 Charge - Paynet JS widget sonrası ödeme onaylama
     */
    public function charge(ChargeParams $params): Response
    {
        return $this->request('v1/transaction/charge', $params->toArray());
    }

    /**
     * İşlem sorgulama
     */
    public function checkTransaction(CheckTransactionParams $params): Response
    {
        return $this->request('v1/transaction/check', $params->toArray());
    }

    /**
     * Oran/Taksit bilgilerini getir
     */
    public function getRatios(RatioParams $params): Response
    {
        return $this->request('v1/ratio/Get', $params->toArray());
    }

    /**
     * Mail/SMS ile ödeme linki oluştur
     */
    public function createMailOrder(MailOrderParams $params): Response
    {
        return $this->request('v1/mailorder/create', $params->toArray());
    }

    /**
     * İade/İptal talebi
     */
    public function reversedRequest(ReversedRequestParams $params): Response
    {
        return $this->request('v1/transaction/reversed_request', $params->toArray());
    }

    /**
     * İşlemi transfer edildi olarak işaretle
     */
    public function markTransferred(array $params): Response
    {
        return $this->request('v1/transaction/mark_transferred', $params);
    }

    /**
     * İşlem detaylarını getir
     */
    public function getTransactionDetail(array $params): Response
    {
        return $this->request('v1/transaction/detail', $params);
    }

    /**
     * Kart Bilgisi Saklama
     */
    public function saveCard(SaveCardParams $params): Response
    {
        return $this->request('v1/card/save', $params->toArray());
    }

    /**
     * Saklanmis karti sil
     */
    public function deleteCard(DeleteCardParams $params): Response
    {
        return $this->request('v1/card/delete', $params->toArray());
    }

    /**
     * Sakli kartin aciklamasini guncelle
     */
    public function updateCardDescription(CardDescUpdateParams $params): Response
    {
        return $this->request('v1/card/desc_update', $params->toArray());
    }

    /**
     * Sakli kartlari listele
     */
    public function listCards(CardListParams $params): Response
    {
        return $this->request('v1/card/list', $params->toArray());
    }

    /**
     * Sakli Kart - OTP Gönderme
     */
    public function sendOtpForSavedCard(SavedCardOtpParams $params): Response
    {
        return $this->request('v1/card/send_otp', $params->toArray());
    }

    /**
     * Sakli Kart - OTP Kontrol
     */
    public function checkOtpForSavedCard(SavedCardOtpCheckParams $params): Response
    {
        return $this->request('v1/card/check_otp', $params->toArray());
    }

    /**
     * İşlem listesi
     */
    public function listTransactions(array $params = []): Response
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
    public function autoLogin(string $userName, ?string $agentId = null): Response
    {
        return $this->request('v1/agent/autologin', [
            'user_name' => $userName,
            'agent_id' => $agentId,
        ]);
    }

    /**
     * Entegrasyon bilgilerini kontrol et
     */
    public function checkIntegration(string $agentId, string $publishableKey, string $secretKey): Response
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
