<?php

namespace App\DTO\Interface\ExternalApi;

use App\DTO\Interface\SportsDataDTOInterface;

interface OddsApiSportsDataDTOInterface extends SportsDataDTOInterface
{
    public function getKey(): string;
    public function getGroup(): string;
    public function getTitle(): string;
    public function getDescription(): string;
    public function isActive(): bool;
    public function hasOutrights(): bool;
}
