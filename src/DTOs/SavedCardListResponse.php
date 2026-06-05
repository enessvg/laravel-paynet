<?php

namespace Paynet\DTOs;

use Illuminate\Http\Client\Response;
use Paynet\Enums\ResultCode;

class SavedCardListResponse extends BaseResponse
{
    /**
     * @param array<int, SavedCard> $cards
     */
    public function __construct(
        ?string $objectName = null,
        ResultCode $code = ResultCode::Unsuccessful,
        ?string $message = null,
        public readonly ?int $total = null,
        public readonly ?int $totalCount = null,
        public readonly ?int $limit = null,
        public readonly ?int $endingBefore = null,
        public readonly ?int $startingAfter = null,
        public readonly ?bool $hasMore = null,
        public readonly array $cards = [],
        array $data = [],
        ?Response $rawResponse = null,
    ) {
        parent::__construct($objectName, $code, $message, $data, $rawResponse);
    }

    public static function fromArray(array $data, ?Response $rawResponse = null): static
    {
        $cards = array_map(
            fn (array $card) => SavedCard::fromArray($card),
            array_filter($data['Data'] ?? [], 'is_array'),
        );

        return new static(
            objectName: $data['object_name'] ?? null,
            code: ResultCode::fromCode((int) ($data['result_code'] ?? $data['code'] ?? 1)),
            message: $data['message'] ?? null,
            total: isset($data['total']) ? (int) $data['total'] : null,
            totalCount: isset($data['total_count']) ? (int) $data['total_count'] : null,
            limit: isset($data['limit']) ? (int) $data['limit'] : null,
            endingBefore: isset($data['ending_before']) ? (int) $data['ending_before'] : null,
            startingAfter: isset($data['starting_after']) ? (int) $data['starting_after'] : null,
            hasMore: isset($data['has_more']) ? (bool) $data['has_more'] : null,
            cards: array_values($cards),
            data: $data,
            rawResponse: $rawResponse,
        );
    }
}
