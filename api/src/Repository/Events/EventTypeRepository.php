<?php

namespace App\Repository\Events;

use App\Entity\Events\EventType;
use App\Entity\Events\LootLimit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventType|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventType|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventType[]    findAll()
 * @method EventType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventType::class);
    }

    public function isAllowedLootLimit(string $lootLimit, int $eventTypeId) : bool
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $result = $queryBuilder
            ->select('count(e.id)')
            ->innerJoin(LootLimit::class, 'l', 'WITH', 'e.lootLimit = l.id')
            ->where('e.id = :event_type_id')
            ->setParameter('event_type_id', $eventTypeId)
            ->andWhere('l.type = :type')
            ->setParameter('type', $lootLimit)
            ->getQuery()
            ->getResult();

        return (bool) $result[0][1];
    }

    // /**
    //  * @return EventType[] Returns an array of EventType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventType
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
