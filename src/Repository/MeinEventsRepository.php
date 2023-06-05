<?php

namespace App\Repository;

use App\Entity\CfpEvents;
use App\Entity\MeinEvents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MeinEvents>
 *
 * @method MeinEvents|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeinEvents|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeinEvents[]    findAll()
 * @method MeinEvents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeinEventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeinEvents::class);
    }

    public function save(MeinEvents $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MeinEvents $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function findEvents(CfpEvents $event)
    {
        return $this->createQueryBuilder('mein')
            ->where('LOWER(mein.full_name) LIKE LOWER(:fullName)')
            ->setParameter('fullName', '%' . $event->getFullName() . '%')
            ->getQuery()
            ->getResult()
            ;
    }
}
