<?php

namespace Paynet\DTOs;

class DeleteCardParams
{
    public function __construct(
        public string $cardOwnerId,
        public string $cardHash,
    ) {}

    public function toArray(): array
    {
        return [
            'card_owner_id' => $this->cardOwnerId,
            'card_hash' => $this->cardHash,
        ];
    }
}
