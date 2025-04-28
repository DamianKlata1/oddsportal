<?php

namespace App\Factory\Interface\DTO;

use App\DTO\Interface\SportsDataDTOInterface;

interface SportsDataDTOFactoryInterface
{
    public function create(
        string $key,
        string $group,
        string $title,
        string $description,
        bool $active,
        bool $hasOutrights
    ): SportsDataDTOInterface;
    public function createFromArray(array $data): SportsDataDTOInterface;
    public function createFromArrayList(array $dataList): array;
}
