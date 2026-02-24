# Laravel Paynet

Paynet Payment Gateway için Laravel paketi. PHP 8.2+ ve Laravel 10/11/12 uyumlu.

## Kurulum

```bash
composer require enessvg/laravel-paynet
```

## Yapılandırma

Config dosyasını yayınlayın:

```bash
php artisan vendor:publish --tag=paynet-config
```

`.env` dosyanıza aşağıdaki değişkenleri ekleyin:

```env
PAYNET_SECRET_KEY=your_secret_key_here
PAYNET_PUBLIC_KEY=your_public_key_here
PAYNET_IS_LIVE=false
PAYNET_TDS_RETURN_URL=https://your-domain.com/payment/callback
```

## Kullanım

### Facade ile Kullanım

```php
use Paynet\Facades\Paynet;
use Paynet\DTOs\PaymentParams;
use Paynet\Support\PaynetTools;

// Direkt ödeme (3D Secure olmadan)
$params = new PaymentParams(
    amount: PaynetTools::formatWithDecimalSeparator(123.45),
    pan: '5400617004770430',
    month: '12',
    year: '25',
    cvc: '123',
    cardHolder: 'John Doe',
    referenceNo: PaynetTools::generateReferenceNo('ORD-'),
    instalment: 1,
);

$result = Paynet::payment($params);

if ($result->code === 0) {
    // Ödeme başarılı
    $transactionId = $result->xactId;
} else {
    // Hata
    $errorMessage = $result->bankErrorMessage ?? $result->message;
}
```

### Dependency Injection ile Kullanım

```php
use Paynet\PaynetClient;
use Paynet\DTOs\PaymentParams;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaynetClient $paynet
    ) {}

    public function processPayment(Request $request)
    {
        $params = new PaymentParams(
            amount: '123,45',
            pan: $request->card_number,
            month: $request->expire_month,
            year: $request->expire_year,
            cvc: $request->cvc,
            cardHolder: $request->card_holder,
        );

        return $this->paynet->payment($params);
    }
}
```

### 3D Secure Ödeme

```php
use Paynet\Facades\Paynet;
use Paynet\DTOs\TdsInitialParams;
use Paynet\DTOs\TdsChargeParams;
use Paynet\Enums\ResultCode;

// Adım 1: 3D Secure başlat
$params = new TdsInitialParams(
    amount: '123,45',
    pan: '5400617004770430',
    month: '12',
    year: '25',
    cvc: '123',
    returnUrl: route('payment.callback'),
    cardHolder: 'John Doe',
    referenceNo: 'ORD-12345',
);

$result = Paynet::tdsInitial($params);

if ($result->code === ResultCode::Successful) {
    // Session'a kaydet
    session(['paynet_session_id' => $result->sessionId]);
    session(['paynet_token_id' => $result->tokenId]);
    
    // 3D sayfasına yönlendir
    return redirect($result->postUrl);
}

// Adım 2: Callback'de ödemeyi onayla
public function callback(Request $request)
{
    $params = new TdsChargeParams(
        sessionId: session('paynet_session_id'),
        tokenId: session('paynet_token_id'),
    );

    $result = Paynet::tdsCharge($params);

    if ($result->isSucceed) {
        return view('payment.success', ['transaction' => $result]);
    }

    return view('payment.error', ['error' => $result->bankErrorMessage]);
}
```

### Taksit/Oran Sorgulama

```php
use Paynet\Facades\Paynet;
use Paynet\DTOs\RatioParams;

$params = new RatioParams(
    amount: '1000,00',
    bin: '540061', // Kartın ilk 6 hanesi (opsiyonel)
);

$result = Paynet::getRatios($params);

if ($result->code === 0 && isset($result->banks)) {
    foreach ($result->banks as $bank) {
        echo "Banka: {$bank->bankName}\n";
        foreach ($bank->ratios as $ratio) {
            echo "  {$ratio->instalment} Taksit: %{$ratio->ratio}\n";
        }
    }
    // Veya HTML tablo olarak
    // echo $result->toHtmlTable();
} else {
    echo $result->message;
}
```

### İşlem Sorgulama

