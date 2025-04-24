<?php

namespace App\ExternalApi\OddsApi\Helper;

class ImportResult
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';


    public function __construct(
        private readonly string $status,
        private readonly array $imported = [],
        private readonly ?string $errorMessage = null
    ) {
    }
    public static function success(array $imported): self
    {
        return new self(self::STATUS_SUCCESS, $imported);
    }

    public static function failure(?string $errorMessage = null): self
    {
        return new self(self::STATUS_FAILED, [], $errorMessage);
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getImported(): array
    {
        return $this->imported;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}
