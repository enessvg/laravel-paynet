<?php

namespace Paynet\Enums;

/**
 * İşlem tipleri
 */
enum TransactionType: int
{
    case Sale = 1;           // Satış
    case Preauth = 2;        // Ön provizyon
    case PostAuth = 3;       // Ön provizyon kapama
    case Refund = 4;         // İade
    case Cancel = 5;         // İptal

    public function description(): string
    {
        return match($this) {
            self::Sale => 'Satış',
            self::Preauth => 'Ön Provizyon',
            self::PostAuth => 'Ön Provizyon Kapama',
            self::Refund => 'İade',
            self::Cancel => 'İptal',
        };
    }
}
