<?php

namespace App\Repository;

use App\Entity\EtatElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EtatElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtatElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtatElement[]    findAll()
 * @method EtatElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtatElement::class);
    }

    // /**
    //  * @return EtatElement[] Returns an array of EtatElement objects
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
    public function findOneBySomeField($value): ?EtatElement
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
