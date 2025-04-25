<?php

namespace App\Service\Interface\Import;

interface ImportResultInterface
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';

    public static function success(array $imported): self;
    public static function failure(?string $errorMessage = null): self;
    public function isSuccess(): bool;
    public function getStatus(): string;
    public function getImported(): array;
    public function getErrorMessage(): ?string;
}
