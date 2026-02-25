<?php

namespace Paynet\DTOs;

use Paynet\Enums\TransactionType;

class TdsInitialParams
{
    public function __construct(
        public string $amount,
        public string $referenceNo,
        public string $returnUrl,
        public string $domain,
        public ?string $pan = null,
        public ?string $month = null,
        public ?string $year = null,
        public ?string $cvc = null,
        public ?string $cardHolder = null,
        public ?string $agentReferenceNo = null,
        public ?string $agentId = null,
        public ?string $userId = null,
        public int $posType = 5,
        public ?string $companyAmount = null,
        public bool $addCommission = false,
        public ?string $ratioCode = null,
        public int|null $instalment = null,
        public bool $mergeOption = false,
        public ?string $ratioCodeMethod = null,
        public bool $approvedCard = false,
        public bool $dontApplyCampaign = false,
        public bool $isEscrow = false,
        public ?string $iban = null,
        public ?string $agentCustomerName = null,
        public ?string $cardHash = null,
        public ?string $cardHolderPhone = null,
        public ?string $cardHolderMail = null,
        public ?string $description = null,
        public bool $saveCard = false,
        public ?string $cardOwnerId = null,
        public ?string $userUniqueId = null,
        public ?string $userGsmNo = null,
        public ?string $cardDesc = null,
        public ?string $subscriptionId = null,
        public ?string $invoiceNo = null,
        public ?TransactionType $transactionType = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'amount' => $this->amount,
            'reference_no' => $this->referenceNo,
            'return_url' => $this->returnUrl,
            'domain' => $this->domain,
            'pan' => $this->pan,
            'month' => $this->month,
            'year' => $this->year,
            'cvc' => $this->cvc,
            'card_holder' => $this->cardHolder,
            'agent_reference_no' => $this->agentReferenceNo,
            'agent_id' => $this->agentId,
            'user_id' => $this->userId,
            'pos_type' => $this->posType,
            'company_amount' => $this->companyAmount,
            'add_commission' => $this->addCommission,
            'ratio_code' => $this->ratioCode,
            'instalment' => $this->instalment,
            'merge_option' => $this->mergeOption,
            'ratio_code_method' => $this->ratioCodeMethod,
            'approved_card' => $this->approvedCard,
            'dont_apply_campaign' => $this->dontApplyCampaign,
            'is_escrow' => $this->isEscrow,
            'iban' => $this->iban,
            'agent_customer_name' => $this->agentCustomerName,
            'card_hash' => $this->cardHash,
            'card_holder_phone' => $this->cardHolderPhone,
            'card_holder_mail' => $this->cardHolderMail,
            'description' => $this->description,
            'save_card' => $this->saveCard,
            'card_owner_id' => $this->cardOwnerId,
            'user_unique_id' => $this->userUniqueId,
            'user_gsm_no' => $this->userGsmNo,
            'card_desc' => $this->cardDesc,
            'subscription_id' => $this->subscriptionId,
            'invoice_no' => $this->invoiceNo,
            'transaction_type' => $this->transactionType?->value,
        ], fn($value) => $value !== null && $value !== '');
    }
}
