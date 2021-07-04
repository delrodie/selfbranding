<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function findByTag($tag,$antitag = null)
    {
        $tag1 = "accompagnement"; $tag2="suivi";

        $query =  $this->createQueryBuilder('s');
        if ($antitag){
            $query->where('s.titre LIKE :tag1')
                ->orWhere('s.titre LIKE :tag2')
                ->setParameters([
                    'tag1' => '%'.$tag1.'%',
                    'tag2' => '%'.$tag2.'%'
                ])
            ;
        }else{
            $query->where('s.titre LIKE :tag')
            ->setParameter('tag', '%'.$tag.'%');
        }

        return $query
            ->getQuery()->getResult()
            ;
    }

    // /**
    //  * @return Service[] Returns an array of Service objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Service
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
