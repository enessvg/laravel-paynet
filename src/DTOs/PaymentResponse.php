<?php

namespace Paynet\DTOs;

use Illuminate\Http\Client\Response;
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
        public readonly ?string $bankId = null,
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
        public readonly ?string $cardBankId = null,
        public readonly ?string $cardLogoUrl = null,
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
            
            id: isset($data['id']) ? (string) $data['id'] : null,
            xactId: $data['xact_id'] ?? null,
            xactDate: $data['xact_date'] ?? null,
            transactionType: isset($data['transaction_type']) ? (int) $data['transaction_type'] : null,
            posType: isset($data['pos_type']) ? (int) $data['pos_type'] : null,
            agentId: $data['agent_id'] ?? null,
            userId: $data['user_id'] ?? null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            instalment: isset($data['instalment']) ? (int) $data['instalment'] : null,
            ratio: isset($data['ratio']) ? (float) $data['ratio'] : null,
            cardNoMasked: $data['card_no_masked'] ?? null,
            cardHolder: $data['card_holder'] ?? null,
            amount: isset($data['amount']) ? (string) $data['amount'] : null,
            netAmount: isset($data['net_amount']) ? (string) $data['net_amount'] : null,
            comission: isset($data['comission']) ? (string) $data['comission'] : null,
            comissionTax: isset($data['comission_tax']) ? (string) $data['comission_tax'] : null,
            currency: $data['currency'] ?? null,
            
            bankId: isset($data['bank_id']) ? (string) $data['bank_id'] : null,
            bankName: $data['bank_name'] ?? null,
            bankAuthorizationCode: $data['bank_authorization_code'] ?? null,
            bankReferenceCode: $data['bank_reference_code'] ?? null,
            bankOrderId: $data['bank_order_id'] ?? null,
            isSucceed: isset($data['is_succeed']) ? (bool) $data['is_succeed'] : null,
            paynetErrorId: $data['paynet_error_id'] ?? null,
            paynetErrorMessage: $data['paynet_error_message'] ?? null,
            bankErrorId: $data['bank_error_id'] ?? null,
            bankErrorMessage: $data['bank_error_message'] ?? null,
            bankErrorShortDesc: $data['bank_error_short_desc'] ?? null,
            bankErrorLongDesc: $data['bank_error_long_desc'] ?? null,
            referenceNo: $data['reference_no'] ?? null,
            xactTransactionId: $data['xact_transaction_id'] ?? null,
            campaignUrl: $data['campaign_url'] ?? null,
            endUserComission: isset($data['end_user_comission']) ? (string) $data['end_user_comission'] : null,
            endUserRatio: isset($data['end_user_ratio']) ? (float) $data['end_user_ratio'] : null,
            ratioCode: $data['ratio_code'] ?? null,
            ratioCodeMethod: $data['ratio_code_method'] ?? null,
            cardBrandName: $data['card_brand_name'] ?? null,
            cardType: $data['card_type'] ?? null,
            plusInstallment: isset($data['plus_installment']) ? (int) $data['plus_installment'] : null,
            companyCostRatio: isset($data['company_cost_ratio']) && $data['company_cost_ratio'] !== '' ? (float) $data['company_cost_ratio'] : null,
            companyCommission: isset($data['company_commission']) ? (string) $data['company_commission'] : null,
            companyCommissionWithTax: isset($data['company_commission_with_tax']) ? (string) $data['company_commission_with_tax'] : null,
            companyNetAmount: isset($data['company_net_amount']) ? (string) $data['company_net_amount'] : null,
            
            isSaveCardSucceed: isset($data['is_save_card_succeed']) ? (bool) $data['is_save_card_succeed'] : null,
            saveCardResultMessage: $data['save_card_result_message'] ?? null,
            cardOwnerId: $data['card_owner_id'] ?? null,
            userUniqueId: $data['user_unique_id'] ?? null,
            cardHash: $data['card_hash'] ?? null,
            cardBankId: isset($data['card_bank_id']) ? (string) $data['card_bank_id'] : null,
            cardLogoUrl: $data['card_logo_url'] ?? null,
            data: $data,
            rawResponse: $rawResponse,
        );
    }

    public static function fromJson(object $json): static
    {
        return static::fromArray(static::objectToArray($json));
    }
}
