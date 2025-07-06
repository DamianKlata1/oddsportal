<?php

namespace App\Controller\Api\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Interface\Admin\Dashboard\AdminDashboardServiceInterface;

class DashboardController extends AbstractController
{
    public function __construct
    (
        private readonly AdminDashboardServiceInterface $adminDashboardService
    ) {
    }

    #[Route('/stats', name: 'api_admin_get_stats')]
    public function getStats(): Response
    {
        $statsDTO = $this->adminDashboardService->getDashboardStats();
        return $this->json($statsDTO);
    }
    #[Route('/command-statuses', name: 'api_admin_get_command_statuses')]
    public function getCommandStatuses(): Response
    {
        $commandStatuses = $this->adminDashboardService->getCommandStatuses();
        return $this->json($commandStatuses);
    }
    #[Route('/recent-users', name: 'api_admin_get_recent_users')]
    public function getRecentUsers(): Response
    {
        $recentUsers = $this->adminDashboardService->getRecentUsers();
        return $this->json($recentUsers);
    }
}
