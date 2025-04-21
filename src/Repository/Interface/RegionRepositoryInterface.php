<?php

namespace App\Repository\Interface;

use App\Entity\Region;

interface RegionRepositoryInterface
{
    public function save(Region $region): Region;
}