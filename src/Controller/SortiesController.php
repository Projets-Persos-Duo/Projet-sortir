<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Sortie;
use App\Form\SortieSearchForm;
use App\Entity\User;
use App\Form\DesinscriptionSortieFormType;
use App\Form\InscriptionSortieFormType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
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
        $sortieChoixForm = $this->createForm(SortieSearchForm::class, $data);
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
     * @Route("/liste-archivees", name="list_archivees")
     */
    public function listeSortiesArchivees(SortieRepository $sortieRepository): Response
    {
        $sorties=$sortieRepository->findArchivees();

        return $this->render('sorties/list.html.twig', [
            'sorties' => $sorties,
            'entete' => 'Les archives'
        ]);
    }



    /**
     * @Route("/details/{id}", name="detail")
     */
    public function detailSortie(int $id, Request $request, EntityManagerInterface $entityManager,UserRepository $userRepository, SortieRepository $sortieRepository): Response
    {
       $sortie = $sortieRepository->find($id);

        /** @var User $user *///pour que le user soit bien un objet App/Entity/User et pas un UserInterface
        $user = $this->getUser();

        $inscriptionSortieForm = $this->createForm(InscriptionSortieFormType::class, $sortie);
        $inscriptionSortieForm->handleRequest($request);

        $desinscriptionSortieForm = $this->createForm(DesinscriptionSortieFormType::class, $sortie);
        $desinscriptionSortieForm->handleRequest($request);

        if ($inscriptionSortieForm->isSubmitted() && $inscriptionSortieForm->isValid()){
            $sortie->addParticipant($user);
            $user->addSortiesParticipee($sortie);
            $entityManager->persist($user);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', "Vous êtes bien inscrit à cette sortie");
            return $this->redirectToRoute('sorties_detail',['id' => $id]);
        }

        if ($desinscriptionSortieForm->isSubmitted() && $desinscriptionSortieForm->isValid()){
            $sortie->removeParticipant($user);
            $user->removeSortiesParticipee($sortie);
            $entityManager->persist($user);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', "Désincription enregistrée");
            return $this->redirectToRoute('sorties_detail',['id' => $id]);
        }

        return $this->render('sorties/detail.html.twig', [
            "sortie"=>$sortie,
            'inscriptionSortieForm'=>$inscriptionSortieForm->createView(),
            'desinscriptionSortieForm'=>$desinscriptionSortieForm->createView()
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

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        SortieRepository $sortieRepository
    ): Response
    {
        $sortie = $sortieRepository->find($id);
        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);

        if($this->getUser()->getId() != $sortie->getOrganisateur()->getId()) {
            throw $this->createAccessDeniedException('Non autorisé');
        }

        if($sortie->getDateCloture() > new \DateTime('now')) {
            $this->addFlash(
                'danger',
                'La sortie ne peut plus etre modifiée après la date de cloture !'
            );
            return $this->redirectToRoute(
                'sorties_detail',
                ['id'=>$sortie->getId()],
                Response::HTTP_SEE_OTHER
            );

        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Sortie modifiée !');

            return $this->redirectToRoute(
                'sorties_detail',
                ['id'=>$sortie->getId()],
                Response::HTTP_SEE_OTHER
            );

        }

        fail:
        return $this->renderForm('sorties/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);

    }

}
