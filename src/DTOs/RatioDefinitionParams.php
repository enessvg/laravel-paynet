<?php

namespace Paynet\DTOs;

use InvalidArgumentException;
use Paynet\DTOs\Concerns\ValidatesPaynetParams;

class RatioDefinitionParams
{
    use ValidatesPaynetParams;

    public function __construct(
        public string $ratioCode,
        public array $banks,
        public ?string $cardType = null,
    ) {}

    public function toArray(): array
    {
        $this->validate();

        return $this->filterPayload([
            'ratio_code' => $this->ratioCode,
            'card_type' => $this->cardType,
            'banks' => $this->banks,
        ]);
    }

    private function validate(): void
    {
        $this->requireNonEmpty('ratioCode', $this->ratioCode);
        $this->validateCardType($this->cardType);

        if ($this->banks === []) {
            throw new InvalidArgumentException('banks en az bir banka icermelidir.');
        }

        foreach ($this->banks as $bankIndex => $bank) {
            if (!is_array($bank)) {
                throw new InvalidArgumentException("banks.{$bankIndex} array olmalidir.");
            }

            if (!$this->isFilled($bank['bank_id'] ?? null)) {
                throw new InvalidArgumentException("banks.{$bankIndex}.bank_id zorunludur.");
            }

            if (!isset($bank['instalments']) || $bank['instalments'] === null) {
                continue;
            }

            if (!is_array($bank['instalments'])) {
                throw new InvalidArgumentException("banks.{$bankIndex}.instalments array olmalidir.");
            }

            foreach ($bank['instalments'] as $instalmentIndex => $instalment) {
                if (!is_array($instalment)) {
                    throw new InvalidArgumentException("banks.{$bankIndex}.instalments.{$instalmentIndex} array olmalidir.");
                }

                if (!$this->isFilled($instalment['instalment'] ?? null)) {
                    throw new InvalidArgumentException("banks.{$bankIndex}.instalments.{$instalmentIndex}.instalment zorunludur.");
                }
            }
        }
    }
}
