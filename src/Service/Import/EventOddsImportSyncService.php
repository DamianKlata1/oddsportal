<?php

namespace App\Service\Import;

use App\Entity\Event;
use App\Enum\MarketType;
use App\Enum\SyncStatus;
use App\Entity\BetRegion;
use App\DTO\Sync\SyncStatusDTO;
use App\Entity\EventOddsImportSync;
use App\Exception\ImportFailedException;
use Symfony\Component\HttpFoundation\Response;
use App\ExternalApi\Interface\OddsApi\OddsApiClientInterface;
use App\Factory\Interface\DTO\OddsApiEventDTOFactoryInterface;
use App\Repository\Interface\EventOddsImportSyncRepositoryInterface;
use App\Service\Interface\Import\EventOddsImportSyncServiceInterface;
use App\ExternalApi\Interface\OddsApi\OddsApiOddsDataImporterInterface;

class EventOddsImportSyncService implements EventOddsImportSyncServiceInterface
{
    private const SYNC_THRESHOLD_SECONDS = 300; // 5 minutes

    public function __construct(
        private readonly EventOddsImportSyncRepositoryInterface $eventOddsImportSyncRepository,
        private readonly OddsApiClientInterface $oddsApiClient,
        private readonly OddsApiOddsDataImporterInterface $oddsDataImporter,
        private readonly OddsApiEventDTOFactoryInterface $eventDTOFactory,
    ) {
    }

    public function synchronizeEventOddsData(Event $event, BetRegion $betRegion): SyncStatusDTO
    {
        $syncStatus = $this->checkSyncStatus($event, $betRegion);
        if (!$syncStatus->isSyncRequired()) {
            return $syncStatus;
        }
        $eventsData = $this->oddsApiClient->fetchOddsDataForEvent(
            $event->getLeague()->getApiKey(),
            $event->getApiId(),
            $betRegion->getName(),
            $event->getLeague()->getRegion()->getName() === 'Outrights' ? MarketType::OUTRIGHTS : MarketType::H2H
        );
        $eventDTO = $this->eventDTOFactory->createFromArray($eventsData);
        $importResult = $this->oddsDataImporter->importSingle($eventDTO, $event->getLeague(), $betRegion);
        if (!$importResult->isSuccess()) {
            throw new ImportFailedException(
                message: $importResult->getErrorMessage() ?? 'Unknown import error.',
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        $this->updateSyncStatus($event, $betRegion);
        
        return $syncStatus;
    }
    private function checkSyncStatus(Event $event, BetRegion $betRegion): SyncStatusDTO
    {
        $status = $this->eventOddsImportSyncRepository->findOneBy(['event' => $event, 'betRegion' => $betRegion]);

        if (!$status) {
            return new SyncStatusDTO(SyncStatus::REQUIRED);
        }
        $now = new \DateTimeImmutable();
        $nextUpdateAllowedAt = $status->getLastImportedAt()->modify(sprintf('+%d seconds', self::SYNC_THRESHOLD_SECONDS));

        if ($nextUpdateAllowedAt < $now) {
            return new SyncStatusDTO(SyncStatus::REQUIRED);
        } else {
            return new SyncStatusDTO(SyncStatus::SKIPPED, $nextUpdateAllowedAt);
        }
    }

    private function updateSyncStatus(Event $event, BetRegion $betRegion): void
    {
        $now = new \DateTimeImmutable();
        $status = $this->eventOddsImportSyncRepository->findOneBy(['event' => $event, 'betRegion' => $betRegion]);

        if (!$status) {
            $status = new EventOddsImportSync();
            $status->setEvent($event);
            $status->setBetRegion($betRegion);
        }
        $status->setLastImportedAt($now);

        $this->eventOddsImportSyncRepository->save($status, true);
    }
}
