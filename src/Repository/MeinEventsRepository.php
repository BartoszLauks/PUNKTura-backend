<?php

namespace App\Repository;

use App\Entity\CfpEvents;
use App\Entity\MeinEvents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function findEvents(CfpEvents $event) : array
    {
        return $this->createQueryBuilder('mein')
            ->where('LOWER(mein.full_name) LIKE LOWER(:fullName) AND LOWER(mein.handle) LIKE LOWER(:handle)')
            ->setParameter('fullName', '%' . $event->getFullName() . '%')
            ->setParameter('handle', '%' . $event->getClearHandle() . '%')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findEventsFilters(array $filters) : array
    {
        $query = $this->createQueryBuilder('mein')
            ->select('mein.id, mein.full_name, mein.handle, mein.point')
            ;

        if (key_exists('page', $filters))
        {
            $result = $query->getQuery()->getResult();

            $page = 1;
            if (key_exists('page', $filters)) {
                $page = (int) $filters['page'];
            }

            if($page < 1) {
                $page = 1;
            }

            $perPage = 10;
            if (key_exists('perPage',$filters) && ctype_digit($filters['perPage']))
            {
                $perPage = $filters['perPage'];
            }

            $total = count($result);

            $totalPages = ceil($total/ $perPage);

            $offset = ($page - 1) * $perPage;

            if( $offset < 0 ) $offset = 0;

            $yourDataArray = array_slice($result, $offset, $perPage );

            if($page > $totalPages){
                throw new HttpException(Response::HTTP_FOUND, 'Page Not Found');
            }
            return [
                'pagination' => [
                    'page' => $page,
                    'allPages' => $totalPages
                ],
                'results' => $yourDataArray
            ];
        }

        return $query->getQuery()->getResult();
    }
}
