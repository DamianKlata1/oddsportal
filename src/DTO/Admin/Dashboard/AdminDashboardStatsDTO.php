<?php

namespace App\DTO\Admin\Dashboard;

class AdminDashboardStatsDTO
{
    public function __construct(
        private readonly int $registeredUsers,
        private readonly int $eventsInDatabase,
        private readonly int $activeLeagues,
        private readonly int $newUsersLast7Days
    ) {
    }
    public function getRegisteredUsers(): int
    {
        return $this->registeredUsers;
    }
    public function getEventsInDatabase(): int
    {
        return $this->eventsInDatabase;
    }
    public function getActiveLeagues(): int
    {
        return $this->activeLeagues;
    }
    public function getNewUsersLast7Days(): int
    {
        return $this->newUsersLast7Days;
    }
}
