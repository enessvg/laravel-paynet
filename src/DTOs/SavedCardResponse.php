<?php

namespace Paynet\DTOs;

use Illuminate\Http\Client\Response;
use Paynet\Enums\ResultCode;

class SavedCardResponse extends BaseResponse
{
    public function __construct(
        ?string $objectName = null,
        ResultCode $code = ResultCode::Unsuccessful,
        ?string $message = null,
        public readonly ?string $cardOwnerId = null,
        public readonly ?string $userUniqueId = null,
        public readonly ?string $cardDesc = null,
        public readonly ?string $cardHash = null,
        public readonly ?string $cardNo = null,
        public readonly ?string $cardBin = null,
        public readonly ?string $cardType = null,
        public readonly ?string $cardBankName = null,
        public readonly ?string $cardLogoUrl = null,
        public readonly ?string $cardBrandName = null,
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
            cardOwnerId: isset($data['card_owner_id']) ? (string) $data['card_owner_id'] : null,
            userUniqueId: isset($data['user_unique_id']) ? (string) $data['user_unique_id'] : null,
            cardDesc: isset($data['card_desc']) ? (string) $data['card_desc'] : null,
            cardHash: isset($data['card_hash']) ? (string) $data['card_hash'] : null,
            cardNo: isset($data['card_no']) ? (string) $data['card_no'] : null,
            cardBin: isset($data['card_bin']) ? (string) $data['card_bin'] : null,
            cardType: isset($data['card_type']) ? (string) $data['card_type'] : null,
            cardBankName: isset($data['card_bank_name']) ? (string) $data['card_bank_name'] : null,
            cardLogoUrl: isset($data['card_logo_url']) ? (string) $data['card_logo_url'] : null,
            cardBrandName: isset($data['card_brand_name']) ? (string) $data['card_brand_name'] : null,
            data: $data,
            rawResponse: $rawResponse,
        );
    }
}
