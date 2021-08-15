<?php

namespace App\Repository;

use App\Entity\RatingQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RatingQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method RatingQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method RatingQuestion[]    findAll()
 * @method RatingQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RatingQuestion::class);
    }

    // /**
    //  * @return RatingQuestion[] Returns an array of RatingQuestion objects
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
    public function findOneBySomeField($value): ?RatingQuestion
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
