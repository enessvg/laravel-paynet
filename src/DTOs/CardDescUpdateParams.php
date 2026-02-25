<?php

namespace Paynet\DTOs;

class CardDescUpdateParams
{
    public function __construct(
        public string $cardOwnerId,
        public string $cardHash,
        public string $cardDesc,
    ) {}

    public function toArray(): array
    {
        return [
            'card_owner_id' => $this->cardOwnerId,
            'card_hash' => $this->cardHash,
            'card_desc' => $this->cardDesc,
        ];
    }
}
