<?php

namespace App\Repository;

use App\Entity\ElementDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ElementDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method ElementDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method ElementDetails[]    findAll()
 * @method ElementDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElementDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ElementDetails::class);
    }

    // /**
    //  * @return ElementDetails[] Returns an array of ElementDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ElementDetails
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
