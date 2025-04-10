<?php

namespace App\DTO;

use App\Validator\UniqueUserEmail;
use Symfony\Component\Validator\Constraints as Assert;

class NewUserDTO {

    public function __construct(
            #[Assert\Email()]
            #[Assert\NotBlank()]
            private readonly string $email,
            #[Assert\NotBlank()]
            #[Assert\Length(min: 6, max: 4096)]
            private readonly string $password
    ) {
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
}
