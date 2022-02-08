<?php

namespace App\Repository;

use App\Entity\Thematiques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Thematiques|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thematiques|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thematiques[]    findAll()
 * @method Thematiques[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThematiquesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thematiques::class);
    }

    // /**
    //  * @return Thematiques[] Returns an array of Thematiques objects
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
    public function findOneBySomeField($value): ?Thematiques
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
