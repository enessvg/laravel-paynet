<?php

namespace Paynet\DTOs;

use Paynet\DTOs\Concerns\ValidatesPaynetParams;

class CardDescUpdateParams
{
    use ValidatesPaynetParams;

    public function __construct(
        public string $cardOwnerId,
        public string $cardHash,
        public string $cardDesc,
    ) {}

    public function toArray(): array
    {
        $this->requireNonEmpty('cardOwnerId', $this->cardOwnerId);
        $this->requireNonEmpty('cardHash', $this->cardHash);
        $this->requireNonEmpty('cardDesc', $this->cardDesc);

        return [
            'card_owner_id' => $this->cardOwnerId,
            'card_hash' => $this->cardHash,
            'card_desc' => $this->cardDesc,
        ];
    }
}
