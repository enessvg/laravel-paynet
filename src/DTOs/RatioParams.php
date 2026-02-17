<?php

namespace Paynet\DTOs;

class RatioParams
{
    public function __construct(
        public int $posType = 5,
        public ?string $bin = null,
        public ?string $amount = null,
        public bool $addCommissionToAmount = false,
        public ?string $ratioCode = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'pos_type' => $this->posType,
            'bin' => $this->bin,
            'amount' => $this->amount,
            'addcommission_to_amount' => $this->addCommissionToAmount,
            'ratio_code' => $this->ratioCode,
        ], fn($value) => $value !== null && $value !== '');
    }
}
