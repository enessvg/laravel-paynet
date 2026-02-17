<?php

namespace Paynet\DTOs;

class CheckTransactionParams
{
    public function __construct(
        public ?string $xactId = null,
        public ?string $referenceNo = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'xact_id' => $this->xactId,
            'reference_no' => $this->referenceNo,
        ], fn($value) => $value !== null && $value !== '');
    }
}
