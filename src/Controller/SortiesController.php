<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\InscriptionSortieFormType;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/sorties", name="sorties_")
 */

class SortiesController extends AbstractController
{



    /**
     * @Route("/list", name="list")
     */
    public function listeSorties(SortieRepository $sortieRepository): Response
    {
        $sorties=$sortieRepository->findAll();


        return $this->render('sorties/list.html.twig', [
            'sorties' => $sorties,
        ]);
    }

    /**
     * @Route("/list/campus/{id}", name="campus_list")
     */
    public function listeSortiesCampus(int $id, Request $request,
                                       EntityManagerInterface $entityManager,
                                       SortieRepository $sortieRepository,
                                       CampusRepository $campusRepository): Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $entityManager->persist($sortie);
            $entityManager->flush();
        }

            $campus = $campusRepository->find($id);
            $sorties = $sortieRepository->sortiesParCampus($campus);


            return $this->render('sorties/listCampus.html.twig', [
                'sorties' => $sorties,'sortieForm'=>$sortieForm->createView()
            ]);
    }



    /**
     * @Route("/list/theme", name="thematique_list")
     */
    /*public function listeSortiesThematiques(SortieRepository $sortieRepository): Response
    {
        //TODO : retravailler cette fonction

        $sorties=$sortieRepository->findByCampus([$campus], [nom=> 'DESC'], 30, 0);


        return $this->render('sorties/list.html.twig', [
            'sorties' => $sorties]);



        $sorties=$sortieRepository->sortiesParTheme();


        return $this->render('sorties/listTheme.html.twig', [
            'sorties' => $sorties,
        ]);
    }*/


    /**
     * @Route("/details/{id}", name="detail")
     */
    public function detailSortie(int $id, Request $request, EntityManagerInterface $entityManager,UserRepository $userRepository, SortieRepository $sortieRepository): Response
    {
       $sortie = $sortieRepository->find($id);

        /** @var \App\Entity\User $user *///pour que le user soit bien un objet App/Entity/User et pas un UserInterface
        $user = $this->getUser();

       $inscriptionSortieForm = $this->createForm(InscriptionSortieFormType::class, $sortie);
       $inscriptionSortieForm->handleRequest($request);
       if ($inscriptionSortieForm->isSubmitted() && $inscriptionSortieForm->isValid()){
           $sortie->addParticipant($user);
           $user->addSortiesParticipee($sortie);
           $entityManager->persist($user);
           $entityManager->persist($sortie);
           $entityManager->flush();

           $this->addFlash('success', "Vous êtes bien inscrit à cette sortie");
           return $this->redirectToRoute('sorties_detail',['id' => $id]);
       }


        return $this->render('sorties/detail.html.twig',
            [
                "sortie"=>$sortie,
                'inscriptionSortieForm'=>$inscriptionSortieForm->createView()
            ]);
    }

    /**
     * @Route("/create", name="create")
     */

    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();

            $sortieForm=$this->createForm(SortieType::class, $sortie);

            $sortieForm->handleRequest($request);

            if ($sortieForm->isSubmitted() && $sortieForm->isValid()){

                $sortie->setDateAnnonce(new \DateTime());
                $sortie->setHeureAnnonce(new \DateTime());


$entityManager->persist($sortie);
$entityManager->flush();

$this->addFlash('success', 'Sortie ajoutée avec succés');

return $this->redirectToRoute('sorties_list');
    }

    return  $this -> render ('sorties/create.html.twig', ['sortieForm'=>$sortieForm->createView()]);


    }


    }
