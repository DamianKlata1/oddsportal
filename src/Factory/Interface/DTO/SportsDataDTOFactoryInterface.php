<?php

namespace App\Factory\Interface\DTO;

use App\DTO\Interface\SportsDataDTOInterface;

interface SportsDataDTOFactoryInterface
{
    public function createFromArray(array $data): SportsDataDTOInterface;
    public function createFromArrayList(array $dataList): array;
}
