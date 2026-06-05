<?php

namespace Paynet\DTOs;

use Illuminate\Http\Client\Response;
use Paynet\Enums\ResultCode;

abstract class BaseResponse
{
    public function __construct(
        public readonly ?string $objectName = null,
        public readonly ResultCode $code = ResultCode::Unsuccessful,
        public readonly ?string $message = null,
        protected readonly array $data = [],
        protected readonly ?Response $rawResponse = null,
    ) {}

    /**
     * İşlem başarılı mı?
     */
    public function successful(): bool
    {
        return $this->apiSuccessful();
    }

    public function failed(): bool
    {
        return !$this->successful();
    }

    public function apiSuccessful(): bool
    {
        return $this->code->isSuccessful();
    }

    public function errorMessage(): ?string
    {
        foreach ([
            'bank_error_message',
            'paynet_error_message',
            'message',
            'type',
        ] as $key) {
            $value = $this->get($key);

            if ($value !== null && $value !== '') {
                return (string) $value;
            }
        }

        return $this->message;
    }

    public function raw(): ?Response
    {
        return $this->rawResponse;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return data_get($this->data, $key, $default);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function isSuccessful(): bool
    {
        return $this->successful();
    }

    /**
     * JSON objesinden response oluşturur
     */
    public static function fromJson(object $json): static
    {
        return static::fromArray(static::objectToArray($json));
    }

    public static function fromResponse(Response $response): static
    {
        $json = $response->json();

        if (!is_array($json)) {
            $json = [
                'code' => ResultCode::ServerError->value,
                'message' => 'Gecersiz JSON yaniti',
            ];
        }

        if (!$response->successful()) {
            $json = static::normalizeHttpError($json, $response->status());
        }

        return static::fromArray($json, $response);
    }

    public static function fromThrowable(\Throwable $throwable): static
    {
        return static::fromArray([
            'code' => ResultCode::ServerError->value,
            'message' => $throwable->getMessage(),
        ]);
    }

    public static function fromArray(array $data, ?Response $rawResponse = null): static
    {
        return new static(
            objectName: isset($data['object_name']) ? (string) $data['object_name'] : null,
            code: ResultCode::fromCode((int) ($data['result_code'] ?? $data['code'] ?? 1)),
            message: isset($data['message']) ? (string) $data['message'] : null,
            data: $data,
            rawResponse: $rawResponse,
        );
    }

    protected static function normalizeHttpError(array $data, int $status): array
    {
        if (isset($data['result_code'])) {
            return $data;
        }

        $data['result_code'] = match ($status) {
            401, 403 => ResultCode::Unauthorized->value,
            400 => ResultCode::BadRequest->value,
            default => ResultCode::ServerError->value,
        };

        return $data;
    }

    protected static function objectToArray(object $json): array
    {
        return json_decode(json_encode($json, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
    }
}
