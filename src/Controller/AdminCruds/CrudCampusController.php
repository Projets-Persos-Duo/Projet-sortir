<?php

namespace App\Controller\AdminCruds;

use App\Entity\Campus;
use App\Form\CRUDS_Admin\CampusCrudType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/crud/campus")
 */
class CrudCampusController extends AbstractController
{
    /**
     * @Route("/", name="crud_campus_index", methods={"GET"})
     */
    public function index(CampusRepository $campusRepository): Response
    {
        return $this->render('admin/crud_campus/index.html.twig', [
            'campuses' => $campusRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="crud_campus_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $campus = new Campus();
        $form = $this->createForm(CampusCrudType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($campus);
            $entityManager->flush();

            return $this->redirectToRoute('crud_campus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/crud_campus/new.html.twig', [
            'campus' => $campus,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_campus_show", methods={"GET"})
     */
    public function show(Campus $campus): Response
    {
        return $this->render('admin/crud_campus/show.html.twig', [
            'campus' => $campus,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_campus_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Campus $campus, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CampusCrudType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('crud_campus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/crud_campus/edit.html.twig', [
            'campus' => $campus,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_campus_delete", methods={"POST"})
     */
    public function delete(Request $request, Campus $campus, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$campus->getId(), $request->request->get('_token'))) {
            $entityManager->remove($campus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_campus_index', [], Response::HTTP_SEE_OTHER);
    }
}
