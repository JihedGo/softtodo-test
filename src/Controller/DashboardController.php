<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $doneProjects       = $projectRepository->findBy(['status' => 'DONE']);
        $blockedProjects    = $projectRepository->findBy(['status' => 'BLOCKED']);
        $inprogressProjects = $projectRepository->findBy(['status' => 'IN_PROGRESS']);
        return $this->render('dashboard/dashboard.html.twig', [
            'nbDone' => count($doneProjects),
            'nbBlocked' => count($blockedProjects),
            'nbInProgress' => count($inprogressProjects)
        ]);
    }
}