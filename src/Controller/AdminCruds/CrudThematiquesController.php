<?php

namespace App\Controller\AdminCruds;

use App\Entity\Thematiques;
use App\Form\CRUDS_Admin\ThematiquesCrudType;
use App\Repository\ThematiquesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/crud/thematiques")
 */
class CrudThematiquesController extends AbstractController
{
    /**
     * @Route("/", name="crud_thematiques_index", methods={"GET"})
     */
    public function index(ThematiquesRepository $thematiquesRepository): Response
    {
        return $this->render('admin/crud_thematiques/index.html.twig', [
            'thematiques' => $thematiquesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="crud_thematiques_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $thematique = new Thematiques();
        $form = $this->createForm(ThematiquesCrudType::class, $thematique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($thematique);
            $entityManager->flush();

            return $this->redirectToRoute('crud_thematiques_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_thematiques/new.html.twig', [
            'thematique' => $thematique,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_thematiques_show", methods={"GET"})
     */
    public function show(Thematiques $thematique): Response
    {
        return $this->render('admin/crud_thematiques/show.html.twig', [
            'thematique' => $thematique,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_thematiques_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Thematiques $thematique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ThematiquesCrudType::class, $thematique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('crud_thematiques_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('crud_thematiques/edit.html.twig', [
            'thematique' => $thematique,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_thematiques_delete", methods={"POST"})
     */
    public function delete(Request $request, Thematiques $thematique, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$thematique->getId(), $request->request->get('_token'))) {
            $entityManager->remove($thematique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_thematiques_index', [], Response::HTTP_SEE_OTHER);
    }
}
