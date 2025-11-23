<?php

namespace App\Service;

use App\Entity\Streak;
use App\Entity\User;
use App\Entity\UserAction;
use Doctrine\ORM\EntityManagerInterface;

class StreakService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function updateOnActionDone(User $user, UserAction $userAction): Streak
    {
        $streak = $user->getStreak();

        if (!$streak) {
            $streak = new Streak();
            $streak->setUser($user);
            $streak->setCurrentCount(0);
            $streak->setBestCount(0);
        }

        $today = $userAction->getDoneAt()->setTime(0, 0);
        $last  = $streak->getLastDoneAt()?->setTime(0, 0);

        if (!$last) {
            // première action
            $streak->setCurrentCount(1);
        } elseif ($today == $last) {
            // même jour, on ne casse pas la série mais on n’incrémente pas
        } elseif ($today->diff($last)->days === 1) {
            // jour suivant, on incrémente
            $streak->setCurrentCount($streak->getCurrentCount() + 1);
        } else {
            // trou dans la série -> on recommence
            $streak->setCurrentCount(1);
        }

        if ($streak->getCurrentCount() > $streak->getBestCount()) {
            $streak->setBestCount($streak->getCurrentCount());
        }

        $streak->setLastDoneAt($userAction->getDoneAt());

        $this->em->persist($streak);
        $this->em->flush();

        return $streak;
    }
}
