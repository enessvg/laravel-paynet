<?php

namespace Paynet\DTOs;

use Paynet\Enums\ResultCode;

class ChargeResponse extends BaseResponse
{
    public function __construct(
        ?string $objectName = null,
        ResultCode $code = ResultCode::Unsuccessful,
        ?string $message = null,
        
        public readonly ?string $xactId = null,
        public readonly ?string $xactDate = null,
        public readonly ?int $transactionType = null,
        public readonly ?int $posType = null,
        public readonly ?bool $isTds = null,
        public readonly ?string $agentId = null,
        public readonly ?string $userId = null,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?int $bankId = null,
        public readonly ?int $instalment = null,
        public readonly ?string $cardNoMasked = null,
        public readonly ?string $cardHolder = null,
        public readonly ?string $amount = null,
        public readonly ?string $netAmount = null,
        public readonly ?string $comission = null,
        public readonly ?string $comissionTax = null,
        public readonly ?string $currency = null,
        public readonly ?string $authorizationCode = null,
        public readonly ?string $referenceCode = null,
        public readonly ?string $orderId = null,
        public readonly ?bool $isSucceed = null,
        public readonly ?string $paynetErrorId = null,
        public readonly ?string $paynetErrorMessage = null,
        public readonly ?string $bankErrorId = null,
        public readonly ?string $bankErrorMessage = null,
        public readonly ?string $bankErrorShortDesc = null,
        public readonly ?string $bankErrorLongDesc = null,
        public readonly ?string $agentReferenceNo = null,
        public readonly ?float $ratio = null,
        public readonly ?string $ratioCode = null,
        public readonly ?string $endUserComission = null,
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
            transactionType: $json->transaction_type ?? null,
            posType: $json->pos_type ?? null,
            isTds: $json->is_tds ?? null,
            agentId: $json->agent_id ?? null,
            userId: $json->user_id ?? null,
            email: $json->email ?? null,
            phone: $json->phone ?? null,
            bankId: $json->bank_id ?? null,
            instalment: $json->instalment ?? null,
            cardNoMasked: $json->card_no_masked ?? null,
            cardHolder: $json->card_holder ?? null,
            amount: $json->amount ?? null,
            netAmount: $json->net_amount ?? null,
            comission: $json->comission ?? null,
            comissionTax: $json->comission_tax ?? null,
            currency: $json->currency ?? null,
            authorizationCode: $json->authorization_code ?? null,
            referenceCode: $json->reference_code ?? null,
            orderId: $json->order_id ?? null,
            isSucceed: $json->is_succeed ?? null,
            paynetErrorId: $json->paynet_error_id ?? null,
            paynetErrorMessage: $json->paynet_error_message ?? null,
            bankErrorId: $json->bank_error_id ?? null,
            bankErrorMessage: $json->bank_error_message ?? null,
            bankErrorShortDesc: $json->bank_error_short_desc ?? null,
            bankErrorLongDesc: $json->bank_error_long_desc ?? null,
            agentReferenceNo: $json->agent_reference_no ?? null,
            ratio: isset($json->ratio) ? (float) $json->ratio : null,
            ratioCode: $json->ratio_code ?? null,
            endUserComission: $json->end_user_comission ?? null,
        );
    }
}
