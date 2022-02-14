<?php

namespace App\Repository;

use App\Data\SearchSortiesData;
use App\Entity\Campus;
use App\Entity\Sortie;use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @return Sortie[]
     */
    public function findNonArchivees(): array
    {
        $sorties = parent::findAll();
        $sorties = array_filter($sorties, function (Sortie $sortie) {
            return $sortie->getDateFin() > new \DateTime('-1 month');
        });

        return $sorties;
    }

    /**
     * Ne retourne que les sorties archivées (peut être utile)
     * @return Sortie[]
     */
    public function findArchivees(): array
    {
        $sorties = parent::findAll();
        $sorties = array_filter($sorties, function (Sortie $sortie) {
            return $sortie->getDateFin() <= new \DateTime('-1 month');
        });

        return $sorties;

    }

    /**
     * Lister les sorties en fonction du campus choisi via nouvelle methode
     * On ajoute aussi ici que le type de retour est un tableau de "sortie" (lié à entité sortie)
     * @return Sortie []
     */
    public function findSearch(SearchSortiesData $search):array
    {
        $queryBuilder = $this
            ->createQueryBuilder('sortie');

        if(!empty($data->campus)) {
            $queryBuilder
                ->andWhere('sortie.campus in (:cats)')
                ->setParameter('cats', $data->campus);
        }

        if(!empty($search->thematiques)){
            $queryBuilder=$queryBuilder
                ->andWhere('c.id IN (:thematiques)')
                ->setParameter('thematiques', $search->thematiques);

        }

        $queryBuilder = $this->exclureSortiesExpirees($queryBuilder);


        $queryBuilder->setMaxResults(10);
        $query=$queryBuilder->getQuery();

        return  $query->getResult();

    }

    private function exclureSortiesExpirees(QueryBuilder $queryBuilder): QueryBuilder
    {
//        $queryBuilder->andWhere('s.date_fin < :date')->setParameter(
//            'date', new \DateTimeImmutable()
//        );

        return $queryBuilder;
    }

    //Pour trier les sorties par dates asc, pour afficher d'abord les plus proches
    /**
     * @return Sortie[]
     */
    public function trierSortiesParDatesPlusProches(): array
    {
        $queryBuilder= $this->createQueryBuilder('s');$queryBuilder
        ->orderBy('s.date_debut', 'ASC');

        $sorties = $queryBuilder->getQuery();

        return $sorties->getResult();
    }


}