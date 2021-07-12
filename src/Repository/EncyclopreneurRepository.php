<?php

namespace App\Repository;

use App\Entity\Encyclopreneur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Encyclopreneur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Encyclopreneur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Encyclopreneur[]    findAll()
 * @method Encyclopreneur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncyclopreneurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Encyclopreneur::class);
    }

    // /**
    //  * @return Encyclopreneur[] Returns an array of Encyclopreneur objects
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
    public function findOneBySomeField($value): ?Encyclopreneur
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
