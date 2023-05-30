<?php

namespace App\Repository;

use App\Entity\CfpEvents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CfpEvents>
 *
 * @method CfpEvents|null find($id, $lockMode = null, $lockVersion = null)
 * @method CfpEvents|null findOneBy(array $criteria, array $orderBy = null)
 * @method CfpEvents[]    findAll()
 * @method CfpEvents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CfpEventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CfpEvents::class);
    }

    public function save(CfpEvents $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CfpEvents $entity, bool $flush = false): void
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
}
