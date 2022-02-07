<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    /**
     * @Route("/sorties", name="sorties_list")
     */
    public function list(SortieRepository $sortieRepository): Response
    {
        $sorties=$sortieRepository->findAll();


        return $this->render('/sorties/list.html.twig', [
            'sorties' => $sorties,
        ]);
    }
/*
    /**
     * @Route("/sorties/detail/{id}", name="sorties_detail")
     */
  /*  public function detail(int $id, SortieRepository $sortieRepository): Response
 /*   {

       $sortie = $sortieRepository->find($id);
        return $this->render('sorties/detail.html.twig',
            ["sortie"=>$sortie]);
    }*/

    /**
     * @Route("/sorties/create", name="sorties_create")
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

return $this->redirectToRoute('sorties/list.html.twig');
    }

    return  $this -> render ('sorties/create.html.twig', ['sortieForm'=>$sortieForm->createView()]);


    }


}
