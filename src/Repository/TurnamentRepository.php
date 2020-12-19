<?php

namespace App\Repository;

use App\Entity\Turnament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Turnament|null find($id, $lockMode = null, $lockVersion = null)
 * @method Turnament|null findOneBy(array $criteria, array $orderBy = null)
 * @method Turnament[]    findAll()
 * @method Turnament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurnamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Turnament::class);
    }
    public function search($title) {
        return $this->createQueryBuilder('Turnament')
            ->andWhere('Turnament.name LIKE :title')
            ->setParameter('title', '%'.$title.'%')
            ->getQuery()
            ->execute();
    }

    // /**
    //  * @return Turnament[] Returns an array of Turnament objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Turnament
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
