<?php

namespace Paynet\Enums;

/**
 * POS Tipleri
 */
enum PosType: int
{
    case VirtualPos = 5;     // Sanal POS

    public function description(): string
    {
        return match($this) {
            self::VirtualPos => 'Sanal POS',
        };
    }
}
