<?php

namespace App\Repository;

use App\Entity\PollAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PollAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method PollAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method PollAnswer[]    findAll()
 * @method PollAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PollAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PollAnswer::class);
    }

    // /**
    //  * @return PollAnswer[] Returns an array of PollAnswer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PollAnswer
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
