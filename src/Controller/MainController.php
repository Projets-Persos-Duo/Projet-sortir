<?php

namespace App\Controller;

use App\Data\SearchSortiesData;
use App\Form\SortieSearchType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="main_home")
     */

    public function home(Request $request,
                         SortieRepository $sortieRepository,
                         EntityManagerInterface $entityManager,
                         PaginatorInterface $paginator)
    {
        $data= new SearchSortiesData();
        $sortieChoixForm = $this->createForm(SortieSearchType::class, $data);
        $sortieChoixForm->handleRequest($request);

        $sorties = $sortieRepository->findSearch($data, $this->getUser());

        /* on traite la demande de rejoindre depuis la liste */
        if (!empty($sortie = $sortieRepository->find((int)$request->get('rejoindre')))
            && $this->getUser()) {
            $this->getUser()->addSortiesParticipee($sortie);
            $entityManager->persist($sortie);
            $entityManager->persist($this->getUser());
            $entityManager->flush();
        }

        /* on traite la demande de quitter depuis la liste */
        if (!empty($sortie = $sortieRepository->find((int)$request->get('quitter')))
            && $this->getUser()) {
            $this->getUser()->removeSortiesParticipee($sortie);
            $entityManager->persist($sortie);
            $entityManager->persist($this->getUser());
            $entityManager->flush();
        }

        /* on traite la demande d'annulation depuis la liste */
        if (!empty($sortie = $sortieRepository->find((int)$request->get('annuler')))
            && $this->getUser()
            && $this->getUser()->getUserIdentifier() == $sortie->getOrganisateur()->getUserIdentifier()
        ) {
            $sortie->setRaisonAnnulation('automatiquement annulé depuis la liste');
            $entityManager->persist($sortie);
            $entityManager->persist($this->getUser());
            $entityManager->flush();
            $this->addFlash('success', 'La sortie a bien été annulée');
            $sorties = $sortieRepository->findSearch($data, $this->getUser());
        }

        $sortiePaginator=$paginator->paginate(
            $sorties, //on passe les données
            $request->query->getInt('page', 1), //numéro page en cours - 1 par défault
            6 //nombre d'éléments par page
        );

             return $this->render (
            '/main/home.html.twig', [
                'sorties' => $sortiePaginator,
                'sortieChoixForm' => $sortieChoixForm->createView(),

            ]
        );

    }


}