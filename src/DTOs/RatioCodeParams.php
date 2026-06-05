<?php

namespace Paynet\DTOs;

use Paynet\DTOs\Concerns\ValidatesPaynetParams;

class RatioCodeParams
{
    use ValidatesPaynetParams;

    public function __construct(
        public string $ratioCode,
    ) {}

    public function toArray(): array
    {
        $this->requireNonEmpty('ratioCode', $this->ratioCode);

        return [
            'ratio_code' => $this->ratioCode,
        ];
    }
}
