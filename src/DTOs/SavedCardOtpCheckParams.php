<?php

namespace Paynet\DTOs;

class SavedCardOtpCheckParams
{
    public function __construct(
        public string $userGsm,
        public string $otpSessionId,
        public string $otpCode,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'user_gsm' => $this->userGsm,
            'otp_session_id' => $this->otpSessionId,
            'otp_code' => $this->otpCode,
        ], fn($value) => $value !== null && $value !== '');
    }
}
