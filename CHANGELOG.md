# Changelog

Tüm önemli değişiklikler bu dosyada belgelenecektir.

Format [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) standardına uygundur,
ve bu proje [Semantic Versioning](https://semver.org/spec/v2.0.0.html) kullanır.

## [2.0.0] - 2026-02-25

### Değiştirilenler
- `PaynetClient` içindeki istek katmanı Guzzle yerine Laravel HTTP Client (`Http` facade) kullanacak şekilde güncellendi.
- `request()` metodu sadeleştirildi ve yalnızca `POST` isteği atan bir yapıya dönüştürüldü.
- Dahili yanıt işleme/normalizasyon katmanı kaldırıldı; yanıt artık ham haliyle döndürülüyor.
- Facade phpdoc dönüş tipleri yeni davranışa göre güncellendi.

### Kırıcı Değişiklikler
- `PaynetClient` endpoint metodlarının dönüş tipi DTO response sınıfları yerine `\Illuminate\Http\Client\Response` oldu.
- SDK içinde `try/catch` ile exception yutma davranışı kaldırıldı; timeout/bağlantı hataları ve diğer exception'lar çağıran tarafta yönetilmelidir.
- DTO response parse adımları (`fromJson` akışı) `PaynetClient` içinden kaldırıldı; response ayrıştırma artık uygulama katmanının sorumluluğundadır.

### Taşıma Notları
- Entegrasyon kodlarında dönüş nesnesini `Response` olarak ele alıp `status()`, `body()`, `json()` gibi metodlar kullanılmalıdır.
- Hata yönetimi için çağıran tarafta `try/catch` ile özellikle `\Illuminate\Http\Client\ConnectionException` yakalanması önerilir.

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
