<?php

namespace App\DTO\Sport;
use Symfony\Component\Validator\Constraints as Assert;

class SportDTO
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Type(type: 'integer')]
        private int $id,
        #[Assert\NotBlank()]
        #[Assert\Type(type: 'string')]
        private string $name,
        #[Assert\NotBlank()]
        #[Assert\Type(type: 'string')]
        #[Assert\Url()]
        private string $logoPath,
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

    public function getLogoPath(): string
    {
        return $this->logoPath;
    }
}