```php
use Paynet\Facades\Paynet;
use Paynet\DTOs\CheckTransactionParams;

$result = Paynet::checkTransaction(new CheckTransactionParams(
    xactId: '12345678',
));

// veya referans numarası ile
$result = Paynet::checkTransaction(new CheckTransactionParams(
    referenceNo: 'ORD-12345',
));

if ($result->isSucceed) {
    echo "İşlem durumu: Başarılı";
    echo "Tutar: {$result->amount}";
}
```

### Mail/SMS ile Ödeme Linki

```php
use Paynet\Facades\Paynet;
use Paynet\DTOs\MailOrderParams;

$params = new MailOrderParams(
    amount: '500,00',
    nameSurname: 'Ahmet Yılmaz',
    userName: 'ahmet@example.com',
    email: 'ahmet@example.com',
    sendMail: true,
    phone: '05551234567',
    sendSms: true,
    expireDate: 24, // 24 saat geçerli
    succeedUrl: route('payment.success'),
    errorUrl: route('payment.error'),
);

$result = Paynet::createMailOrder($params);

if ($result->isSuccessful()) {
    $paymentUrl = $result->url;
}
```

### Paynet JS Widget ile Kullanım (v1)

```php
// Blade view
<form action="{{ route('payment.charge') }}" method="post" id="checkout-form">
    @csrf
    <script type="text/javascript"
        class="paynet-button"
        src="https://pts-pj.paynet.com.tr/public/js/paynet.js"
        data-key="{{ config('paynet.public_key') }}"
        data-description="Ödemenizi tamamlamak için bilgileri girip tamam butonuna basınız"
        data-amount="{{ \Paynet\Support\PaynetTools::formatWithoutDecimalSeparator($amount) }}"
        data-button_label="Ödemeyi Tamamla"
        data-pos_type="5">
    </script>
</form>

// Controller
use Paynet\Facades\Paynet;
use Paynet\DTOs\ChargeParams;
use Paynet\Support\PaynetTools;

public function charge(Request $request)
{
    $params = new ChargeParams(
        sessionId: $request->session_id,
        tokenId: $request->token_id,
        amount: (string) PaynetTools::formatWithoutDecimalSeparator(session('amount')),
    );

    $result = Paynet::charge($params);

    if ($result->isSucceed) {
        return redirect()->route('payment.success');
    }

    return back()->withErrors(['payment' => $result->bankErrorMessage]);
}
```

## Yardımcı Fonksiyonlar

```php
use Paynet\Support\PaynetTools;

// Tutar formatlama
$formatted = PaynetTools::formatWithDecimalSeparator(123.45); // "123,45"
$cents = PaynetTools::formatWithoutDecimalSeparator(123.45);  // 12345

// Kart doğrulama
$isValid = PaynetTools::validateCardNumber('5400617004770430'); // true
$isValidExpiry = PaynetTools::validateExpiryDate('12', '25');   // true
$isValidCvc = PaynetTools::validateCvc('123');                  // true

// Kart maskeleme
$masked = PaynetTools::maskCardNumber('5400617004770430'); // "540061******0430"

// BIN numarası
$bin = PaynetTools::getCardBin('5400617004770430'); // "540061"

// Benzersiz referans numarası
$refNo = PaynetTools::generateReferenceNo('ORD-'); // "ORD-17082532451234"
```

## Enum Kullanımı

```php
use Paynet\Enums\ResultCode;
use Paynet\Enums\TransactionType;

// Sonuç kodu kontrolü
if ($result->code === ResultCode::Successful) {
    // Başarılı
}

// Açıklama alma
$description = ResultCode::Successful->description(); // "İşlem başarılı"

// İşlem tipi
$params->transactionType = TransactionType::Sale;     // Satış
$params->transactionType = TransactionType::Refund;   // İade
```

## Hata Yönetimi

```php
use Paynet\Exceptions\PaynetException;

try {
    $result = Paynet::payment($params);
} catch (PaynetException $e) {
    // Bağlantı veya yapılandırma hatası
    Log::error('Paynet Error: ' . $e->getMessage(), [
        'result_code' => $e->resultCode?->value,
        'bank_error' => $e->bankErrorMessage,
    ]);
}
```

## Test Kartları
Altta ki linkten detaylı bir şekilde ulaşabilirsiniz

https://doc.paynet.com.tr/genel-bilgiler/test-kartlari

## Lisans

MIT
