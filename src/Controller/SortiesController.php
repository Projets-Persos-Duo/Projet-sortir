<?php

namespace App\Controller;

use App\Data\SearchSortiesData;
use App\Entity\Photo;
use App\Entity\Sortie;
use App\Form\AnnulationSortieFormType;
use App\Form\SortieSearchType;
use App\Entity\User;
use App\Form\DesinscriptionSortieFormType;
use App\Form\InscriptionSortieFormType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sorties", name="sorties_")
 */

class SortiesController extends AbstractController
{

//    /**
//     * @Route("/findSelect", name="findSelect")
//     */
//    public function FindSortieBySelection(Request $request, SortieRepository $sortieRepository): Response
//    {
//        $data= new SearchSortiesData();
//        $sortieChoixForm = $this->createForm(SortieSearchType::class, $data);
//        $sortieChoixForm->handleRequest($request);
//        $sorties = $sortieRepository->findAll();
//
//        if ($sortieChoixForm->isSubmitted() &&  $sortieChoixForm->isValid()) {
//            $sorties = $sortieRepository->findSearchCampus($data);
//            return $this->redirectToRoute('sorties_select', ['sorties'=>$sorties]);
//        }
//
//        return $this->renderForm('sorties/accueil.html.twig', [
//            'sorties' => $sorties,
//            'sortieChoixForm' => $sortieChoixForm,
//        ]);
//    }
//
//    /**
//     * @Route("/select", name="select")
//     */
//    public function listeSelectSorties(SortieRepository $sortieRepository, Request $request): Response
//    {
//        //TODO / FINIR CETTE FONCTION QUI LISTE LES SORTIES SELON LEUR SELECTION
//        // plus necessaire vu que MainController s'en occupe?
//        $sorties=new Sortie();
//
//        return $this->render('sorties/listSelect.html.twig', [
//            'sorties' => $sorties,
//        ]);
//    }


    /**
     *  Lié à _interface.html.twig
     * @Route("/list", name="list")
     */
    public function listeAllSorties(SortieRepository $sortieRepository,
                                    PaginatorInterface $paginator,
                                    Request $request
                                ): Response
    {

            $data=$sortieRepository->findAllTrie();
            //paginator va nous permettre de choisir le nombre
            // de sorties affichées par page (ici 6)
            $sorties = $paginator->paginate(
                $data,
                $request->query->getInt('page', 1),
                6
        );

        return $this->render('sorties/list.html.twig', [
            'sorties' => $sorties,
        ]);
    }


    /**
     * Lié à _interface.html.twig et à liste_sorties.html.twig
     * @Route("/liste-archivees", name="list_archivees")
     */
    public function listeSortiesArchivees(SortieRepository $sortieRepository,
                                        PaginatorInterface $paginator,
                                        Request $request): Response
    {
        $data=$sortieRepository->findArchivees();

        // paginator va nous permettre de choisir
        // le nombre de sorties affichées par page (ici 6)
        $sorties=$paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('sorties/list.html.twig', [
            'sorties' => $sorties,
            'entete' => 'Sorties archivées'
        ]);
    }

    /**
     * Lié à liste_sorties_miniatures.html.twig
     * @Route("/details/{id}", name="detail")
     */
    public function detailSortie(
        int $id, Request $request, EntityManagerInterface $entityManager,
        UserRepository $userRepository, SortieRepository $sortieRepository): Response
    {
       $sortie = $sortieRepository->find($id);

        /** @var User $user */
        //pour que le user soit bien un objet App/Entity/User et pas un UserInterface
        $user = $this->getUser();

        //Boutons pour s'inscrire ou se désinscrire (modifier ou annuler : sont directement sur le twig)
        $inscriptionSortieForm = $this->createForm(InscriptionSortieFormType::class, $sortie);
        $inscriptionSortieForm->handleRequest($request);

        $desinscriptionSortieForm = $this->createForm(DesinscriptionSortieFormType::class, $sortie);
        $desinscriptionSortieForm->handleRequest($request);

        if ($inscriptionSortieForm->isSubmitted() && $inscriptionSortieForm->isValid()){
//            $sortie->addParticipant($user); je crois que removeSortiesParticipee le fait, ivo
            $user->addSortiesParticipee($sortie);
            $entityManager->persist($user);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', "Vous êtes bien inscrit à cette sortie");
            return $this->redirectToRoute('sorties_detail',['id' => $id]);
        }

        if ($desinscriptionSortieForm->isSubmitted() && $desinscriptionSortieForm->isValid() ) {
            //$sortie->removeParticipant($user); je crois que removeSortiesParticipee le fait, ivo
            $user->removeSortiesParticipee($sortie);
            $entityManager->persist($user);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', "Désinscription enregistrée");
            return $this->redirectToRoute('sorties_detail',['id' => $id]);
        }

        //TODO: enlever l'utilisateur qui a un compte désactivé


        return $this->render('sorties/detail.html.twig', [
            "sortie"=>$sortie,
            'inscriptionSortieForm'=>$inscriptionSortieForm->createView(),
            'desinscriptionSortieForm'=>$desinscriptionSortieForm->createView()
        ]);
    }

