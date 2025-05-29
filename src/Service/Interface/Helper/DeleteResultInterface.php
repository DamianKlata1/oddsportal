<?php
namespace App\Service\Interface\Helper;

interface DeleteResultInterface
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';

    public static function success(array $deleted): self;
    public static function failure(?string $errorMessage = null): self;
    public function isSuccess(): bool;
    public function getStatus(): string;
    public function getDeleted(): array;
    public function getErrorMessage(): ?string;
}
