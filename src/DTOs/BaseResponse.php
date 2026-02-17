<?php

namespace Paynet\DTOs;

use Paynet\Enums\ResultCode;

abstract class BaseResponse
{
    public function __construct(
        public readonly ?string $objectName = null,
        public readonly ResultCode $code = ResultCode::Unsuccessful,
        public readonly ?string $message = null,
    ) {}

    /**
     * İşlem başarılı mı?
     */
    public function isSuccessful(): bool
    {
        return $this->code->isSuccessful();
    }

    /**
     * JSON objesinden response oluşturur
     */
    public static function fromJson(object $json): static
    {
        return new static(
            objectName: $json->object_name ?? null,
            code: ResultCode::fromCode((int) ($json->code ?? 1)),
            message: $json->message ?? null,
        );
    }
}
