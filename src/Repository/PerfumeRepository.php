<?php

namespace App\Repository;

use App\Entity\OlfactiveFamily;
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

   // Filtrage par famille olfactive
   public function findByOLf(OlfactiveFamily $olfactiveFamily){
    $queryBuilder = $this->createQueryBuilder('p');
    if (!empty($olfactiveFamily)) {
         $queryBuilder
            ->andWhere('p.olfactive_family_id = :olfactiveFamily')
            ->setParameter('olfactiveFamily', $olfactiveFamily);
    }
   }
        
//     //fonction pour les requetes de filtrage catalogue
//     public function findByFilters($olfactiveFamily, $brand, $priceRange, $tag)
// {
//     $queryBuilder = $this->createQueryBuilder('p');
    
//     // Filtrage par famille olfactive
//     if (!empty($olfactiveFamily)) {
//         $queryBuilder
//             ->andWhere('p.olfactive_family_id = :olfactiveFamily')
//             ->setParameter('olfactiveFamily', $olfactiveFamily);
//     }
    
//     // Filtrage par marque
//     if ($brand) {
//         $queryBuilder
//             ->andWhere('p.brand = :brand')
//             ->setParameter('brand', $brand);
//     }
    
//     // Filtrage par prix
//     if ($priceRange) {
//         $priceRanges = explode('-', $priceRange);
//         if (count($priceRanges) == 2) {
//             $queryBuilder
//                 ->andWhere('p.price BETWEEN :minPrice AND :maxPrice')
//                 ->setParameter('minPrice', $priceRanges[0])
//                 ->setParameter('maxPrice', $priceRanges[1]);
//         } elseif ($priceRanges[0] == '200+') {
//             $queryBuilder
//                 ->andWhere('p.price >= :minPrice')
//                 ->setParameter('minPrice', 200);
//         }
//     }
    
//     // Filtrage par tag (note)
//     if ($tag) {
//         $queryBuilder
//             ->leftJoin('p.note_id', 'n')
//             ->andWhere(':tag MEMBER OF n')
//             ->setParameter('tag', $tag);
//     }
    
//     return $queryBuilder->getQuery()->getResult();
// }
// public function findByPrice($value)
// {
//     // Création d'une requête QueryBuilder pour l'entité Perfume, avec l'alias 'a'
//     return $this->createQueryBuilder('a')
//         //Ajout d'une condition WHERE pour filtrer les parfums dont le prix est inférieur à la valeur fournie dans les select option du twig
//         ->andWhere('a.price < :val')
//         // Liaison de la valeur fournie avec le paramètre ':val' dans la requête SQL
//         ->setParameter('val', $value)
//         // résultats par ordre croissant de prix
//         ->orderBy('a.price', 'ASC')
//         // Obtention de l'objet Query finalisé
//         ->getQuery()
//         // Exécution de la requête SQL et récupération des résultats
//         ->getResult();
// }
// public function findByPriceCategory($value, $category)
// {
//     // Création d'une requête QueryBuilder pour l'entité Perfume, avec l'alias 'a'
//     return $this->createQueryBuilder('a')
//         // Ajout de deux conditions WHERE : une pour filtrer les parfums dont le prix est inférieur à la valeur fournie,
//         // et une autre pour filtrer les parfums par catégorie/famille olfactive
//         ->andWhere('a.price < :val', "a.category = $category")
//         // Liaison de la valeur fournie avec le paramètre ':val' dans la requête SQL
//         ->setParameter('val', $value)
//         // Tri des résultats par ordre croissant de prix
//         ->orderBy('a.price', 'ASC')
//         // Obtention de l'objet Query finalisé
//         ->getQuery()
//         // Exécution de la requête SQL et récupération des résultats
//         ->getResult();
// }

// //filtrer par note
// public function findByNote($note)
// {
//     // Création d'une requête QueryBuilder pour l'entité Perfume, avec l'alias 'p'
//     return $this->createQueryBuilder('p')
//         // Ajout d'une condition WHERE pour filtrer les parfums par la présence de la note spécifiée
//         ->where(':note MEMBER OF p.notes')
//         // Liaison de la note spécifiée avec le paramètre ':note' dans la requête SQL
//         ->setParameter('note', $note)
//         // Obtention de l'objet Query finalisé
//         ->getQuery()
//         // Exécution de la requête SQL et récupération des résultats
//         ->getResult();
// }
// public function findBySearch($value)
// {
//     // Création d'une requête QueryBuilder pour l'entité Perfume, avec l'alias 'p'
//     return $this->createQueryBuilder('p')
//         // Ajout d'une condition WHERE pour rechercher les parfums dont le titre ressemble à la valeur spécifiée
//         ->where('p.title LIKE :val')
//         // Liaison de la valeur spécifiée avec le paramètre ':val' dans la requête SQL
//         ->setParameter('val', '%' . $value . '%')
//         // Tri des résultats par ordre alphabétique croissant du titre
//         ->orderBy('p.title', 'ASC')
//         // Obtention de l'objet Query finalisé
//         ->getQuery()
//         // Exécution de la requête SQL et récupération des résultats
//         ->getResult();
// }



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
