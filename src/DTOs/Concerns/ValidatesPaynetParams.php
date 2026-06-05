<?php

namespace Paynet\DTOs\Concerns;

use InvalidArgumentException;

trait ValidatesPaynetParams
{
    protected function requireNonEmpty(string $field, mixed $value): void
    {
        if (!$this->isFilled($value)) {
            throw new InvalidArgumentException("{$field} zorunludur.");
        }
    }

    protected function isFilled(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        if (is_array($value)) {
            return $value !== [];
        }

        return true;
    }

    protected function validateCommaDecimalAmount(string $field, ?string $value): void
    {
        if (!$this->isFilled($value)) {
            return;
        }

        if (!preg_match('/^\d+,\d{2}$/', trim((string) $value))) {
            throw new InvalidArgumentException("{$field} 123,45 formatinda olmalidir.");
        }
    }

    protected function validateMinorUnitAmount(string $field, ?string $value): void
    {
        if (!$this->isFilled($value)) {
            return;
        }

        if (!preg_match('/^\d+$/', trim((string) $value))) {
            throw new InvalidArgumentException("{$field} 100 ile carpilmis rakamlardan olusmalidir.");
        }
    }

    protected function validateCardType(?string $value): void
    {
        if (!$this->isFilled($value)) {
            return;
        }

        if (!in_array(trim((string) $value), ['cc', 'bc', 'dc'], true)) {
            throw new InvalidArgumentException('cardType cc, bc veya dc olmalidir.');
        }
    }

    protected function filterPayload(array $payload): array
    {
        return array_filter($payload, fn ($value) => $value !== null && $value !== '');
    }
}
