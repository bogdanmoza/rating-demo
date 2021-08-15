<?php

namespace App\Repository;

use App\Entity\Vico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vico|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vico|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vico[]    findAll()
 * @method Vico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vico::class);
    }

    // /**
    //  * @return Vico[] Returns an array of Vico objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vico
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
