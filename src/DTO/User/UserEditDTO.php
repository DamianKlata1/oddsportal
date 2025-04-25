<?php

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserEditDTO {

    public function __construct(
            #[Assert\Length(min: 6, max: 4096)]
            #[Assert\NotBlank()]
            private readonly string $current_password,
            #[Assert\Email()]
            private readonly ?string $email = null,
            #[Assert\Length(min: 6, max: 4096)]
            private readonly ?string $password = null
    ) {
        
    }
    public function getCurrentPassword(): string
    {
        return $this->current_password;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
