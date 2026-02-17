<?php

namespace Paynet\DTOs;

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
        public readonly ?int $bankId = null,
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
    ) {
        parent::__construct($objectName, $code, $message);
    }

    public static function fromJson(object $json): static
    {
        return new static(
            objectName: $json->object_name ?? null,
            code: ResultCode::fromCode((int) ($json->code ?? 1)),
            message: $json->message ?? null,
            
            xactId: $json->xact_id ?? null,
            xactDate: $json->xact_date ?? null,
            transactionType: isset($json->transaction_type) ? (int) $json->transaction_type : null,
            posType: isset($json->pos_type) ? (int) $json->pos_type : null,
            agentId: $json->agent_id ?? null,
            isTds: isset($json->is_tds) ? (bool) $json->is_tds : null,
            bankId: isset($json->bank_id) ? (int) $json->bank_id : null,
            instalment: isset($json->instalment) ? (int) $json->instalment : null,
            cardNo: $json->card_no ?? null,
            cardHolder: $json->card_holder ?? null,
            cardType: $json->card_type ?? null,
            ratio: isset($json->ratio) ? (float) $json->ratio : null,
            ratioCode: $json->ratio_code ?? null,
            amount: $json->amount ?? null,
            netAmount: $json->netAmount ?? null,
            comission: $json->comission ?? null,
            comissionTax: $json->comission_tax ?? null,
            endUserComission: $json->end_user_comission ?? null,
            currency: $json->currency ?? null,
            authorizationCode: $json->authorization_code ?? null,
            referenceCode: $json->reference_code ?? null,
            orderId: $json->order_id ?? null,
            isSucceed: isset($json->is_succeed) ? (bool) $json->is_succeed : null,
            xactTransactionId: $json->xact_transaction_id ?? null,
            email: $json->email ?? null,
            phone: $json->phone ?? null,
            note: $json->note ?? null,
            agentReference: $json->agent_reference ?? null,
        );
    }
}
