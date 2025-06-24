<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\Interface\BetRegion\BetRegionServiceInterface;

#[AsCommand(
    name: 'app:load-bet-regions',
    description: 'Add a short description for your command',
)]
class LoadBetRegionsCommand extends Command
{
    public function __construct(
        private readonly BetRegionServiceInterface $betRegionService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
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
            $io->error('Failed to load bet regions: ' . $loadResult->getErrorMessage());
            return Command::FAILURE;
        }
        foreach ($loadResult->getImported() as $typeOfData => $data) {
            $io->section(ucfirst($typeOfData) . ' imported:');
            foreach ($data as $item) {
                $io->writeln('- ' . $item);
            }
        }

        $io->success('Bet regions successfully imported.');
        return Command::SUCCESS;
    }
}
