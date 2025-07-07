<?php

namespace App\Service\CommandLog;

use DateTimeImmutable;
use App\Entity\CommandLog;
use App\Enum\CommandStatus;
use App\Service\Entity\AbstractEntityService;
use App\Repository\Interface\CommandLogRepositoryInterface;
use App\Service\Interface\CommandLog\CommandLoggerServiceInterface;

class CommandLoggerService extends AbstractEntityService implements CommandLoggerServiceInterface
{
    public function __construct(private readonly CommandLogRepositoryInterface $commandLogRepository)
    {
    }

    public function logStart(string $commandName): void
    {
        $this->updateStatus($commandName, CommandStatus::RUNNING);
    }

    public function logSuccess(string $commandName, ?string $output = null): void
    {
        $this->updateStatus($commandName, CommandStatus::SUCCESS, $output);
    }

    public function logFailure(string $commandName, string $errorOutput): void
    {
        $this->updateStatus($commandName, CommandStatus::FAILURE, $errorOutput);
    }

    private function updateStatus(string $commandName, CommandStatus $status, ?string $output = null): void
    {
        $logEntry = $this->commandLogRepository->findOneBy(['commandName' => $commandName]);
        if (!$logEntry) {
            $logEntry = new CommandLog();
            $logEntry->setCommandName($commandName);
        }
        $logEntry->setStatus($status->value);
        $logEntry->setLastRunAt(new DateTimeImmutable());
        if ($output) {
            $logEntry->setOutput($output);
        }
        $this->commandLogRepository->save($logEntry, true);
    }
}
