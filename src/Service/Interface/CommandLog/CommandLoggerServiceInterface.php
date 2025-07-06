<?php

namespace App\Service\Interface\CommandLog;

interface CommandLoggerServiceInterface
{
    public function logStart(string $commandName): void;
    public function logSuccess(string $commandName, ?string $output = null): void;
    public function logFailure(string $commandName, string $errorOutput): void;
}
