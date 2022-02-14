<?php

namespace App\Controller\AdminCruds;

use App\Entity\Ville;
use App\Form\CRUDS_Admin\VilleCrudType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/crud/ville")
 */
class CrudVilleController extends AbstractController
{
    /**
     * @Route("/", name="crud_ville_index", methods={"GET"})
     */
    public function index(VilleRepository $villeRepository): Response
    {
        return $this->render('admin/crud_ville/index.html.twig', [
            'villes' => $villeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="crud_ville_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ville = new Ville();
        $form = $this->createForm(VilleCrudType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            return $this->redirectToRoute('crud_ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/crud_ville/new.html.twig', [
            'ville' => $ville,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_ville_show", methods={"GET"})
     */
    public function show(Ville $ville): Response
    {
        return $this->render('admin/crud_ville/show.html.twig', [
            'ville' => $ville,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_ville_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VilleCrudType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('crud_ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/crud_ville/edit.html.twig', [
            'ville' => $ville,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_ville_delete", methods={"POST"})
     */
    public function delete(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ville->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ville);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_ville_index', [], Response::HTTP_SEE_OTHER);
    }
}
