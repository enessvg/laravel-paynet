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
 * @method static \Paynet\DTOs\RatioResponse getRatios(\Paynet\DTOs\RatioParams $params)
 * @method static \Paynet\DTOs\MailOrderResponse createMailOrder(\Paynet\DTOs\MailOrderParams $params)
 * @method static object reversedRequest(\Paynet\DTOs\ReversedRequestParams $params)
 * @method static object markTransferred(array $params)
 * @method static object getTransactionDetail(array $params)
 * @method static object listTransactions(array $params = [])
 * @method static object autoLogin(string $userName, ?string $agentId = null)
 * @method static object checkIntegration(string $agentId, string $publishableKey, string $secretKey)
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
