<?php

namespace App\DTO\ExternalApi\OddsApi;

use Symfony\Component\Validator\Constraints as Assert;
use App\DTO\Interface\ExternalApi\OddsApiSportsDataDTOInterface;


class OddsApiSportsDataDTO implements OddsApiSportsDataDTOInterface
{
    public function __construct(
        #[Assert\NotBlank()]
        private readonly string $key,
        #[Assert\NotBlank()]
        private readonly string $group,
        #[Assert\NotBlank()]
        private readonly string $title,
        #[Assert\NotBlank()]
        private readonly string $description,
        #[Assert\Type('bool')]
        #[Assert\IsTrue()]
        private readonly bool $active,
        #[Assert\Type('bool')]
        private readonly bool $hasOutrights
    ) {}

    public function getKey(): string
    {
        return $this->key;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function hasOutrights(): bool
    {
        return $this->hasOutrights;
    }
}
