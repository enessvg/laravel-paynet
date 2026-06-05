<?php

namespace Paynet\DTOs;

use InvalidArgumentException;
use Paynet\DTOs\Concerns\ValidatesPaynetParams;

class SaveCardParams
{
    use ValidatesPaynetParams;

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
        $this->validate();

        return $this->filterPayload([
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
        ]);
    }

    private function validate(): void
    {
        foreach ([
            'cardDesc' => $this->cardDesc,
            'cardHolder' => $this->cardHolder,
            'cardNumber' => $this->cardNumber,
            'expireMonth' => $this->expireMonth,
            'expireYear' => $this->expireYear,
        ] as $field => $value) {
            $this->requireNonEmpty($field, $value);
        }

        if (!$this->isFilled($this->cardOwnerId) && !$this->isFilled($this->userUniqueId)) {
            throw new InvalidArgumentException('cardOwnerId veya userUniqueId zorunludur.');
        }

        $otpFields = [
            'otpCode' => $this->otpCode,
            'otpSessionId' => $this->otpSessionId,
            'userGsm' => $this->userGsm,
        ];

        $hasAnyOtpField = false;
        foreach ($otpFields as $value) {
            if ($this->isFilled($value)) {
                $hasAnyOtpField = true;
                break;
            }
        }

        if ($hasAnyOtpField) {
            foreach ($otpFields as $field => $value) {
                $this->requireNonEmpty($field, $value);
            }
        }
    }
}
