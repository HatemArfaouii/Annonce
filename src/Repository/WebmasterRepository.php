<?php

namespace App\Repository;

use App\Entity\Webmaster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Webmaster|null find($id, $lockMode = null, $lockVersion = null)
 * @method Webmaster|null findOneBy(array $criteria, array $orderBy = null)
 * @method Webmaster[]    findAll()
 * @method Webmaster[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebmasterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Webmaster::class);
    }

    // /**
    //  * @return Webmaster[] Returns an array of Webmaster objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Webmaster
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
