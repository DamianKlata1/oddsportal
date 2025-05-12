<?php

namespace App\DTO\Bookmaker;
use Symfony\Component\Validator\Constraints as Assert;

class BookmakerDTO
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Type('string')]
        private readonly string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }


}
