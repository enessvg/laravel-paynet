# Laravel Paynet

Paynet Payment Gateway icin Laravel SDK. PHP 8.2+ ve Laravel 10/11/12 ile uyumludur.

## Kurulum

```bash
composer require enessvg/laravel-paynet
php artisan vendor:publish --tag=paynet-config
```

`.env`:

```env
PAYNET_SECRET_KEY=your_secret_key_here
PAYNET_PUBLIC_KEY=your_public_key_here
PAYNET_IS_LIVE=false
PAYNET_TIMEOUT=30
PAYNET_VERIFY_SSL=true
PAYNET_TDS_RETURN_URL=https://your-domain.com/payment/callback
```

## Response Kullanimi

Tum client methodlari SDK response objesi dondurur. Laravel HTTP response gerekiyorsa `raw()` ile erisebilirsiniz.

```php
$result->successful();     // Tahsilat/operasyon basarili mi?
$result->failed();         // Basarisiz mi?
$result->apiSuccessful();  // Paynet code 0 veya 100 mu?
$result->errorMessage();   // Banka/Paynet hata mesaji
$result->get('xact_id');   // Ham response alanina erisim
$result->toArray();        // Ham body array
$result->raw();            // Illuminate\Http\Client\Response|null
```

Odeme benzeri response'larda `successful()` sadece `is_succeed=true` ve Paynet sonuc kodu `0` veya `100` ise true doner. Yani HTTP 200 veya `code=0` tek basina tahsilat basarisi sayilmaz.

## Direkt Odeme

```php
use Paynet\DTOs\PaymentParams;
use Paynet\Facades\Paynet;
use Paynet\Support\PaynetTools;

$result = Paynet::payment(new PaymentParams(
    amount: PaynetTools::formatWithDecimalSeparator(123.45),
    referenceNo: PaynetTools::generateReferenceNo('ORD-'),
    domain: 'www.acme.com',
    pan: '5400617004770430',
    month: '12',
    year: '2030',
    cvc: '123',
    cardHolder: 'John Doe',
));

if ($result->successful()) {
    $transactionId = $result->xactId;
}

if ($result->failed()) {
    $message = $result->errorMessage();
}
```

Sakli kart ile odeme:

```php
$result = Paynet::payment(new PaymentParams(
    amount: '123,45',
    referenceNo: 'ORD-1001',
    domain: 'www.acme.com',
    cardHash: 'saved-card-token',
));
```

## 3D Odeme

```php
use Paynet\DTOs\TdsChargeParams;
use Paynet\DTOs\TdsInitialParams;
use Paynet\Facades\Paynet;

$initial = Paynet::tdsInitial(new TdsInitialParams(
    amount: '123,45',
    referenceNo: 'ORD-1002',
    returnUrl: route('payment.callback'),
    domain: 'www.acme.com',
    pan: '5400617004770430',
    month: '12',
    year: '2030',
    cvc: '123',
    cardHolder: 'John Doe',
));

if ($initial->successful()) {
    return redirect($initial->postUrl);
}

// Callback icinde Paynet'in post ettigi/session'da saklanan degerlerle:
$charge = Paynet::tdsCharge(new TdsChargeParams(
    sessionId: request('session_id'),
    tokenId: request('token_id'),
));

if ($charge->successful()) {
    $transactionId = $charge->xactId;
}
```

## Odeme Dogrulama

Odeme cevabini aldiktan sonra tek helper ile Paynet sorgusu uzerinden dogrulayabilirsiniz.

```php
$verification = Paynet::verifyPayment(
    referenceNo: 'ORD-1001',
    expectedAmount: '123,45',
    expectedCurrency: 'TRY',
    expectedReferenceNo: 'ORD-1001',
);

if ($verification->successful()) {
    $transaction = $verification->transaction;
}
```

## Oran/Taksit

```php
use Paynet\DTOs\RatioParams;
use Paynet\Facades\Paynet;

$ratios = Paynet::getRatios(new RatioParams(
    amount: '100000',
    bin: '540061',
    addCommissionToAmount: true,
    agentId: '1001',
    cardType: 'cc',
));

foreach ($ratios->banks as $bank) {
    foreach ($bank->ratios as $ratio) {
        echo "{$bank->bankName}: {$ratio->instalment} taksit\n";
    }
}
```

