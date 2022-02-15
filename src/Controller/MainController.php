<?php

namespace App\Controller;

use App\Data\SearchSortiesData;
use App\Form\SortieSearchType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="main_home")
     */


    public function home(Request $request, SortieRepository $sortieRepository, EntityManagerInterface $entityManager)
    {
        $data= new SearchSortiesData();
        $sortieChoixForm = $this->createForm(SortieSearchType::class, $data);
        $sortieChoixForm->handleRequest($request);

        $sorties = $sortieRepository->findNonArchivees();

        if ($sortieChoixForm->isSubmitted() &&  $sortieChoixForm->isValid()) {
            $sorties = $sortieRepository->findSearch($data, $this->getUser());

        }

        if (!empty($sortie = $sortieRepository->find((int)$request->get('rejoindre'))) && $this->getUser()) {
            $this->getUser()->addSortiesParticipee($sortie);
            $entityManager->persist($sortie);
            $entityManager->persist($this->getUser());
            $entityManager->flush();
        }

        if (!empty($sortie = $sortieRepository->find((int)$request->get('quitter'))) && $this->getUser()) {
            $this->getUser()->removeSortiesParticipee($sortie);
            $entityManager->persist($sortie);
            $entityManager->persist($this->getUser());
            $entityManager->flush();
        }

        if (!empty($sortie = $sortieRepository->find((int)$request->get('annuler'))) && $this->getUser()
        && $this->getUser()->getUserIdentifier() == $sortie->getOrganisateur()->getUserIdentifier()) {
            $sortie->setRaisonAnnulation('automatiquement annulé depuis la liste');
            $entityManager->persist($sortie);
            $entityManager->persist($this->getUser());
            $entityManager->flush();
            $this->addFlash('success', 'La sortie a bien été annulée');
            $sorties = $sortieRepository->findNonArchivees();
        }


        return $this->render (
            '/main/home.html.twig', [
                'sorties' => $sorties,
                'sortieChoixForm' => $sortieChoixForm->createView(),

            ]
        );

    }


}