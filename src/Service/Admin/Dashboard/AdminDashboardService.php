<?php

namespace App\Service\Admin\Dashboard;

use App\DTO\Admin\Dashboard\RecentUserDTO;
use App\DTO\Admin\Dashboard\CommandStatusDTO;
use App\DTO\Admin\Dashboard\AdminDashboardStatsDTO;
use App\Repository\Interface\UserRepositoryInterface;
use App\Repository\Interface\EventRepositoryInterface;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Repository\Interface\CommandLogRepositoryInterface;
use App\Service\Interface\Admin\Dashboard\AdminDashboardServiceInterface;

class AdminDashboardService implements AdminDashboardServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventRepositoryInterface $eventRepository,
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly CommandLogRepositoryInterface $commandLogRepository
    )
    {
    }
    public function getDashboardStats(): AdminDashboardStatsDTO
    {
        $registeredUsers = $this->userRepository->count([]);
        $eventsInDatabase = $this->eventRepository->count([]);
        $activeLeagues = $this->leagueRepository->count(['active' => true]);
        $newUsersLast7Days = $this->userRepository->countRegisteredInLast7Days();

        return new AdminDashboardStatsDTO(
            registeredUsers: $registeredUsers,
            eventsInDatabase: $eventsInDatabase,
            activeLeagues: $activeLeagues,
            newUsersLast7Days: $newUsersLast7Days
        );
    }
    public function getCommandStatuses(): array
    {
        $commandStatuses = $this->commandLogRepository->findBy([], ['lastRunAt' => 'DESC'], 10);
        $commandStatusesDTOs = [];
        foreach ($commandStatuses as $commandStatus) {
            $commandStatusesDTOs[] = new CommandStatusDTO(
                name: $commandStatus->getCommandName(),
                lastRunAt: $commandStatus->getLastRunAt(),
                status: $commandStatus->getStatus(),
                output: $commandStatus->getOutput()
            );
        }
        return $commandStatusesDTOs;
    }
    public function getRecentUsers(): array
    {
        $recentUsers = $this->userRepository->findBy([], ['registeredAt' => 'DESC'], 10);
        $recentUserDTOs = [];
        foreach ($recentUsers as $user) {
            $recentUserDTOs[] = new RecentUserDTO(
                email: $user->getEmail(),
                time: $user->getRegisteredAt()
            );
        }
        return $recentUserDTOs;
    }
}
