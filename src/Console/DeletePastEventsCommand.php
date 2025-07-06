<?php

namespace App\Console;

use App\Console\Trait\CommandExecutionTrait;
use App\Service\Interface\CommandLog\CommandLoggerServiceInterface;
use App\Service\Interface\Event\EventServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:delete-past-events',
    description: 'Deleting events that have already passed',
)]
class DeletePastEventsCommand extends Command
{
    use CommandExecutionTrait;
    public function __construct(
        private readonly EventServiceInterface $eventService,
        private readonly CommandLoggerServiceInterface $logger
    ) {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $commandName = $this->getName();

        $this->logger->logStart($commandName);

        try {
            $result = $this->eventService->deletePastEvents();

            if (!$result->isSuccess()) {
                return $this->handleFailure($io, $commandName, $result->getErrorMessage());
            }

            $deletedEvents = $result->getDeleted()['events'] ?? [];

            if (empty($deletedEvents)) {
                return $this->handleSuccess($io, $commandName, 'No past events to delete');
            }

            $this->displayDeletedEvents($io, $deletedEvents);
            return $this->handleSuccess($io, $commandName, 'Past events deleted');

        } catch (\Throwable $e) {
            return $this->handleFailure($io, $commandName, $e->getMessage());
        }
    }

    private function displayDeletedEvents(SymfonyStyle $io, array $events): void
    {
        foreach ($events as $event) {
            $io->section(sprintf(
                'Deleted event: %s vs %s on %s',
                $event['homeTeam'],
                $event['awayTeam'],
                $event['commenceTime']->format('Y-m-d H:i')
            ));
        }
    }
}
