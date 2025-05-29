<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Factory\Interface\DTO\SportsDataDTOFactoryInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Repository\Interface\RegionRepositoryInterface;
use App\Repository\Interface\SportRepositoryInterface;
use App\Service\Interface\Import\SportsDataImporterInterface;
use App\Service\Interface\League\LeagueServiceInterface;

#[AsCommand(
    name: 'app:synchronise-sports-data',
    description: 'Synchronise sports data from external API',
)]
class SynchroniseSportsDataCommand extends Command
{
    public function __construct(
        private readonly OddsApiClientInterface $oddsApiClient,
        private readonly SportsDataImporterInterface $sportsDataImporter,
        private readonly SportsDataDTOFactoryInterface $sportsDataDTOFactory,
        private readonly SportRepositoryInterface $sportRepository,
        private readonly RegionRepositoryInterface $regionRepository,
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly LeagueServiceInterface $leagueService
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

        $io->info('Fetching data from external API');
        $data = $this->oddsApiClient->fetchSportsData();
        $io->info('Validating and processing data');
        $sportDataDTOs = $this->sportsDataDTOFactory->createFromArrayList($data);

        $importResult = $this->sportsDataImporter->import($sportDataDTOs);
        $deleteResult = $this->leagueService->removeOutdatedLeagues($sportDataDTOs);
        if (!$importResult->isSuccess()) {
            $io->error('Failed to import sports data: ' . $importResult->getErrorMessage());
            return Command::FAILURE;
        }
        if (!$deleteResult->isSuccess()) {
            $io->error('Failed to delete outdated leagues: ' . $deleteResult->getErrorMessage());
            return Command::FAILURE;
        }
        if (
            empty($importResult->getImported()['sports']) &&
            empty($importResult->getImported()['regions']) &&
            empty($importResult->getImported()['leagues']) &&
            empty($deleteResult->getDeleted()['leagues'])
        ) {
            $io->info('No sports data to synchronise.');
            return Command::SUCCESS;
        }
        foreach ($importResult->getImported() as $typeOfData => $data) {
            $io->section(ucfirst($typeOfData) . ' imported:');
            foreach ($data as $item) {
                $io->writeln('- ' . $item);
            }
        }
        foreach ($deleteResult->getDeleted() as $typeOfData => $data) {
            $io->section(ucfirst($typeOfData) . ' deleted:');
            foreach ($data as $item) {
                $io->writeln('- ' . $item['league'] . ' in ' . $item['region'] . ' (' . $item['sport'] . ')');
            }
        }


        $io->success('Sports data successfully synchronised.');
        return Command::SUCCESS;
    }
}
