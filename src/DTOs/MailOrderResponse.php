<?php

namespace Paynet\DTOs;

use Paynet\Enums\ResultCode;

class MailOrderResponse extends BaseResponse
{
    public function __construct(
        ?string $objectName = null,
        ResultCode $code = ResultCode::Unsuccessful,
        ?string $message = null,
        
        public readonly ?string $url = null,
    ) {
        parent::__construct($objectName, $code, $message);
    }

    public static function fromJson(object $json): static
    {
        return new static(
            objectName: $json->object_name ?? null,
            code: ResultCode::fromCode((int) ($json->code ?? 1)),
            message: $json->message ?? null,
            url: $json->url ?? null,
        );
    }
}
