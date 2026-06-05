<?php

namespace Paynet\DTOs;

use Paynet\DTOs\Concerns\ValidatesPaynetParams;

class TransactionIdParams
{
    use ValidatesPaynetParams;

    public function __construct(
        public string $xactId,
    ) {}

    public function toArray(): array
    {
        $this->requireNonEmpty('xactId', $this->xactId);

        return [
            'xact_id' => $this->xactId,
        ];
    }
}
