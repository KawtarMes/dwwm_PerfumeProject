<?php

namespace App\Repository;

use App\Entity\OlfactiveFamily;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OlfactiveFamily>
 *
 * @method OlfactiveFamily|null find($id, $lockMode = null, $lockVersion = null)
 * @method OlfactiveFamily|null findOneBy(array $criteria, array $orderBy = null)
 * @method OlfactiveFamily[]    findAll()
 * @method OlfactiveFamily[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OlfactiveFamilyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OlfactiveFamily::class);
    }

    //    /**
    //     * @return OlfactiveFamily[] Returns an array of OlfactiveFamily objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OlfactiveFamily
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