Public oran, oran tipi ve oran tanimlari:

```php
use Paynet\DTOs\RatioCodeParams;
use Paynet\DTOs\RatioDefinitionParams;
use Paynet\DTOs\RatioTypeParams;

Paynet::getPublicRatios(new RatioParams(bin: '540061'));
Paynet::setRatioType(new RatioTypeParams(description: 'API oran'));
Paynet::deleteRatioType(new RatioCodeParams(ratioCode: 'RATIO1'));
Paynet::defineRatio(new RatioDefinitionParams(
    ratioCode: 'RATIO1',
    cardType: 'cc',
    banks: [
        [
            'bank_id' => 'DENZ',
            'instalments' => [
                ['instalment' => 0, 'ratio' => 0.1],
            ],
        ],
    ],
));
```

## Kart Saklama

```php
use Paynet\DTOs\CardDescUpdateParams;
use Paynet\DTOs\CardListParams;
use Paynet\DTOs\DeleteCardParams;
use Paynet\DTOs\SaveCardParams;
use Paynet\DTOs\SavedCardOtpCheckParams;
use Paynet\DTOs\SavedCardOtpParams;
use Paynet\Facades\Paynet;

$save = Paynet::saveCard(new SaveCardParams(
    cardDesc: 'Kisisel Kartim',
    cardHolder: 'John Doe',
    cardNumber: '4355080000000000',
    expireMonth: '12',
    expireYear: '2030',
    cvv: '123',
    userUniqueId: 'user-123',
));

$cardOwnerId = $save->cardOwnerId;

$cards = Paynet::listCards(new CardListParams(
    cardOwnerId: $cardOwnerId,
));

$cardHash = $cards->cards[0]->cardHash;

Paynet::updateCardDescription(new CardDescUpdateParams(
    cardOwnerId: $cardOwnerId,
    cardHash: $cardHash,
    cardDesc: 'Yeni Kart Aciklamasi',
));

Paynet::deleteCard(new DeleteCardParams(
    cardOwnerId: $cardOwnerId,
    cardHash: $cardHash,
));

$otp = Paynet::sendCardOtp(new SavedCardOtpParams(
    userGsm: '5551234567',
    otpSessionId: 'otp-session-id',
));

Paynet::checkOtpForSavedCard(new SavedCardOtpCheckParams(
    userGsm: '5551234567',
    otpSessionId: 'otp-session-id',
    otpCode: 'ART2',
));
```

## Iade ve Iptal

```php
use Paynet\DTOs\ReversalListParams;
use Paynet\DTOs\ReversedRequestParams;
use Paynet\DTOs\TransactionIdParams;
use Paynet\Facades\Paynet;

Paynet::requestReversal(new ReversedRequestParams(
    xactId: 'xk_...',
    amount: '1000',
));

Paynet::listReversals(new ReversalListParams(
    datab: '2026-01-01',
    datbi: '2026-01-02',
));

Paynet::cancelPreAuthorization(new TransactionIdParams(xactId: 'xk_...'));
Paynet::cancelCapture(new TransactionIdParams(xactId: 'xk_...'));
```

## Yardimci Fonksiyonlar

```php
use Paynet\Support\PaynetTools;

PaynetTools::formatWithDecimalSeparator(123.45);    // "123,45"
PaynetTools::formatWithoutDecimalSeparator(123.45); // "12345"
PaynetTools::validateCardNumber('5400617004770430');
PaynetTools::validateExpiryDate('12', '2030');
PaynetTools::validateCvc('123');
PaynetTools::maskCardNumber('5400617004770430');    // "540061******0430"
PaynetTools::getCardBin('5400617004770430');        // "540061"
PaynetTools::generateReferenceNo('ORD-');
```

## Test Kartlari

Paynet test kartlari icin: https://doc.paynet.com.tr/genel-bilgiler/test-kartlari

## Lisans

MIT
