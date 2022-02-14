<?php

namespace App\Controller;

use App\Data\SearchSortiesData;
use App\Form\SortieSearchType;
use App\Repository\SortieRepository;
use phpDocumentor\Reflection\Types\This;
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
        $data= new SearchSortiesData();
        $sortieChoixForm = $this->createForm(SortieSearchType::class, $data);
        $sortieChoixForm->handleRequest($request);

        $sorties = $sortieRepository->findNonArchivees();

        if ($sortieChoixForm->isSubmitted() &&  $sortieChoixForm->isValid()) {
            $sorties = $sortieRepository->findSearch($data, $this->getUser());


//            return $this->redirectToRoute('sorties_select', ['sorties' => $sorties]);

        }

        return $this->render (
            '/main/home.html.twig', [
                'sorties' => $sorties,
                'sortieChoixForm' => $sortieChoixForm->createView(),

            ]
        );

    }


}