<?php

namespace App\Repository;

use App\Entity\UserAction;
use App\Entity\User;
use App\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserAction>
 */
class UserActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAction::class);
    }

    /**
     * Somme des points gagnés par un utilisateur
     */
    public function sumPointsForUser(int $userId): int
    {
        $qb = $this->createQueryBuilder('ua')
            ->select('COALESCE(SUM(ua.pointsEarned), 0)')
            ->andWhere('ua.user = :uid')
            ->setParameter('uid', $userId);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Dernières actions réalisées par un utilisateur
     */
    public function findLatestForUser(int $userId, int $limit = 20): array
    {
        return $this->createQueryBuilder('ua')
            ->andWhere('ua.user = :uid')
            ->setParameter('uid', $userId)
            ->orderBy('ua.doneAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si l'utilisateur a déjà fait CETTE action à cette date
     */
    public function findOneForUserAndActionOnDate(
        User $user,
        Action $action,
        \DateTimeImmutable $day
    ): ?UserAction {
        $start = $day->setTime(0, 0, 0);
        $end   = $day->setTime(23, 59, 59);

        return $this->createQueryBuilder('ua')
            ->andWhere('ua.user = :user')
            ->andWhere('ua.action = :action')
            ->andWhere('ua.doneAt BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('action', $action)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
