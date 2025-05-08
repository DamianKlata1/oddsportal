<?php

namespace App\DTO\League;

use Symfony\Component\Validator\Constraints as Assert;

class LeagueDTO
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Type(type: 'integer')]
        private int $id,
        #[Assert\NotBlank()]
        #[Assert\Type(type: 'string')]
        private string $name
    ) {

    }


    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }

}
