<?php

namespace App\Repository;

use App\Entity\RestaurantFavoris;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantFavoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantFavoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantFavoris[]    findAll()
 * @method RestaurantFavoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantFavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantFavoris::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(RestaurantFavoris $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(RestaurantFavoris $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return RestaurantFavoris[] Returns an array of RestaurantFavoris objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RestaurantFavoris
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
