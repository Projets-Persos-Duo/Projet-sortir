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
     * @Route("/details/{id}", name="detail")
     */
    public function detailSortie(int $id, SortieRepository $sortieRepository): Response
    {

       $sortie = $sortieRepository->find($id);
        return $this->render('sorties/detail.html.twig',
            ["sortie"=>$sortie]);
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
