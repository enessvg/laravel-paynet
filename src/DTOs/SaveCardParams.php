<?php

namespace Paynet\DTOs;

class SaveCardParams
{
    public function __construct(
        public string $cardDesc,
        public string $cardHolder,
        public string $cardNumber,
        public string $expireMonth,
        public string $expireYear,
        public ?string $cvv = null,
        public ?string $userUniqueId = null,
        public ?string $cardOwnerId = null,
        public ?string $otpCode = null,
        public ?string $otpSessionId = null,
        public ?string $userGsm = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'card_desc' => $this->cardDesc,
            'card_holder' => $this->cardHolder,
            'card_number' => $this->cardNumber,
            'expire_month' => $this->expireMonth,
            'expire_year' => $this->expireYear,
            'cvv' => $this->cvv,
            'user_unique_id' => $this->userUniqueId,
            'card_owner_id' => $this->cardOwnerId,
            'otp_code' => $this->otpCode,
            'otp_session_id' => $this->otpSessionId,
            'user_gsm' => $this->userGsm,
        ], fn($value) => $value !== null && $value !== '');
    }
}
