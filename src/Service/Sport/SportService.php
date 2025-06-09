<?php

namespace App\Service\Sport;

use App\Service\Entity\AbstractEntityService;
use App\Repository\Interface\SportRepositoryInterface;
use App\Service\Interface\Sport\SportServiceInterface;

class SportService extends AbstractEntityService implements SportServiceInterface
{
    public function __construct(
        private readonly SportRepositoryInterface $sportRepository
    ) {
        parent::__construct($sportRepository);
    }

}
