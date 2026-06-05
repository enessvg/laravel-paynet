<?php

namespace Paynet\DTOs;

use Paynet\DTOs\Concerns\ValidatesPaynetParams;

class SavedCardOtpParams
{
    use ValidatesPaynetParams;

    public function __construct(
        public string $userGsm,
        public string $otpSessionId,
    ) {}

    public function toArray(): array
    {
        $this->requireNonEmpty('userGsm', $this->userGsm);
        $this->requireNonEmpty('otpSessionId', $this->otpSessionId);

        return $this->filterPayload([
            'user_gsm' => $this->userGsm,
            'otp_session_id' => $this->otpSessionId,
        ]);
    }
}
