<?php

namespace Paynet\DTOs;

class ChargeParams
{
    public function __construct(
        public string $sessionId,
        public string $tokenId,
        public string $amount,
        public ?string $referenceNo = null,
        public int $transactionType = 1,
        public bool $addComissionAmount = false,
        public bool $noInstalment = false,
        public bool $tdsRequired = true,
        public ?string $installments = null,
        public ?string $ratioCode = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'session_id' => $this->sessionId,
            'token_id' => $this->tokenId,
            'amount' => $this->amount,
            'reference_no' => $this->referenceNo,
            'transaction_type' => $this->transactionType,
            'add_comission_amount' => $this->addComissionAmount,
            'no_instalment' => $this->noInstalment,
            'tds_required' => $this->tdsRequired,
            'installments' => $this->installments,
            'ratio_code' => $this->ratioCode,
        ], fn($value) => $value !== null && $value !== '');
    }
}
