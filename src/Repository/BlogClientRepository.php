<?php

namespace App\Repository;

use App\Entity\BlogClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogClient[]    findAll()
 * @method BlogClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogClient::class);
    }

    // /**
    //  * @return BlogClient[] Returns an array of BlogClient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlogClient
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
