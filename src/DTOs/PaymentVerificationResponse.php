<?php

namespace Paynet\DTOs;

use Illuminate\Http\Client\Response;
use Paynet\Enums\ResultCode;

class PaymentVerificationResponse extends BaseResponse
{
    public function __construct(
        ?string $objectName = null,
        ResultCode $code = ResultCode::Unsuccessful,
        ?string $message = null,
        array $data = [],
        ?Response $rawResponse = null,
        public readonly ?CheckTransactionResponse $transaction = null,
        public readonly array $mismatches = [],
    ) {
        parent::__construct($objectName, $code, $message, $data, $rawResponse);
    }

    public function successful(): bool
    {
        return $this->apiSuccessful() && $this->transaction?->successful() === true && $this->mismatches === [];
    }

    public static function fromTransaction(CheckTransactionResponse $transaction, array $mismatches = []): self
    {
        return new self(
            objectName: 'payment_verification',
            code: $mismatches === [] ? $transaction->code : ResultCode::Unsuccessful,
            message: $mismatches === [] ? $transaction->message : implode(' ', $mismatches),
            data: [
                'object_name' => 'payment_verification',
                'code' => $mismatches === [] ? $transaction->code->value : ResultCode::Unsuccessful->value,
                'message' => $mismatches === [] ? $transaction->message : implode(' ', $mismatches),
                'mismatches' => $mismatches,
                'transaction' => $transaction->toArray(),
            ],
            rawResponse: $transaction->raw(),
            transaction: $transaction,
            mismatches: $mismatches,
        );
    }
}
