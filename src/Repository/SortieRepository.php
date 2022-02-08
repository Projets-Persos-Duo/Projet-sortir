<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Sortie;use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }


    /**
     * Lister les sorties en fonction du campus choisi
     */
    public function sortiesParCampus (sortie $sortie):array
    {

        $entityManager=$this->getEntityManager();
        $dql="SELECT c FROM App\Entity\Sortie WHERE c.campus=? ORDER BY c.nom DESC";
       $query=$entityManager->createQuery($dql);
       $query->setMaxResults(30);
        return $query->getResult();
    }


    /**
     * Lister les sorties en fonction du thème choisi
     */
    public function sortiesParTheme (sortie $sortie):array
    {
        $entityManager=$this->getEntityManager();
        $dql="SELECT c FROM App\Entity\Sortie WHERE c.theme=? ORDER BY c.nom DESC";
        $query=$entityManager->createQuery($dql);
        $query->setMaxResults(30);
        return $query->getResult();
    }




    // /**
    //  * @return Sortie[] Returns an array of Campus objects
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
    public function findOneBySomeField($value): ?Campus
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