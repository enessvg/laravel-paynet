<?php

namespace Paynet\DTOs;

use Paynet\DTOs\Concerns\ValidatesPaynetParams;

class DeleteCardParams
{
    use ValidatesPaynetParams;

    public function __construct(
        public string $cardOwnerId,
        public string $cardHash,
    ) {}

    public function toArray(): array
    {
        $this->requireNonEmpty('cardOwnerId', $this->cardOwnerId);
        $this->requireNonEmpty('cardHash', $this->cardHash);

        return [
            'card_owner_id' => $this->cardOwnerId,
            'card_hash' => $this->cardHash,
        ];
    }
}
