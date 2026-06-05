<?php

namespace Paynet\DTOs;

use Paynet\DTOs\Concerns\ValidatesPaynetParams;

class RatioTypeParams
{
    use ValidatesPaynetParams;

    public function __construct(
        public string $description,
        public ?string $ratioCode = null,
        public bool $isDefault = false,
        public bool $isCumulative = true,
    ) {}

    public function toArray(): array
    {
        $this->requireNonEmpty('description', $this->description);

        return $this->filterPayload([
            'ratio_code' => $this->ratioCode,
            'description' => $this->description,
            'is_default' => $this->isDefault,
            'is_cumulative' => $this->isCumulative,
        ]);
    }
}
