<?php

namespace Paynet\DTOs;

class SavedCardOtpParams
{
    public function __construct(
        public string $userGsm,
        public string $otpSessionId,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'user_gsm' => $this->userGsm,
            'otp_session_id' => $this->otpSessionId,
        ], fn($value) => $value !== null && $value !== '');
    }
}
