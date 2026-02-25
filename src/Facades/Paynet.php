<?php

namespace Paynet\Facades;

use Illuminate\Support\Facades\Facade;
use Paynet\PaynetClient;

/**
 * @method static \Illuminate\Http\Client\Response payment(\Paynet\DTOs\PaymentParams $params)
 * @method static \Illuminate\Http\Client\Response tdsInitial(\Paynet\DTOs\TdsInitialParams $params)
 * @method static \Illuminate\Http\Client\Response tdsCharge(\Paynet\DTOs\TdsChargeParams $params)
 * @method static \Illuminate\Http\Client\Response charge(\Paynet\DTOs\ChargeParams $params)
 * @method static \Illuminate\Http\Client\Response checkTransaction(\Paynet\DTOs\CheckTransactionParams $params)
 * @method static \Illuminate\Http\Client\Response getRatios(\Paynet\DTOs\RatioParams $params)
 * @method static \Illuminate\Http\Client\Response createMailOrder(\Paynet\DTOs\MailOrderParams $params)
 * @method static \Illuminate\Http\Client\Response reversedRequest(\Paynet\DTOs\ReversedRequestParams $params)
 * @method static \Illuminate\Http\Client\Response markTransferred(array $params)
 * @method static \Illuminate\Http\Client\Response getTransactionDetail(array $params)
 * @method static \Illuminate\Http\Client\Response saveCard(\Paynet\DTOs\SaveCardParams $params)
 * @method static \Illuminate\Http\Client\Response deleteCard(\Paynet\DTOs\DeleteCardParams $params)
 * @method static \Illuminate\Http\Client\Response updateCardDescription(\Paynet\DTOs\CardDescUpdateParams $params)
 * @method static \Illuminate\Http\Client\Response listCards(\Paynet\DTOs\CardListParams $params)
 * @method static \Illuminate\Http\Client\Response listTransactions(array $params = [])
 * @method static \Illuminate\Http\Client\Response autoLogin(string $userName, ?string $agentId = null)
 * @method static \Illuminate\Http\Client\Response checkIntegration(string $agentId, string $publishableKey, string $secretKey)
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
