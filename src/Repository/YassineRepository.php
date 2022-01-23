<?php

namespace App\Repository;

use App\Entity\Yassine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Yassine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Yassine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Yassine[]    findAll()
 * @method Yassine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class YassineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Yassine::class);
    }

    // /**
    //  * @return Yassine[] Returns an array of Yassine objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('y.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Yassine
    {
        return $this->createQueryBuilder('y')
            ->andWhere('y.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
