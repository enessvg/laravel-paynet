<?php

namespace Paynet\Enums;

/**
 * Paynet API sonuç kodları
 */
enum ResultCode: int
{
    case Successful = 0;
    case Unsuccessful = 1;
    case CompanyBlocked = 2;
    case AgentBlocked = 3;
    case AgentNotFound = 4;
    case DuplicateData = 5;
    case NoProcess = 6;
    case Unauthorized = 7;
    case ServerError = 8;
    case NotImplemented = 9;
    case TimeOut = 10;
    case BadRequest = 11;
    case NoData = 12;
    case PaynetjNoSession = 13;
    case PaynetjWrongBin = 14;
    case PaynetjUnmatchTran = 15;
    case Paynetj3dError = 16;
    case PaynetjUsedSession = 17;
    case WrongCardData = 18;
    case WrongTransactionType = 19;
    case WrongPosType = 20;
    case WrongRatioGet = 21;
    case PaynetjExpireDateError = 22;
    case RatioCodeNotFound = 23;
    case InvoiceNoNotFound = 24;
    case CardNotFound = 25;
    case CardKeyUndefined = 26;
    case OldSuccessful = 100;
    case SubscriptionOn = 200;
    case SubscriptionOff = 201;

    /**
     * Sonuç kodunun başarılı olup olmadığını kontrol eder
     */
    public function isSuccessful(): bool
    {
        return $this === self::Successful || $this === self::OldSuccessful;
    }

    /**
     * Sonuç kodunun açıklamasını döndürür
     */
    public function description(): string
    {
        return match($this) {
            self::Successful => 'İşlem başarılı',
            self::Unsuccessful => 'İşlem başarısız',
            self::CompanyBlocked => 'Firma bloke edilmiş',
            self::AgentBlocked => 'Bayi bloke edilmiş',
            self::AgentNotFound => 'Bayi bulunamadı',
            self::DuplicateData => 'Mükerrer veri',
            self::NoProcess => 'İşlem yok',
            self::Unauthorized => 'Yetkisiz erişim',
            self::ServerError => 'Sunucu hatası',
            self::NotImplemented => 'Henüz uygulanmadı',
            self::TimeOut => 'Zaman aşımı',
            self::BadRequest => 'Hatalı istek',
            self::NoData => 'Veri bulunamadı',
            self::PaynetjNoSession => 'Paynet oturum bulunamadı',
            self::PaynetjWrongBin => 'Geçersiz kart BIN',
            self::PaynetjUnmatchTran => 'İşlem eşleşmedi',
            self::Paynetj3dError => '3D Secure hatası',
            self::PaynetjUsedSession => 'Oturum zaten kullanılmış',
            self::WrongCardData => 'Hatalı kart bilgileri',
            self::WrongTransactionType => 'Geçersiz işlem tipi',
            self::WrongPosType => 'Geçersiz POS tipi',
            self::WrongRatioGet => 'Oran alınamadı',
            self::PaynetjExpireDateError => 'Geçersiz son kullanma tarihi',
            self::RatioCodeNotFound => 'Oran kodu bulunamadı',
            self::InvoiceNoNotFound => 'Fatura numarası bulunamadı',
            self::CardNotFound => 'Kart bulunamadı',
            self::CardKeyUndefined => 'Kart anahtarı tanımsız',
            self::OldSuccessful => 'Önceki işlem başarılı',
            self::SubscriptionOn => 'Abonelik aktif',
            self::SubscriptionOff => 'Abonelik pasif',
        };
    }

    /**
     * Integer değerden enum oluşturur
     */
    public static function fromCode(int $code): self
    {
        return self::tryFrom($code) ?? self::Unsuccessful;
    }
}
