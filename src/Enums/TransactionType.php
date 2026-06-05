<?php

namespace Paynet\Enums;

/**
 * İşlem tipleri
 */
enum TransactionType: int
{
    case Sale = 1;           // Satış
    case PreAuth = 3;        // Ön provizyon
    case Refund = 4;         // İade
    case Cancel = 5;         // İptal

    public function description(): string
    {
        return match($this) {
            self::Sale => 'Satış',
            self::PreAuth => 'Ön Provizyon',
            self::Refund => 'İade',
            self::Cancel => 'İptal',
        };
    }
}
