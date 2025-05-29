<?php

namespace App\Console;

use App\Service\Interface\Event\EventServiceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:delete-past-events',
    description: 'Deleting events that have already passed',
)]
class DeletePastEventsCommand extends Command
{
    public function __construct(
        private readonly EventServiceInterface $eventService,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $deleteResult = $this->eventService->deletePastEvents();

        if (!$deleteResult->isSuccess()) {
            $io->error('Failed to delete past events: ' . $deleteResult->getErrorMessage());
            return Command::FAILURE;
        }
        if (count($deleteResult->getDeleted()['events']) === 0) {
            $io->success('No past events to delete');
            return Command::SUCCESS;
        }
        foreach ($deleteResult->getDeleted()['events'] as $deletedEvent) {
            $io->section(sprintf(
                'Deleted event: %s vs %s on %s',
                $deletedEvent['homeTeam'],
                $deletedEvent['awayTeam'],
                $deletedEvent['commenceTime']->format('Y-m-d H:i')
            ));
        }

        $io->success('Past events deleted');

        return Command::SUCCESS;
    }
}
