<?php

namespace Paynet\DTOs;

use Paynet\Enums\ResultCode;

class TdsInitialResponse extends BaseResponse
{
    public function __construct(
        ?string $objectName = null,
        ResultCode $code = ResultCode::Unsuccessful,
        ?string $message = null,
        
        public readonly ?string $tokenId = null,
        public readonly ?string $sessionId = null,
        public readonly ?string $postUrl = null,
        public readonly ?string $htmlContent = null,
    ) {
        parent::__construct($objectName, $code, $message);
    }

    public static function fromJson(object $json): static
    {
        return new static(
            objectName: $json->object_name ?? null,
            code: ResultCode::fromCode((int) ($json->code ?? 1)),
            message: $json->message ?? null,
            
            tokenId: $json->token_id ?? null,
            sessionId: $json->session_id ?? null,
            postUrl: $json->post_url ?? null,
            htmlContent: $json->html_content ?? null,
        );
    }

    /**
     * 3D Secure yönlendirme URL'ini döndürür
     */
    public function getRedirectUrl(): ?string
    {
        return $this->postUrl;
    }
}
