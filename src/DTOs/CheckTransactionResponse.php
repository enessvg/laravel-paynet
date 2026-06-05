<?php

namespace Paynet\DTOs;

use Illuminate\Http\Client\Response;
use Paynet\Enums\ResultCode;

class CheckTransactionResponse extends BaseResponse
{
    public function __construct(
        ?string $objectName = null,
        ResultCode $code = ResultCode::Unsuccessful,
        ?string $message = null,
        
        public readonly ?string $xactId = null,
        public readonly ?string $xactDate = null,
        public readonly ?int $transactionType = null,
        public readonly ?int $posType = null,
        public readonly ?string $agentId = null,
        public readonly ?bool $isTds = null,
        public readonly ?string $bankId = null,
        public readonly ?int $instalment = null,
        public readonly ?string $cardNo = null,
        public readonly ?string $cardHolder = null,
        public readonly ?string $cardType = null,
        public readonly ?float $ratio = null,
        public readonly ?string $ratioCode = null,
        public readonly ?string $amount = null,
        public readonly ?string $netAmount = null,
        public readonly ?string $comission = null,
        public readonly ?string $comissionTax = null,
        public readonly ?string $endUserComission = null,
        public readonly ?string $currency = null,
        public readonly ?string $authorizationCode = null,
        public readonly ?string $referenceCode = null,
        public readonly ?string $orderId = null,
        public readonly ?bool $isSucceed = null,
        public readonly ?string $xactTransactionId = null,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?string $note = null,
        public readonly ?string $agentReference = null,
        array $data = [],
        ?Response $rawResponse = null,
    ) {
        parent::__construct($objectName, $code, $message, $data, $rawResponse);
    }

    public function successful(): bool
    {
        return $this->apiSuccessful() && $this->isSucceed === true;
    }

    public static function fromArray(array $data, ?Response $rawResponse = null): static
    {
        return new static(
            objectName: $data['object_name'] ?? null,
            code: ResultCode::fromCode((int) ($data['result_code'] ?? $data['code'] ?? 1)),
            message: $data['message'] ?? null,
            
            xactId: $data['xact_id'] ?? null,
            xactDate: $data['xact_date'] ?? null,
            transactionType: isset($data['transaction_type']) ? (int) $data['transaction_type'] : null,
            posType: isset($data['pos_type']) ? (int) $data['pos_type'] : null,
            agentId: $data['agent_id'] ?? null,
            isTds: isset($data['is_tds']) ? (bool) $data['is_tds'] : null,
            bankId: isset($data['bank_id']) ? (string) $data['bank_id'] : null,
            instalment: isset($data['instalment']) ? (int) $data['instalment'] : null,
            cardNo: $data['card_no'] ?? $data['cardno'] ?? null,
            cardHolder: $data['card_holder'] ?? null,
            cardType: $data['card_type'] ?? null,
            ratio: isset($data['ratio']) ? (float) $data['ratio'] : null,
            ratioCode: $data['ratio_code'] ?? null,
            amount: isset($data['amount']) ? (string) $data['amount'] : null,
            netAmount: isset($data['net_amount']) ? (string) $data['net_amount'] : null,
            comission: isset($data['comission']) ? (string) $data['comission'] : null,
            comissionTax: isset($data['comission_tax']) ? (string) $data['comission_tax'] : null,
            endUserComission: isset($data['end_user_comission']) ? (string) $data['end_user_comission'] : null,
            currency: $data['currency'] ?? null,
            authorizationCode: $data['authorization_code'] ?? $data['authorizationcode'] ?? null,
            referenceCode: $data['reference_code'] ?? $data['referencecode'] ?? null,
            orderId: $data['order_id'] ?? null,
            isSucceed: isset($data['is_succeed']) ? (bool) $data['is_succeed'] : null,
            xactTransactionId: $data['xact_transaction_id'] ?? null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            note: $data['note'] ?? null,
            agentReference: $data['agent_reference'] ?? $data['reference_no'] ?? null,
            data: $data,
            rawResponse: $rawResponse,
        );
    }

    public static function fromJson(object $json): static
    {
        return static::fromArray(static::objectToArray($json));
    }
}
