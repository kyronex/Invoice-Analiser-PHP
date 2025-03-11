<?php

namespace App\Repository;

use App\Entity\RejectFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RejectFile>
 *
 * @method RejectFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method RejectFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method RejectFile[]    findAll()
 * @method RejectFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RejectFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RejectFile::class);
    }

//    /**
//     * @return RejectFile[] Returns an array of RejectFile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RejectFile
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
