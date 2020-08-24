<?php

namespace App\Repository;

use App\Entity\CommentHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentHistory[]    findAll()
 * @method CommentHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentHistory::class);
    }

    // /**
    //  * @return CommentHistory[] Returns an array of CommentHistory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentHistory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
