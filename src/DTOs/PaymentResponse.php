<?php

namespace Paynet\DTOs;

use Paynet\Enums\ResultCode;

class PaymentResponse extends BaseResponse
{
    public function __construct(
        // Base fields
        ?string $objectName = null,
        ResultCode $code = ResultCode::Unsuccessful,
        ?string $message = null,
        
        // Transaction fields
        public readonly ?string $id = null,
        public readonly ?string $xactId = null,
        public readonly ?string $xactDate = null,
        public readonly ?int $transactionType = null,
        public readonly ?int $posType = null,
        public readonly ?string $agentId = null,
        public readonly ?string $userId = null,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?int $instalment = null,
        public readonly ?float $ratio = null,
        public readonly ?string $cardNoMasked = null,
        public readonly ?string $cardHolder = null,
        public readonly ?string $amount = null,
        public readonly ?string $netAmount = null,
        public readonly ?string $comission = null,
        public readonly ?string $comissionTax = null,
        public readonly ?string $currency = null,
        
        // Bank fields
        public readonly ?int $bankId = null,
        public readonly ?string $bankName = null,
        public readonly ?string $bankAuthorizationCode = null,
        public readonly ?string $bankReferenceCode = null,
        public readonly ?string $bankOrderId = null,
        public readonly ?bool $isSucceed = null,
        public readonly ?string $paynetErrorId = null,
        public readonly ?string $paynetErrorMessage = null,
        public readonly ?string $bankErrorId = null,
        public readonly ?string $bankErrorMessage = null,
        public readonly ?string $bankErrorShortDesc = null,
        public readonly ?string $bankErrorLongDesc = null,
        public readonly ?string $referenceNo = null,
        public readonly ?string $xactTransactionId = null,
        public readonly ?string $campaignUrl = null,
        public readonly ?string $endUserComission = null,
        public readonly ?float $endUserRatio = null,
        public readonly ?string $ratioCode = null,
        public readonly ?string $ratioCodeMethod = null,
        public readonly ?string $cardBrandName = null,
        public readonly ?string $cardType = null,
        public readonly ?int $plusInstallment = null,
        public readonly ?float $companyCostRatio = null,
        public readonly ?string $companyCommission = null,
        public readonly ?string $companyCommissionWithTax = null,
        public readonly ?string $companyNetAmount = null,
        
        // Card save fields
        public readonly ?bool $isSaveCardSucceed = null,
        public readonly ?string $saveCardResultMessage = null,
        public readonly ?string $cardOwnerId = null,
        public readonly ?string $userUniqueId = null,
        public readonly ?string $cardHash = null,
        public readonly ?int $cardBankId = null,
        public readonly ?string $cardLogoUrl = null,
    ) {
        parent::__construct($objectName, $code, $message);
    }

    public static function fromJson(object $json): static
    {
        return new static(
            objectName: $json->object_name ?? null,
            code: ResultCode::fromCode((int) ($json->code ?? 1)),
            message: $json->message ?? null,
            
            id: $json->id ?? null,
            xactId: $json->xact_id ?? null,
            xactDate: $json->xact_date ?? null,
            transactionType: $json->transaction_type ?? null,
            posType: $json->pos_type ?? null,
            agentId: $json->agent_id ?? null,
            userId: $json->user_id ?? null,
            email: $json->email ?? null,
            phone: $json->phone ?? null,
            instalment: $json->instalment ?? null,
            ratio: isset($json->ratio) ? (float) $json->ratio : null,
            cardNoMasked: $json->card_no_masked ?? null,
            cardHolder: $json->card_holder ?? null,
            amount: $json->amount ?? null,
            netAmount: $json->net_amount ?? null,
            comission: $json->comission ?? null,
            comissionTax: $json->comission_tax ?? null,
            currency: $json->currency ?? null,
            
            bankId: $json->bank_id ?? null,
            bankName: $json->bank_name ?? null,
            bankAuthorizationCode: $json->bank_authorization_code ?? null,
            bankReferenceCode: $json->bank_reference_code ?? null,
            bankOrderId: $json->bank_order_id ?? null,
            isSucceed: $json->is_succeed ?? null,
            paynetErrorId: $json->paynet_error_id ?? null,
            paynetErrorMessage: $json->paynet_error_message ?? null,
            bankErrorId: $json->bank_error_id ?? null,
            bankErrorMessage: $json->bank_error_message ?? null,
            bankErrorShortDesc: $json->bank_error_short_desc ?? null,
            bankErrorLongDesc: $json->bank_error_long_desc ?? null,
            referenceNo: $json->reference_no ?? null,
            xactTransactionId: $json->xact_transaction_id ?? null,
            campaignUrl: $json->campaign_url ?? null,
            endUserComission: $json->end_user_comission ?? null,
            endUserRatio: isset($json->end_user_ratio) ? (float) $json->end_user_ratio : null,
            ratioCode: $json->ratio_code ?? null,
            ratioCodeMethod: $json->ratio_code_method ?? null,
            cardBrandName: $json->card_brand_name ?? null,
            cardType: $json->card_type ?? null,
            plusInstallment: $json->plus_installment ?? null,
            companyCostRatio: isset($json->company_cost_ratio) ? (float) $json->company_cost_ratio : null,
            companyCommission: $json->company_commission ?? null,
            companyCommissionWithTax: $json->company_commission_with_tax ?? null,
            companyNetAmount: $json->company_net_amount ?? null,
            
            isSaveCardSucceed: $json->is_save_card_succeed ?? null,
            saveCardResultMessage: $json->save_card_result_message ?? null,
            cardOwnerId: $json->card_owner_id ?? null,
            userUniqueId: $json->user_unique_id ?? null,
            cardHash: $json->card_hash ?? null,
            cardBankId: $json->card_bank_id ?? null,
            cardLogoUrl: $json->card_logo_url ?? null,
        );
    }
}
