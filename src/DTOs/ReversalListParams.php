<?php

namespace Paynet\DTOs;

class ReversalListParams
{
    public function __construct(
        public string $datab,
        public string $datbi,
        public ?string $agentId = null,
        public ?string $bankId = null,
        public ?int $limit = null,
        public ?int $endingBefore = null,
        public ?int $startingAfter = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'agent_id' => $this->agentId,
            'bank_id' => $this->bankId,
            'datab' => $this->datab,
            'datbi' => $this->datbi,
            'limit' => $this->limit,
            'ending_before' => $this->endingBefore,
            'starting_after' => $this->startingAfter,
        ], fn ($value) => $value !== null && $value !== '');
    }
}
