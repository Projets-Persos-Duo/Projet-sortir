<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SortieSearchForm;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="main_home")
     */


    public function home(Request $request, SortieRepository $sortieRepository)
    {
        $data= new SearchData();
        $sortieChoixForm = $this->createForm(SortieSearchForm::class, $data);
        $sortieChoixForm->handleRequest($request);
        $sorties = $sortieRepository->findAll();


        if ($sortieChoixForm->isSubmitted() &&  $sortieChoixForm->isValid()) {
            $sorties = $sortieRepository->findSearchCampus($data);


            return $this->redirectToRoute('sorties_select', ['sorties' => $sorties]);

        }

        return $this->render (
            '/main/home.html.twig', [
                'sorties' => $sorties,
                'sortieChoixForm' => $sortieChoixForm->createView(),

            ]
        );

    }


}