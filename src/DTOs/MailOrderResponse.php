<?php

namespace Paynet\DTOs;

use Illuminate\Http\Client\Response;
use Paynet\Enums\ResultCode;

class MailOrderResponse extends BaseResponse
{
    public function __construct(
        ?string $objectName = null,
        ResultCode $code = ResultCode::Unsuccessful,
        ?string $message = null,
        
        public readonly ?string $url = null,
        array $data = [],
        ?Response $rawResponse = null,
    ) {
        parent::__construct($objectName, $code, $message, $data, $rawResponse);
    }

    public static function fromArray(array $data, ?Response $rawResponse = null): static
    {
        return new static(
            objectName: $data['object_name'] ?? null,
            code: ResultCode::fromCode((int) ($data['result_code'] ?? $data['code'] ?? 1)),
            message: $data['message'] ?? null,
            url: $data['url'] ?? null,
            data: $data,
            rawResponse: $rawResponse,
        );
    }

    public static function fromJson(object $json): static
    {
        return static::fromArray(static::objectToArray($json));
    }
}
