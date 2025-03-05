<?php

namespace App\Repository;

use App\Entity\EvoProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EvoProduit>
 *
 * @method EvoProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method EvoProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method EvoProduit[]    findAll()
 * @method EvoProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvoProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EvoProduit::class);
    }

//    /**
//     * @return EvoProduit[] Returns an array of EvoProduit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EvoProduit
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
