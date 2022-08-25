<?php

namespace App\Repository;

use App\Data\SearchSortiesData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;

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
     * On affiche toutes les sorties et on organise par date
     * @return Sortie[]
     */
    public function findAllTrie()
    {
        return $this->findBy(array(), array('date_debut'=>'ASC'));
    }

    /**
     * On affiche toutes les sorties en cours et organisées par date
     * Fonction utilisée dans la page d'accueil (MainController)
     * @return Sortie[]
     */
    public function findNonArchivees(): array
    {
        $sorties = $this->findAllTrie();
        $sorties = array_filter($sorties, function (Sortie $sortie)
        {
            return $sortie->getDateFin() > new \DateTime('-1 day') && empty($sortie->getRaisonAnnulation());
        });

        return $sorties;
    }

    /**
     * Ne retourne que les sorties archivées (lié à SortieController et interface.html.twig)
     * Elles sont classées par ordre chronologique
     * @return Sortie[]
     */
    public function findArchivees(): array
    {
        $sorties = $this->findAllTrie();
        $sorties = array_filter($sorties, function (Sortie $sortie)
        {
            return $sortie->getDateFin() <= new \DateTime('-1 day');
        });

        return $sorties;
    }

    /**
     * Lister les sorties en fonction de la selection de l'utilisateur (issu de mainController)
     * On ajoute aussi ici que le type de retour est un tableau de "sortie" (lié à entité sortie)
     * @return Sortie []
     */
    public function findSearch(SearchSortiesData $data, ?UserInterface $moi):array
    {
        $queryBuilder = $this
            ->createQueryBuilder('sortie')
            ->leftJoin('sortie.theme', 'theme')
            ->select('sortie');

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

        if(!empty($data->themes)){
            $queryBuilder=$queryBuilder
                ->andWhere('sortie.theme IN (:thematiques)')
                ->setParameter('thematiques', $data->themes);

        }

        if(!empty($data->entreDebut)) {
            $queryBuilder
                ->andWhere('sortie.date_debut >= :date_deb')
                ->setParameter('date_deb', $data->entreDebut);
        }

        if(!empty($data->entreFin)) {
            $queryBuilder
                ->andWhere('sortie.date_debut <= :date_fin')
                ->setParameter('date_fin', $data->entreFin);
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
                ->setParameter('ajd', date('d-m-y'));
        }

        $queryBuilder = $this->exclureSortiesAnnulees($queryBuilder);
        $queryBuilder = $this->exclureSortiesExpirees($queryBuilder);


//        $queryBuilder->setMaxResults(10);
        $query=$queryBuilder->getQuery();
        dump($data, $query, $queryBuilder);
        dump( date('d-m-y'));
        return  $query->getResult();
    }

    //A l'ouverture de la page d'accueil permet d'exclure automatiquement les sorties passées
    private function exclureSortiesExpirees(QueryBuilder $queryBuilder): QueryBuilder
    {
        $queryBuilder->andWhere('sortie.date_fin > :date_expire')->setParameter(
            'date_expire', new \DateTime('-1 day')
        );

        return $queryBuilder;
    }

    private function exclureSortiesAnnulees(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder->andWhere('sortie.raison_annulation IS NULL');
    }

    //Pour trier les sorties par dates asc, pour afficher d'abord les plus proches
    //Fonction qui ne semble pas fonctionner... et qui n'est pas utilisée !! TODO
    /**
     * @return Sortie[]
     */
    public function trierSortiesParDatesPlusProches(): array
    {
        $queryBuilder= $this->createQueryBuilder('s');
        $queryBuilder->orderBy('s.date_debut', 'ASC');

        $sorties = $queryBuilder->getQuery();

        return $sorties->getResult();
    }



}