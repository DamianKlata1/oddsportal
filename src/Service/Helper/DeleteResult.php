<?php

namespace App\Service\Helper;

use App\Service\Interface\Helper\DeleteResultInterface;

class DeleteResult implements DeleteResultInterface
{
    public function __construct(
        private readonly string $status,
        private readonly array $deleted = [],
        private readonly ?string $errorMessage = null
    ) {
    }
    public static function success(array $deleted): self
    {
        return new self(self::STATUS_SUCCESS, $deleted);
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

    public function getDeleted(): array
    {
        return $this->deleted;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}
