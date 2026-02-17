<?php

namespace Paynet\DTOs;

use Paynet\Enums\TransactionType;

class TdsChargeParams
{
    public function __construct(
        public string $sessionId,
        public string $tokenId,
        public TransactionType $transactionType = TransactionType::Sale,
    ) {}

    public function toArray(): array
    {
        return [
            'session_id' => $this->sessionId,
            'token_id' => $this->tokenId,
            'transaction_type' => $this->transactionType->value,
        ];
    }
}
