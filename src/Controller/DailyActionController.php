<?php

namespace App\Controller;

use App\Entity\UserAction;
use App\Repository\ActionRepository;
use App\Repository\UserActionRepository;
use App\Service\StreakService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class DailyActionController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(ActionRepository $actionRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $action = $actionRepository->findRandomOne();

        return $this->render('daily/home.html.twig', [
            'action' => $action,
        ]);
    }

    #[Route('/do-action/{id}', name: 'app_do_action', methods: ['POST'])]
    public function doAction(
        int $id,
        ActionRepository $actionRepository,
        UserActionRepository $userActionRepository,
        StreakService $streakService,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $action = $actionRepository->find($id);

        if (!$action || !$action->isActive()) {
            $this->addFlash('danger', 'Action introuvable.');
            return $this->redirectToRoute('app_home');
        }

        // Vérifie si l’utilisateur a déjà fait cette action aujourd’hui (optionnel)
        $today = (new \DateTimeImmutable('today'));
        $already = $userActionRepository->findOneForUserAndActionOnDate($user, $action, $today);

        if ($already) {
            $this->addFlash('info', 'Tu as déjà validé cette action aujourd’hui.');
            return $this->redirectToRoute('app_home');
        }

        // Création du UserAction (trace de l’action faite)
        $userAction = new UserAction();
        $userAction->setUser($user);
        $userAction->setAction($action);
        // ICI l’erreur : on utilise bien setPointsEarned()
        $userAction->setPointsEarned($action->getBasePoints());
        $userAction->setDoneAt(new \DateTimeImmutable());

        $em->persist($userAction);

        // Mise à jour de la streak à partir de cette action
        $streak = $streakService->updateOnActionDone($user, $userAction);

        $em->flush();

        $this->addFlash(
            'success',
            sprintf(
                'Bravo ! +%d points. Série actuelle : %d (record : %d).',
                $action->getBasePoints(),
                $streak->getCurrentCount(),
                $streak->getBestCount()
            )
        );

        return $this->redirectToRoute('app_home');
    }
}
