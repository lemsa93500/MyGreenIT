<?php

namespace App\Repository;

use App\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Action>
 */
class ActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Action::class);
    }

    /**
     * Retourne 1 action active aléatoire, sans OFFSET, scalable.
     */
    public function findRandomOne(): ?Action
    {
        // Récupère uniquement les IDs des actions actives
        $rows = $this->createQueryBuilder('a')
            ->select('a.id')
            ->andWhere('a.isActive = :on')->setParameter('on', true)
            ->getQuery()
            ->getArrayResult();

        if (!$rows) {
            return null;
        }

        $ids = array_column($rows, 'id');
        $randomId = $ids[array_rand($ids)];

        return $this->find($randomId);
    }
}
