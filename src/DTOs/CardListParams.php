<?php

namespace Paynet\DTOs;

class CardListParams
{
    public function __construct(
        public string $cardOwnerId,
        public ?int $limit = null,
        public ?int $endingBefore = null,
        public ?int $startingAfter = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'card_owner_id' => $this->cardOwnerId,
            'limit' => $this->limit,
            'ending_before' => $this->endingBefore,
            'starting_after' => $this->startingAfter,
        ], fn($value) => $value !== null && $value !== '');
    }
}
