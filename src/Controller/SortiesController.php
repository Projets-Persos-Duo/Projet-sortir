<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Sortie;
use App\Form\SearchForm;
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
     * @Route("/findSelect", name="findSelect")
     */
    public function FindSortieBySelection(Request $request, SortieRepository $sortieRepository): Response

    {

        $data= new SearchData();
        $sortieChoixForm = $this->createForm(SearchForm::class, $data);
        $sortieChoixForm->handleRequest($request);
        $sorties = $sortieRepository->findAll();


        if ($sortieChoixForm->isSubmitted() &&  $sortieChoixForm->isValid()) {
            $sorties = $sortieRepository->findSearchCampus($data);


            return $this->redirectToRoute('sorties_select', ['sorties'=>$sorties]);

        }


        return $this->renderForm('sorties/accueil.html.twig', [
            'sorties' => $sorties,
            'sortieChoixForm' => $sortieChoixForm,
        ]);

    }

    /**
     * @Route("/select", name="select")
     */

    public function listeSelectSorties(SortieRepository $sortieRepository, Request $request): Response
    {
        //TODO / FINIR CETTE FONCTION QUI LISTE LES SORTIES SELON LEUR SELECTION
//findby??

//$request->get('');

//        $sortie=$sortieRepository->findBy(
//            ['sortie' => '$nom'],
//            ['nom' => 'desc'],
//           30,
//           0);
        $sorties=new Sortie();
//        dump($sorties);

        return $this->render('sorties/listSelect.html.twig', [
            'sorties' => $sorties,
        ]);


    }



    /**
     * @Route("/list", name="list")
     */
    public function listeAllSorties(SortieRepository $sortieRepository): Response
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
        return $this->render('sorties/detail.html.twig', [
            "sortie"=>$sortie,
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

    return  $this -> render ('sorties/create.html.twig', [
        'sortieForm'=>$sortieForm->createView()]);


    }


    }
