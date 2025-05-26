<?php

namespace App\Repository\Planet\InfrastructureReport;

use App\Entity\Planet\InfrastructureReport\Infrastructure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Infrastructure>
 */
class InfrastructureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Infrastructure::class);
    }

    //    /**
    //     * @return Infrastructure[] Returns an array of Infrastructure objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Infrastructure
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
