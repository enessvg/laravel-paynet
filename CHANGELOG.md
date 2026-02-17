# Changelog

Tüm önemli değişiklikler bu dosyada belgelenecektir.

Format [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) standardına uygundur,
ve bu proje [Semantic Versioning](https://semver.org/spec/v2.0.0.html) kullanır.

## [1.0.0] - 2026-02-17

### Eklenenler
- İlk sürüm
- PHP 8.1+ ve Laravel 10/11 desteği
- `ResultCode` backed enum
- Modern DTO sınıfları (PaymentParams, ChargeParams, vb.)
- Facade desteği (`Paynet::payment()`, `Paynet::tdsInitial()`, vb.)
- 3D Secure (TDS) desteği
- Taksit/Oran sorgulama
- Mail/SMS ile ödeme linki oluşturma
- İşlem sorgulama ve listeleme
- PaynetTools yardımcı fonksiyonları
- Kart doğrulama (Luhn algoritması)
- Unit testler
