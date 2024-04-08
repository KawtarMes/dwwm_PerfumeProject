<?php

namespace App\Repository;

use App\Entity\Perfume;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Perfume>
 *
 * @method Perfume|null find($id, $lockMode = null, $lockVersion = null)
 * @method Perfume|null findOneBy(array $criteria, array $orderBy = null)
 * @method Perfume[]    findAll()
 * @method Perfume[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PerfumeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Perfume::class);
    }

    //    /**
    //     * @return Perfume[] Returns an array of Perfume objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Perfume
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
