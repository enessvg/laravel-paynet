<?php

namespace Paynet\DTOs;

class ReversedRequestParams
{
    public function __construct(
        public string $xactId,
        public ?string $amount = null,
        public ?string $succeedUrl = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'xact_id' => $this->xactId,
            'amount' => $this->amount,
            'succeedUrl' => $this->succeedUrl,
        ], fn($value) => $value !== null && $value !== '');
    }
}
