<?php

namespace App\ExternalApi\OddsApi\Helper;

use App\Service\Interface\Import\ImportResultInterface;

class ImportResult implements ImportResultInterface
{
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
