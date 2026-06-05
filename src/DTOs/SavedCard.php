<?php

namespace Paynet\DTOs;

class SavedCard
{
    public function __construct(
        public readonly ?string $companyCode = null,
        public readonly ?string $agentId = null,
        public readonly ?string $userUniqueId = null,
        public readonly ?string $cardOwnerId = null,
        public readonly ?string $cardHash = null,
        public readonly ?string $cardDesc = null,
        public readonly ?string $cardHolder = null,
        public readonly ?string $cardNo = null,
        public readonly ?string $cardBin = null,
        public readonly ?string $cardType = null,
        public readonly ?string $cardBankId = null,
        public readonly ?string $cardBankName = null,
        public readonly ?string $cardLogoUrl = null,
        public readonly ?string $cardBrandName = null,
        public readonly ?int $expireMonth = null,
        public readonly ?int $expireYear = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            companyCode: isset($data['company_code']) ? (string) $data['company_code'] : null,
            agentId: isset($data['agent_id']) ? (string) $data['agent_id'] : null,
            userUniqueId: isset($data['user_unique_id']) ? (string) $data['user_unique_id'] : null,
            cardOwnerId: isset($data['card_owner_id']) ? (string) $data['card_owner_id'] : null,
            cardHash: isset($data['card_hash']) ? (string) $data['card_hash'] : null,
            cardDesc: isset($data['card_desc']) ? (string) $data['card_desc'] : null,
            cardHolder: isset($data['card_holder']) ? (string) $data['card_holder'] : null,
            cardNo: isset($data['card_no']) ? (string) $data['card_no'] : null,
            cardBin: isset($data['card_bin']) ? (string) $data['card_bin'] : null,
            cardType: isset($data['card_type']) ? (string) $data['card_type'] : null,
            cardBankId: isset($data['card_bank_id']) ? (string) $data['card_bank_id'] : null,
            cardBankName: isset($data['card_bank_name']) ? (string) $data['card_bank_name'] : null,
            cardLogoUrl: isset($data['card_logo_url']) ? (string) $data['card_logo_url'] : null,
            cardBrandName: isset($data['card_brand_name']) ? (string) $data['card_brand_name'] : null,
            expireMonth: isset($data['expire_month']) ? (int) $data['expire_month'] : null,
            expireYear: isset($data['expire_year']) ? (int) $data['expire_year'] : null,
        );
    }
}
