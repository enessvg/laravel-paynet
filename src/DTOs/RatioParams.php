<?php

namespace Paynet\DTOs;

use Paynet\DTOs\Concerns\ValidatesPaynetParams;

class RatioParams
{
    use ValidatesPaynetParams;

    public function __construct(
        public int $posType = 5,
        public ?string $bin = null,
        public ?string $amount = null,
        public bool $addCommissionToAmount = false,
        public ?string $ratioCode = null,
        public ?string $agentId = null,
        public ?string $cardType = null,
    ) {}

    public function toArray(): array
    {
        $this->validateMinorUnitAmount('amount', $this->amount);
        $this->validateCardType($this->cardType);

        return $this->filterPayload([
            'pos_type' => $this->posType,
            'bin' => $this->bin,
            'amount' => $this->amount,
            'addcomission_to_amount' => $this->addCommissionToAmount,
            'ratio_code' => $this->ratioCode,
            'agent_id' => $this->agentId,
            'card_type' => $this->cardType,
        ]);
    }
}
