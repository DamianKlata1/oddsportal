<?php

namespace App\Console;

use App\ExternalApi\OddsApi\Interface\OddsApiClientInterface;
use App\Service\Interface\SportsDataImporterInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-sports-data',
    description: 'Import sports data from external API',
)]
class ImportSportsDataCommand extends Command
{
    public function __construct(
        private readonly SportsDataImporterInterface $sportsDataImporter,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Importing sports data');
        $io->section('Fetching data from external API');

        $importResult = $this->sportsDataImporter->import();
        if (!$importResult->isSuccess()) {
            $io->error('Failed to import sports data: ' . $importResult->getErrorMessage());
            return Command::FAILURE;
        }
        if (empty($importResult->getImported())) {
            $io->warning('No sports data imported.');
            return Command::SUCCESS;
        }
        foreach ($importResult->getImported() as $typeOfData => $data) {
            $io->section(ucfirst($typeOfData) . ' imported:');
            foreach ($data as $item) {
                $io->writeln('- ' . $item);
            }
        }
        $io->success('Sports data successfully imported.');

        return Command::SUCCESS;
    }
}
