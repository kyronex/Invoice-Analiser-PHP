<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * Find a client by exact match of nom, prenom, and adresse (case-insensitive)
     */
    public function findDuplicate(array $param): array
    {
        return $this->createQueryBuilder('c')
            ->where('LOWER(c.nom) = LOWER(:nom)')
            ->andWhere('LOWER(c.prenom) = LOWER(:prenom)')
            //->andWhere('LOWER(c.adresse) = LOWER(:adresse)')
            ->setParameter("nom",$param["nom"])
            ->setParameter("prenom",$param["prenom"])
            //->setParameter("adresse",$param["adresse"])
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getResult();
            //->getArrayResult();
    }
}
