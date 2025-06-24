<?php

namespace App\Service\Import;

use App\Entity\League;
use App\Enum\MarketType;
use App\Entity\BetRegion;
use App\Entity\LeagueOddsImportSync;
use App\Exception\ImportFailedException;
use Symfony\Component\HttpFoundation\Response;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use App\Repository\Interface\LeagueOddsImportSyncRepositoryInterface;
use App\Service\Interface\Import\LeagueOddsImportSyncServiceInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiOddsDataImporterInterface;
use App\Factory\Interface\DTO\OddsApiEventDTOFactoryInterface;

class LeagueOddsImportSyncService implements LeagueOddsImportSyncServiceInterface
{
    private const SYNC_THRESHOLD_SECONDS = 3600;
    public function __construct(
        private readonly LeagueOddsImportSyncRepositoryInterface $oddsDataImportSyncRepository,
        private readonly OddsApiClientInterface $oddsApiClient,
        private readonly OddsApiOddsDataImporterInterface $oddsDataImporter,
        private readonly OddsApiEventDTOFactoryInterface $eventDtoFactory
    ) {
    }
    public function synchronizeLeagueOddsData(?League $league, BetRegion $betRegion): void
    {
        if (!$league) {
            //TODO : Handle case where league is not provided
            return;
        }
        if ($this->isSyncRequired($league, $betRegion)) {
            $eventsData = $this->oddsApiClient->fetchOddsDataForLeague(
                $league->getApiKey(),
                $betRegion->getName(),
                $league->getRegion()->getName() === 'Outrights' ? MarketType::OUTRIGHTS : MarketType::H2H
            );
            $eventDTOs = $this->eventDtoFactory->createFromArrayList($eventsData);
            $importResult = $this->oddsDataImporter->importFromList($eventDTOs, $league, $betRegion);
            if (!$importResult->isSuccess()) {
                throw new ImportFailedException(
                    message: $importResult->getErrorMessage() ?? 'Unknown import error.',
                    code: Response::HTTP_INTERNAL_SERVER_ERROR,
                );
            }
            $this->updateSyncStatus($league, $betRegion);
        }
    }
    private function isSyncRequired(League $league, BetRegion $betRegion): bool
    {

        $status = $this->oddsDataImportSyncRepository->findOneBy(['league' => $league, 'betRegion' => $betRegion]);
        if (!$status) {
            return true; // no status =  first import
        }
        $now = new \DateTimeImmutable();
        return $status->getLastImportedAt()->modify(sprintf('+%d seconds', self::SYNC_THRESHOLD_SECONDS)) < $now;
    }
    private function updateSyncStatus(League $league, BetRegion $betRegion): void
    {
        $now = new \DateTimeImmutable();
        $status = $this->oddsDataImportSyncRepository->findOneBy(['league' => $league, 'betRegion' => $betRegion]);

        if (!$status) {
            $status = new LeagueOddsImportSync();
            $status->setLeague($league);
            $status->setBetRegion($betRegion);
        }
        $status->setLastImportedAt($now);

        $this->oddsDataImportSyncRepository->save($status, true);
    }

}
