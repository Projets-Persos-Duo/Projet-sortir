<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Repository\CampusRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    /**
     * @Route("/campus", name="campus_list")
     */
    public function list(CampusRepository $campusRepository): Response
    {
        $campus = $campusRepository->findAll();

        return $this->render('/campus/gererCampus.html.twig',['campus'=>$campus]);
    }

    /**
     * @Route("/campus/create", name="campus_create")
     */

    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $campus = new Campus();

        $campusForm = $this->createForm(CampusType::class, $campus);
        /*$campusForm->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()){
            $entityManager->persist($campus);
            $entityManager->flush();

            //message d'info à l'utilisateur pour la redirection, dans le base.html.twig il faudra l'afficher
            $this->addFlash('success', 'Campus added!');
            return $this->redirectToRoute('campus_list');
        }*/

        return $this->render('campus/gererCampus.html.twig', [
            'campusForm' => $campusForm->createView()
        ]);
    }

    /*public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $serie = new Serie();
        //remplissage de la propriété de l'entité automatique pour dateCreation, pas à compléter via le formulaire
        $serie->setDateCreated(new \DateTime());//peut aussi être fait dans l'entité

        $serieForm = $this->createForm(SerieType::class, $serie);

        //traiter le formulaire
        $serieForm->handleRequest($request);//symfony prend les données soumises et les injecte dans $serie

        if ($serieForm->isSubmitted() && $serieForm->isValid()){
            $entityManager->persist($serie);
            $entityManager->flush();

            //message d'info à l'utilisateur pour la redirection, dans le base.html.twig il faudra l'afficher
            $this->addFlash('success', 'Serie added!');
            return $this->redirectToRoute('serie_details', ['id' => $serie->getId()]);
        }

        //passer l'objet formulaire à twig, en paramètre du render
        return $this->render('serie/create.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }*/
}