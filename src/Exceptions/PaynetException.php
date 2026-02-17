<?php

namespace Paynet\Exceptions;

use Exception;
use Paynet\Enums\ResultCode;

class PaynetException extends Exception
{
    public function __construct(
        string $message = '',
        public readonly ?ResultCode $resultCode = null,
        public readonly ?string $bankErrorCode = null,
        public readonly ?string $bankErrorMessage = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * API yanıtından exception oluşturur
     */
    public static function fromResponse(object $response): self
    {
        $resultCode = isset($response->code) 
            ? ResultCode::fromCode((int) $response->code) 
            : ResultCode::Unsuccessful;

        return new self(
            message: $response->message ?? 'Bilinmeyen hata',
            resultCode: $resultCode,
            bankErrorCode: $response->bank_error_id ?? null,
            bankErrorMessage: $response->bank_error_message ?? null,
            code: (int) ($response->code ?? 1)
        );
    }

    /**
     * Bağlantı hatası için exception
     */
    public static function connectionError(string $url, ?string $reason = null): self
    {
        $message = "{$url} adresine bağlanılamadı";
        if ($reason) {
            $message .= ": {$reason}";
        }

        return new self(
            message: $message,
            resultCode: ResultCode::ServerError
        );
    }

    /**
     * Yapılandırma hatası için exception
     */
    public static function configurationError(string $message): self
    {
        return new self(
            message: $message,
            resultCode: ResultCode::BadRequest
        );
    }
}
