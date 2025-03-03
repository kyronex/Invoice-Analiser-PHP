<?php

namespace App\Repository;

use App\Entity\FactureProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FactureProduit>
 *
 * @method FactureProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactureProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactureProduit[]    findAll()
 * @method FactureProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactureProduit::class);
    }

//    /**
//     * @return FactureProduit[] Returns an array of FactureProduit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FactureProduit
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
