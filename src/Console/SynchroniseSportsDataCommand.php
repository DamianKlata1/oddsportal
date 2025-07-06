<?php

namespace App\Console;

use App\Console\Trait\CommandExecutionTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\Interface\SportRepositoryInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Repository\Interface\RegionRepositoryInterface;
use App\Service\Interface\League\LeagueServiceInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use App\Factory\Interface\DTO\OddsApiSportsDataDTOFactoryInterface;
use App\Service\Interface\CommandLog\CommandLoggerServiceInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiSportsDataImporterInterface;

#[AsCommand(
    name: 'app:synchronise-sports-data',
    description: 'Synchronise sports data from external API',
)]
class SynchroniseSportsDataCommand extends Command
{
    use CommandExecutionTrait;
    public function __construct(
        private readonly OddsApiClientInterface $oddsApiClient,
        private readonly OddsApiSportsDataImporterInterface $sportsDataImporter,
        private readonly OddsApiSportsDataDTOFactoryInterface $sportsDataDTOFactory,
        private readonly SportRepositoryInterface $sportRepository,
        private readonly RegionRepositoryInterface $regionRepository,
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly LeagueServiceInterface $leagueService,
        private readonly CommandLoggerServiceInterface $logger

    ) {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $commandName = $this->getName();
        $this->logger->logStart($commandName);

        $io->title('Importing sports data');

        $io->info('Fetching data from external API');
        $data = $this->oddsApiClient->fetchSportsData();

        $io->info('Validating and processing data');
        $sportDataDTOs = $this->sportsDataDTOFactory->createFromArrayList($data);

        $importResult = $this->sportsDataImporter->import($sportDataDTOs);
        $deleteResult = $this->leagueService->removeOutdatedLeagues($sportDataDTOs);
        if (!$importResult->isSuccess()) {
            return $this->handleFailure($io, $commandName, 'Failed to import sports data: ' . $importResult->getErrorMessage());
        }
        if (!$deleteResult->isSuccess()) {
            return $this->handleFailure($io, $commandName, 'Failed to delete outdated leagues: ' . $deleteResult->getErrorMessage());
        }
        if (
            empty($importResult->getImported()['sports']) &&
            empty($importResult->getImported()['regions']) &&
            empty($importResult->getImported()['leagues']) &&
            empty($deleteResult->getDeleted()['leagues'])
        ) {
            $this->handleSuccess($io, $commandName, 'No new sports data to import or delete.');
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


        return $this->handleSuccess($io, $commandName, 'Sports data successfully synchronised.');
    }
}
