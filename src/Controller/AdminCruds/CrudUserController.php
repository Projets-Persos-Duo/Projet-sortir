<?php

namespace App\Controller\AdminCruds;

use App\Entity\User;
use App\Form\CRUDS_Admin\UserCrudType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/crud/user")
 */
class CrudUserController extends AbstractController
{
    /**
     * @Route("/", name="crud_user_index", methods={"GET"})
     */
    public function index(Request $request, UserRepository $userRepository): Response
    {
        if(!empty($request->get('exporter'))) {
            $users = $userRepository->findAll();
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $row = 1;
            foreach ($users as $user) {
                $sheet->setCellValueByColumnAndRow(1, $row, $user->getUserIdentifier());
                $sheet->setCellValueByColumnAndRow(2, $row, json_encode($user->getRoles()));
                $sheet->setCellValueByColumnAndRow(3, $row, $user->getPassword());
                $sheet->setCellValueByColumnAndRow(4, $row, $user->getEmail());
                $sheet->setCellValueByColumnAndRow(5, $row, $user->getFamilyName());
                $sheet->setCellValueByColumnAndRow(6, $row, $user->getFirstName());
                $sheet->setCellValueByColumnAndRow(7, $row, $user->getTelephone());
                $sheet->setCellValueByColumnAndRow(8, $row, $user->getIsActive());
                $row++;
            }

            $name = \tempnam(sys_get_temp_dir(), 'csv');
            $file = fopen($name, 'w+b');
            $writer = new Csv($spreadsheet);
            $writer->save($file);
            return $this->file($name, 'users_export.csv');
        }
        return $this->render('admin/crud_user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="crud_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request,
                        UserPasswordHasherInterface $userPasswordHasher,
                        EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserCrudType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password_en_clair = $user->getPassword();
            $password_chiffre = $userPasswordHasher->hashPassword($user, $password_en_clair);
            $user->setPassword($password_chiffre);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('crud_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/crud_user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/crud_user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="crud_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserCrudType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('crud_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/crud_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="crud_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('crud_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
