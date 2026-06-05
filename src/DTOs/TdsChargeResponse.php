<?php

namespace Paynet\DTOs;

use Illuminate\Http\Client\Response;
use Paynet\Enums\ResultCode;

class TdsChargeResponse extends PaymentResponse
{
    public function __construct(
        ?string $objectName = null,
        ResultCode $code = ResultCode::Unsuccessful,
        ?string $message = null,
        ?string $id = null,
        ?string $xactId = null,
        ?string $xactDate = null,
        ?int $transactionType = null,
        ?int $posType = null,
        ?string $agentId = null,
        ?string $userId = null,
        ?string $email = null,
        ?string $phone = null,
        ?int $instalment = null,
        ?float $ratio = null,
        ?string $cardNoMasked = null,
        ?string $cardHolder = null,
        ?string $amount = null,
        ?string $netAmount = null,
        ?string $comission = null,
        ?string $comissionTax = null,
        ?string $currency = null,
        ?string $bankId = null,
        ?string $bankName = null,
        ?string $bankAuthorizationCode = null,
        ?string $bankReferenceCode = null,
        ?string $bankOrderId = null,
        ?bool $isSucceed = null,
        ?string $paynetErrorId = null,
        ?string $paynetErrorMessage = null,
        ?string $bankErrorId = null,
        ?string $bankErrorMessage = null,
        ?string $bankErrorShortDesc = null,
        ?string $bankErrorLongDesc = null,
        ?string $referenceNo = null,
        ?string $xactTransactionId = null,
        ?string $campaignUrl = null,
        ?string $endUserComission = null,
        ?float $endUserRatio = null,
        ?string $ratioCode = null,
        ?string $ratioCodeMethod = null,
        ?string $cardBrandName = null,
        ?string $cardType = null,
        ?int $plusInstallment = null,
        ?float $companyCostRatio = null,
        ?string $companyCommission = null,
        ?string $companyCommissionWithTax = null,
        ?string $companyNetAmount = null,
        ?bool $isSaveCardSucceed = null,
        ?string $saveCardResultMessage = null,
        ?string $cardOwnerId = null,
        ?string $userUniqueId = null,
        ?string $cardHash = null,
        ?string $cardBankId = null,
        ?string $cardLogoUrl = null,
        array $data = [],
        ?Response $rawResponse = null,
        
        // TDS specific fields
        public readonly ?bool $isTds = null,
        public readonly ?string $mdStatus = null,
    ) {
        parent::__construct(
            $objectName, $code, $message, $id, $xactId, $xactDate, $transactionType,
            $posType, $agentId, $userId, $email, $phone, $instalment, $ratio,
            $cardNoMasked, $cardHolder, $amount, $netAmount, $comission, $comissionTax,
            $currency, $bankId, $bankName, $bankAuthorizationCode, $bankReferenceCode,
            $bankOrderId, $isSucceed, $paynetErrorId, $paynetErrorMessage, $bankErrorId,
            $bankErrorMessage, $bankErrorShortDesc, $bankErrorLongDesc, $referenceNo,
            $xactTransactionId, $campaignUrl, $endUserComission, $endUserRatio, $ratioCode,
            $ratioCodeMethod, $cardBrandName, $cardType, $plusInstallment, $companyCostRatio,
            $companyCommission, $companyCommissionWithTax, $companyNetAmount,
            $isSaveCardSucceed, $saveCardResultMessage, $cardOwnerId, $userUniqueId,
            $cardHash, $cardBankId, $cardLogoUrl, $data, $rawResponse
        );
    }

    public static function fromArray(array $data, ?Response $rawResponse = null): static
    {
        $parent = PaymentResponse::fromArray($data, $rawResponse);
        
        return new static(
            objectName: $parent->objectName,
            code: $parent->code,
            message: $parent->message,
            id: $parent->id,
            xactId: $parent->xactId,
            xactDate: $parent->xactDate,
            transactionType: $parent->transactionType,
            posType: $parent->posType,
            agentId: $parent->agentId,
            userId: $parent->userId,
            email: $parent->email,
            phone: $parent->phone,
            instalment: $parent->instalment,
            ratio: $parent->ratio,
            cardNoMasked: $parent->cardNoMasked,
            cardHolder: $parent->cardHolder,
            amount: $parent->amount,
            netAmount: $parent->netAmount,
            comission: $parent->comission,
            comissionTax: $parent->comissionTax,
            currency: $parent->currency,
            bankId: $parent->bankId,
            bankName: $parent->bankName,
            bankAuthorizationCode: $parent->bankAuthorizationCode,
            bankReferenceCode: $parent->bankReferenceCode,
            bankOrderId: $parent->bankOrderId,
            isSucceed: $parent->isSucceed,
            paynetErrorId: $parent->paynetErrorId,
            paynetErrorMessage: $parent->paynetErrorMessage,
            bankErrorId: $parent->bankErrorId,
            bankErrorMessage: $parent->bankErrorMessage,
            bankErrorShortDesc: $parent->bankErrorShortDesc,
            bankErrorLongDesc: $parent->bankErrorLongDesc,
            referenceNo: $parent->referenceNo,
            xactTransactionId: $parent->xactTransactionId,
            campaignUrl: $parent->campaignUrl,
            endUserComission: $parent->endUserComission,
            endUserRatio: $parent->endUserRatio,
            ratioCode: $parent->ratioCode,
            ratioCodeMethod: $parent->ratioCodeMethod,
            cardBrandName: $parent->cardBrandName,
            cardType: $parent->cardType,
            plusInstallment: $parent->plusInstallment,
            companyCostRatio: $parent->companyCostRatio,
            companyCommission: $parent->companyCommission,
            companyCommissionWithTax: $parent->companyCommissionWithTax,
            companyNetAmount: $parent->companyNetAmount,
            isSaveCardSucceed: $parent->isSaveCardSucceed,
            saveCardResultMessage: $parent->saveCardResultMessage,
            cardOwnerId: $parent->cardOwnerId,
            userUniqueId: $parent->userUniqueId,
            cardHash: $parent->cardHash,
            cardBankId: $parent->cardBankId,
            cardLogoUrl: $parent->cardLogoUrl,
            data: $data,
            rawResponse: $rawResponse,
            
            isTds: isset($data['is_tds']) ? (bool) $data['is_tds'] : null,
            mdStatus: isset($data['md_status']) ? (string) $data['md_status'] : null,
        );
    }

    public static function fromJson(object $json): static
    {
        return static::fromArray(static::objectToArray($json));
    }
}
