<?php

namespace Paynet\Support;

use Paynet\Exceptions\PaynetException;

class PaynetTools
{
    /**
     * Tutarı ondalık ayırıcı ile formatlar (123,45)
     * 
     * @throws PaynetException
     */
    public static function formatWithDecimalSeparator(float|int|string $amount): string
    {
        if (!is_numeric($amount)) {
            throw PaynetException::configurationError('Tutar sayısal değer olmalı.');
        }

        return number_format((float) $amount, 2, ',', '');
    }

    /**
     * Tutarı ondalık ayırıcı olmadan formatlar (12345 = 123.45 TL)
     * 
     * @throws PaynetException
     */
    public static function formatWithoutDecimalSeparator(float|int|string $amount): string
    {
        if (!is_numeric($amount)) {
            throw PaynetException::configurationError('Tutar sayısal değer olmalı.');
        }

        return (string) ((int) round((float) $amount * 100));
    }

    /**
     * Kuruş cinsinden tutarı TL'ye çevirir (12345 -> 123.45)
     */
    public static function centsToAmount(int $cents): float
    {
        return $cents / 100;
    }

    /**
     * Tutarı kuruş cinsine çevirir (1 -> 100, 100 -> 10000)
     * Nokta veya virgül varsa dikkate alır, yoksa doğrudan 100 ile çarpar.
     * Sadece rakam döndürür.
     */
    public static function amountToCentsInt(float|int|string $amount): int
    {
        if (is_string($amount) && (str_contains($amount, ',') || str_contains($amount, '.')))
        {
            $amount = str_replace(',', '.', $amount);
            return (int) round((float) $amount * 100);
        }
        
        return (int) round((float) $amount * 100);
    }

    /**
     * Kart numarasını maskeler
     */
    public static function maskCardNumber(string $cardNumber): string
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        $length = strlen($cardNumber);
        
        if ($length < 8) {
            return str_repeat('*', $length);
        }

        return substr($cardNumber, 0, 6) . str_repeat('*', $length - 10) . substr($cardNumber, -4);
    }

    /**
     * Kart BIN numarasını alır (ilk 6 hane)
     */
    public static function getCardBin(string $cardNumber): string
    {
        return substr(preg_replace('/\D/', '', $cardNumber), 0, 6);
    }

    /**
     * Luhn algoritması ile kart numarası doğrulaması
     */
    public static function validateCardNumber(string $cardNumber): bool
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        $length = strlen($cardNumber);

        if ($length < 13 || $length > 19) {
            return false;
        }

        $sum = 0;
        $alt = false;

        for ($i = $length - 1; $i >= 0; $i--) {
            $digit = (int) $cardNumber[$i];

            if ($alt) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
            $alt = !$alt;
        }

        return ($sum % 10) === 0;
    }

    /**
     * Son kullanma tarihi geçerli mi?
     */
    public static function validateExpiryDate(string $month, string $year): bool
    {
        $month = (int) $month;
        $year = (int) $year;

        if ($month < 1 || $month > 12) {
            return false;
        }

        // 2 haneli yılı 4 haneye çevir
        if ($year < 100) {
            $year += 2000;
        }

        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');

        if ($year < $currentYear) {
            return false;
        }

        if ($year === $currentYear && $month < $currentMonth) {
            return false;
        }

        return true;
    }

    /**
     * CVC/CVV geçerli mi?
     */
    public static function validateCvc(string $cvc): bool
    {
        $cvc = preg_replace('/\D/', '', $cvc);
        $length = strlen($cvc);

        return $length >= 3 && $length <= 4;
    }

    /**
     * Benzersiz referans numarası oluşturur
     */
    public static function generateReferenceNo(string $prefix = ''): string
    {
        return $prefix . time() . mt_rand(1000, 9999);
    }
}