    /* L'organisateur clique sur le bouton "créer une sortie"
        Lié à nav.html.twig */
    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        //pour que le user soit bien un objet App/Entity/User et pas un UserInterface
        $user = $this->getUser();

        $sortie = new Sortie();

            $sortieForm=$this->createForm(SortieType::class, $sortie);
            $sortieForm->handleRequest($request);

            if ($sortieForm->isSubmitted() && $sortieForm->isValid()){
                $sortie->setOrganisateur($user);
                $sortie->setHeureCloture(new DateTime('23:59:59'));
                $sortie->setDateAnnonce(new DateTime());
                $sortie->setHeureAnnonce(new DateTime());
                $entityManager->persist($sortie);
                $entityManager->flush();
            $this->addFlash('success', 'Sortie ajoutée avec succès');
            return $this->redirectToRoute('main_home');
        }

        return  $this -> render ('sorties/create.html.twig', [
            'sortieForm'=>$sortieForm->createView()]);
    }

    /* L'organisateur clique sur le bouton "modifier la sortie"
    lié à detail.html.twig */
    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader,
        SortieRepository $sortieRepository
    ): Response
    {
        $sortie = $sortieRepository->find($id);
        $form = $this->createForm(SortieType::class, $sortie);
        dump($sortie, $form);

        $form->handleRequest($request);

        $im = $form->get('photos');
        $image = $form->get('photos')->getData();
        if(!empty($image)) {
            $fileName = $fileUploader->upload($image);
            $photo = new Photo();
            $photo->setChemindd($fileName);
            $photo->addSorty($sortie);
            $photo->setIsProfilePicture(false);
            $photo->setUser($this->getUser());
            $entityManager->persist($photo);
        }
        if($this->getUser()->getId() != $sortie->getOrganisateur()->getId()) {
            throw $this->createAccessDeniedException('Non autorisé');
        }
        if($sortie->getDateCloture() < new DateTime('now')) {
            $this->addFlash(
                'danger',
                'La sortie ne peut plus être modifiée après la date de cloture !'
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

    /* L'organisateur clique sur le bouton "annuler la sortie"
     lié à detail.html.twig */
    /**
     * @Route("/annuler/{id}", name="annuler")
     */
    public function annuler( int $id, Request $request, EntityManagerInterface $entityManager, SortieRepository $sortieRepository):Response
    {
        $sortie = $sortieRepository->find($id);
        if($this->getUser()->getId() != $sortie->getOrganisateur()->getId() && !$this->getUser()->getIsAdmin()) {
            throw $this->createAccessDeniedException('Non autorisé');
        }

        $annulationSortieForm = $this->createForm(AnnulationSortieFormType::class, $sortie);
        $annulationSortieForm->handleRequest($request);

        if ($annulationSortieForm->isSubmitted() && $annulationSortieForm->isValid() && $sortie->getDateDebut() > new DateTime('now'))
        {
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'L\'annulation de la sortie a bien été enregistrée');

            return $this->redirectToRoute('sorties_detail',['id' => $id]);
        }

        return $this->render('sorties/annuler.html.twig', [
            "sortie"=>$sortie,
            'annulationSortieForm'=>$annulationSortieForm->createView(),
        ]);
    }

}
