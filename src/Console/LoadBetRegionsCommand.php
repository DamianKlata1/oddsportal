<?php

namespace App\Console;

use App\Console\Trait\CommandExecutionTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\Interface\BetRegion\BetRegionServiceInterface;
use App\Service\Interface\CommandLog\CommandLoggerServiceInterface;

#[AsCommand(
    name: 'app:load-bet-regions',
    description: 'Add a short description for your command',
)]
class LoadBetRegionsCommand extends Command
{
    use CommandExecutionTrait;
    public function __construct(
        private readonly BetRegionServiceInterface $betRegionService,
        private readonly CommandLoggerServiceInterface $logger

    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $commandName = $this->getName();

        $this->logger->logStart($commandName);
        $io->title('Importing bet regions');

        $data = [
            'us',
            'us2',
            'uk',
            'au',
            'eu',
        ];
        $loadResult = $this->betRegionService->loadBetRegions($data);
        if (!$loadResult->isSuccess()) {
            return $this->handleFailure($io, $commandName, 'Failed to load bet regions: ' . $loadResult->getErrorMessage());
        }
        foreach ($loadResult->getImported() as $typeOfData => $data) {
            $io->section(ucfirst($typeOfData) . ' imported:');
            foreach ($data as $item) {
                $io->writeln('- ' . $item);
            }
        }
        return $this->handleSuccess($io, $commandName, 'Bet regions successfully imported.');
    }
}
