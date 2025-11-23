<?php

namespace App\Controller;

use App\Repository\UserActionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard', methods: ['GET'])]
    public function index(UserActionRepository $uaRepo): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $userId = $user->getId();

        $total  = (int) ($uaRepo->sumPointsForUser($userId) ?? 0);
        $streak = method_exists($user, 'getStreak') ? $user->getStreak() : null;
        $latest = $uaRepo->findLatestForUser($userId, 20);

        return $this->render('dashboard/index.html.twig', [
            'totalPoints'   => $total,
            'currentStreak' => $streak?->getCurrentCount() ?? 0,
            'bestStreak'    => $streak?->getBestCount() ?? 0,
            'lastDoneAt'    => $streak?->getLastDoneAt(),
            'latest'        => $latest,
        ]);
    }
}
