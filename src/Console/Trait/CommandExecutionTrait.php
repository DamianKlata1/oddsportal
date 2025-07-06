<?php
namespace App\Console\Trait;

use App\Service\Interface\CommandLog\CommandLoggerServiceInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Command\Command;

trait CommandExecutionTrait
{
    protected function handleSuccess(SymfonyStyle $io, string $commandName, string $message): int
    {
        $io->success($message);
        $this->logger->logSuccess($commandName, $message);
        return Command::SUCCESS;
    }

    protected function handleFailure(SymfonyStyle $io, string $commandName, string $message): int
    {
        $io->error($message);
        $this->logger->logFailure($commandName, $message);
        return Command::FAILURE;
    }
}
