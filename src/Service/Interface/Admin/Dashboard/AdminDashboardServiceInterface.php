<?php

namespace App\Service\Interface\Admin\Dashboard;

use App\DTO\Admin\Dashboard\CommandStatusDTO;
use App\DTO\Admin\Dashboard\AdminDashboardStatsDTO;
use App\DTO\Admin\Dashboard\RecentUserDTO;

interface AdminDashboardServiceInterface
{
    public function getDashboardStats(): AdminDashboardStatsDTO;

    /**
     * @return CommandStatusDTO[]
     */
    public function getCommandStatuses(): array;

    /**
     * @return RecentUserDTO[]
     */
    public function getRecentUsers(): array;


}
