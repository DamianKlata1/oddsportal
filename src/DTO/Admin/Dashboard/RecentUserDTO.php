<?php

namespace App\DTO\Admin\Dashboard;

use DateTimeImmutable;

class RecentUserDTO
{
    public function __construct(
        private readonly string $email,
        private readonly DateTimeImmutable $time
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getTime(): DateTimeImmutable
    {
        return $this->time;
    }
}
