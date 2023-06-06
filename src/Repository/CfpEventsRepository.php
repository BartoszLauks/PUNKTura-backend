<?php

namespace App\Repository;

use App\Entity\CfpEvents;
use App\Entity\MeinEvents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function findEvents(MeinEvents $event) : array
    {
        return $this->createQueryBuilder('cfp')
            ->where(
                'LOWER(cfp.fullName) LIKE LOWER(:fullName) AND LOWER(cfp.clearHandle) LIKE LOWER(:handle)')
            ->setParameter(':fullName', '%'.$event->getFullName().'%')
            ->setParameter(':handle', '%'.$event->getHandle().'%')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findEventsFilters(array $filters) : array
    {
        $query = $this->createQueryBuilder('cfp')
            ->select('cfp.id, cfp.fullName, cfp.clearHandle, cfp.point, cfp.year, cfp.location, cfp.submitDate, cfp.beginDate, cfp.finishDate')
            ;

        if (key_exists('point', $filters) && $filters['point'] === 'true') {
            $query
            ->where('cfp.point IS NOT NULL');
        }

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
