<?php

namespace Paynet\DTOs;

use Paynet\DTOs\Concerns\ValidatesPaynetParams;

class CardListParams
{
    use ValidatesPaynetParams;

    public function __construct(
        public string $cardOwnerId,
        public ?int $limit = null,
        public ?int $endingBefore = null,
        public ?int $startingAfter = null,
    ) {}

    public function toArray(): array
    {
        $this->requireNonEmpty('cardOwnerId', $this->cardOwnerId);

        return $this->filterPayload([
            'card_owner_id' => $this->cardOwnerId,
            'limit' => $this->limit,
            'ending_before' => $this->endingBefore,
            'starting_after' => $this->startingAfter,
        ]);
    }
}
