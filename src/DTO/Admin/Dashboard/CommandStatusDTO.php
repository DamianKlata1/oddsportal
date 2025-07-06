<?php

namespace App\DTO\Admin\Dashboard;

use DateTimeImmutable;

class CommandStatusDTO
{
    public function __construct(
        private readonly string $name,
        private readonly string $status,
        private readonly string $output,
        private readonly DateTimeImmutable $lastRunAt,
    ) {
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getLastRunAt(): DateTimeImmutable
    {
        return $this->lastRunAt;
    }
}
