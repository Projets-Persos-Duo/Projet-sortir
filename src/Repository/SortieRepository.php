<?php

namespace App\Repository;

use App\Data\SearchSortiesData;
use App\Entity\Campus;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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
            return $sortie->getDateFin() > new \DateTime('-1 month') && empty($sortie->getRaisonAnnulation());
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
    public function findSearch(SearchSortiesData $data, ?UserInterface $moi):array
    {
        $queryBuilder = $this
            ->createQueryBuilder('sortie');

        if(!empty($data->campus)) {
            $queryBuilder
                ->andWhere('sortie.campus in (:cats)')
                ->setParameter('cats', $data->campus);
        }

        if(!empty($data->contient)) {
            $queryBuilder
                ->andWhere('sortie.nom LIKE :q')
                ->setParameter('q', "%$data->contient%");
        }

        if(!empty($search->themes)){
            $queryBuilder=$queryBuilder
                ->andWhere('sortie.theme IN (:thematiques)')
                ->setParameter('thematiques', $search->themes);

        }

        if(!empty($data->entreDebut)) {
            $queryBuilder
                ->andWhere('sortie.date_debut >= :date')
                ->setParameter('date', $data->entreDebut);
        }

        if(!empty($data->entreFin)) {
            $queryBuilder
                ->andWhere('sortie.date_debut <= :date')
                ->setParameter('date', $data->entreFin);
        }

        if(!empty($data->queJOrganise && $moi)) {
            $queryBuilder
                ->andWhere('sortie.organisateur = :orga')
                ->setParameter('orga', $moi);
        }

        if(!empty($data->ouJeSuisInscrit && $moi)) {
            $queryBuilder
                ->join('sortie.participants', 'participants')
                ->andWhere('participants = :moi')
                ->setParameter('moi', $moi);
        }

        if(!empty($data->ouJeSuisPasInscrit && $moi)) {
            $queryBuilder
                ->join('sortie.participants', 'non_participants')
                ->andWhere('non_participants != :moi')
                ->setParameter('moi', $moi);
        }

        if(!empty($data->sortiesPassees)) {
            $queryBuilder
                ->andWhere('sortie.date_fin < :ajd')
                ->setParameter('ajd', new \DateTime("now"));
        }

        $queryBuilder = $this->exclureSortiesAnnulees($queryBuilder);

        $queryBuilder->setMaxResults(10);
        $query=$queryBuilder->getQuery();

        return  $query->getResult();

    }

    private function exclureSortiesExpirees(QueryBuilder $queryBuilder): QueryBuilder
    {
        $queryBuilder->andWhere('sortie.date_fin > :date')->setParameter(
            'date', new \DateTime('-1 month')
        );

        return $queryBuilder;
    }

    private function exclureSortiesAnnulees(QueryBuilder $queryBuilder): QueryBuilder
    {

        return $queryBuilder->andWhere('sortie.raison_annulation IS NULL');
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