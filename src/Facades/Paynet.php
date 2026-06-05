<?php

namespace Paynet\Facades;

use Illuminate\Support\Facades\Facade;
use Paynet\PaynetClient;

/**
 * @method static \Paynet\DTOs\PaymentResponse payment(\Paynet\DTOs\PaymentParams $params)
 * @method static \Paynet\DTOs\TdsInitialResponse tdsInitial(\Paynet\DTOs\TdsInitialParams $params)
 * @method static \Paynet\DTOs\TdsChargeResponse tdsCharge(\Paynet\DTOs\TdsChargeParams $params)
 * @method static \Paynet\DTOs\ChargeResponse charge(\Paynet\DTOs\ChargeParams $params)
 * @method static \Paynet\DTOs\CheckTransactionResponse checkTransaction(\Paynet\DTOs\CheckTransactionParams $params)
 * @method static \Paynet\DTOs\PaymentVerificationResponse verifyPayment(?string $xactId = null, ?string $referenceNo = null, string|int|float|null $expectedAmount = null, ?string $expectedCurrency = null, ?string $expectedReferenceNo = null)
 * @method static \Paynet\DTOs\RatioResponse getRatios(\Paynet\DTOs\RatioParams $params)
 * @method static \Paynet\DTOs\RatioResponse getPublicRatios(\Paynet\DTOs\RatioParams $params)
 * @method static \Paynet\DTOs\GenericResponse setRatioType(\Paynet\DTOs\RatioTypeParams $params)
 * @method static \Paynet\DTOs\GenericResponse deleteRatioType(\Paynet\DTOs\RatioCodeParams $params)
 * @method static \Paynet\DTOs\GenericResponse defineRatio(\Paynet\DTOs\RatioDefinitionParams $params)
 * @method static \Paynet\DTOs\MailOrderResponse createMailOrder(\Paynet\DTOs\MailOrderParams $params)
 * @method static \Paynet\DTOs\GenericResponse requestReversal(\Paynet\DTOs\ReversedRequestParams $params)
 * @method static \Paynet\DTOs\GenericResponse reversedRequest(\Paynet\DTOs\ReversedRequestParams $params)
 * @method static \Paynet\DTOs\GenericResponse listReversals(\Paynet\DTOs\ReversalListParams $params)
 * @method static \Paynet\DTOs\GenericResponse cancelPreAuthorization(\Paynet\DTOs\TransactionIdParams $params)
 * @method static \Paynet\DTOs\CaptureReversalResponse cancelCapture(\Paynet\DTOs\TransactionIdParams $params)
 * @method static \Paynet\DTOs\GenericResponse markTransferred(array $params)
 * @method static \Paynet\DTOs\GenericResponse getTransactionDetail(array $params)
 * @method static \Paynet\DTOs\SavedCardResponse saveCard(\Paynet\DTOs\SaveCardParams $params)
 * @method static \Paynet\DTOs\GenericResponse deleteCard(\Paynet\DTOs\DeleteCardParams $params)
 * @method static \Paynet\DTOs\GenericResponse updateCardDescription(\Paynet\DTOs\CardDescUpdateParams $params)
 * @method static \Paynet\DTOs\SavedCardListResponse listCards(\Paynet\DTOs\CardListParams $params)
 * @method static \Paynet\DTOs\SavedCardOtpResponse sendCardOtp(\Paynet\DTOs\SavedCardOtpParams $params)
 * @method static \Paynet\DTOs\SavedCardOtpResponse sendOtpForSavedCard(\Paynet\DTOs\SavedCardOtpParams $params)
 * @method static \Paynet\DTOs\GenericResponse checkOtpForSavedCard(\Paynet\DTOs\SavedCardOtpCheckParams $params)
 * @method static \Paynet\DTOs\GenericResponse listTransactions(array $params = [])
 * @method static \Paynet\DTOs\GenericResponse autoLogin(string $userName, ?string $agentId = null)
 * @method static \Paynet\DTOs\GenericResponse checkIntegration(string $agentId, string $publishableKey, string $secretKey)
 * @method static bool isTestMode()
 * @method static bool isLiveMode()
 * @method static string getApiUrl()
 *
 * @see \Paynet\PaynetClient
 */
class Paynet extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PaynetClient::class;
    }
}
