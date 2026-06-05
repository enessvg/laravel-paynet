<?php

namespace Paynet\DTOs;

use Paynet\DTOs\Concerns\ValidatesPaynetParams;

class SavedCardOtpCheckParams
{
    use ValidatesPaynetParams;

    public function __construct(
        public string $userGsm,
        public string $otpSessionId,
        public string $otpCode,
    ) {}

    public function toArray(): array
    {
        $this->requireNonEmpty('userGsm', $this->userGsm);
        $this->requireNonEmpty('otpSessionId', $this->otpSessionId);
        $this->requireNonEmpty('otpCode', $this->otpCode);

        return $this->filterPayload([
            'user_gsm' => $this->userGsm,
            'otp_session_id' => $this->otpSessionId,
            'otp_code' => $this->otpCode,
        ]);
    }
}
