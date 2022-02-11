<?php

namespace App\Repository;

use App\Data\SearchData;
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
     * Lister les sorties en fonction du campus choisi via nouvelle methode
     * On ajoute aussi ici que le type de retour est un tableau de "sortie" (lié à entité sortie)
     * @return Sortie []
     */
    public function findSearchCampus (SearchData $search):array
    {
    $queryBuilder =$this
        ->createQueryBuilder('s')
        ->select('c','s')
        ->innerJoin('s.campus', 'c');



    if(!empty($search->campus)){
    $queryBuilder=$queryBuilder
        ->andWhere('s.campus = :campus')
        ->setParameter('campus', $search->campus);

    }

$queryBuilder->setMaxResults(10);
        $query=$queryBuilder->getQuery();
        return  $query->getResult();


    }

    /**
     * Lister les sorties en fonction du campus choisi via nouvelle methode
     * On ajoute aussi ici que le type de retour est un tableau de "sortie" (lié à entité sortie)
     * @return Sortie []
     */
    public function findSearchThematique (SearchData $search):array
    {
        $queryBuilder =$this
            ->createQueryBuilder('s')
            ->select('t','s')
             ->join('s.theme', 't');


        if(!empty($search->thematiques)){
            $queryBuilder=$queryBuilder
                ->andWhere('c.id IN (:thematiques)')
                ->setParameter('thematiques', $search->thematiques);

        }


        $query=$queryBuilder->getQuery();
        return  $query->getResult();


    }


}