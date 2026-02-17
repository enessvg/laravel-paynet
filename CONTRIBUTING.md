# Contribution Guide

Bu projeye katkıda bulunmak istiyorsanız:

## Pull Request Süreci

1. Projeyi fork edin
2. Feature branch oluşturun (`git checkout -b feature/yeni-ozellik`)
3. Değişikliklerinizi commit edin (`git commit -m 'Yeni özellik eklendi'`)
4. Branch'inizi push edin (`git push origin feature/yeni-ozellik`)
5. Pull Request açın

## Kod Standartları

- PSR-12 kod stilini takip edin
- Tüm public metodlar için PHPDoc yazın
- Yeni özellikler için test yazın
- CHANGELOG.md'yi güncelleyin

## Test

```bash
composer test
```

## Güvenlik Açıkları

Güvenlik açığı bulursanız, lütfen public issue açmak yerine doğrudan enessvg4@gmail.com adresine mail atın.
