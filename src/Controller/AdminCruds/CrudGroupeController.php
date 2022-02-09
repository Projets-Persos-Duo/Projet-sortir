<?php

namespace App\Controller\AdminCruds;

use App\Entity\Groupe;
use App\Form\CRUDS_Admin\GroupeCrudType;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/crud/groupe")
 */
class CrudGroupeController extends AbstractController
{
    /**
     * @Route("/", name="crud_groupe_index", methods={"GET"})
     */
    public function index(GroupeRepository $groupeRepository): Response
    {
        return $this->render('admin/crud_groupe/index.html.twig', [
            'groupes' => $groupeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="crud_groupe_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $groupe = new Groupe();
        $form = $this->createForm(GroupeCrudType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($groupe);
            $entityManager->flush();

            return $this->redirectToRoute('crud_groupe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_groupe/new.html.twig', [
            'groupe' => $groupe,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_groupe_show", methods={"GET"})
     */
    public function show(Groupe $groupe): Response
    {
        return $this->render('admin/crud_groupe/show.html.twig', [
            'groupe' => $groupe,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_groupe_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Groupe $groupe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GroupeCrudType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('crud_groupe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_groupe/edit.html.twig', [
            'groupe' => $groupe,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_groupe_delete", methods={"POST"})
     */
    public function delete(Request $request, Groupe $groupe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$groupe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($groupe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_groupe_index', [], Response::HTTP_SEE_OTHER);
    }
}
