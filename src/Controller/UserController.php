<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\GroupeRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Gestion upload images
            ///** @var UploadedFile $image */
            $image = $form->get('images')->getData();

            if(!empty($image))
            {
                $fileName = $fileUploader->upload($image);

                $photo = new Photo();
                $photo->setChemindd($fileName);
                $user->setNewProfilePicture($photo);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user, Request $request,
                         GroupeRepository $groupeRepository,
                         EntityManagerInterface $entityManager): Response
    {

        if(!empty($groupe = $request->get('quitter_groupe'))) {
            $groupe = $groupeRepository->find($groupe);
            $user->removeGroupe($groupe);
            $this->addFlash('success', "Groupe de {$groupe->getProprietaire()} quitte !");
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET", "POST"})
     */
    public function edit(
                            Request $request,
                            User $user,
                            EntityManagerInterface $entityManager,
                            UserPasswordHasherInterface $userPasswordHasher,
                            FileUploader $fileUploader
                        ): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $selfUser = $this->getUser();
        if(!$selfUser->getIsAdmin()) {
            if($selfUser->getId() !== (int)$request->get('id')) {
                throw $this->createAccessDeniedException('Non autorisé');
            }
        };

        if ($form->isSubmitted() && $form->isValid()) {
            $oldMDP = $form->get('oldMDP')->getData();
            $newMDP = $form->get('newMDP')->getData();

            if(!empty($oldMDP) && !empty($newMDP)) {
                $valid = $userPasswordHasher->isPasswordValid($user, $oldMDP);
                if ($valid) {
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('newMDP')->getData()
                        )
                    );
                } else {
                    $form->addError(new FormError('Mot de passe invalide !'));
                    goto fail;
                }
            }

            $image = $form->get('images')->getData();
            if(!empty($image))
            {
                $fileName = $fileUploader->upload($image);
                $photo = new Photo();
                $photo->setChemindd($fileName);
                $user->setNewProfilePicture($photo);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Compte modifié !');

            return $this->redirectToRoute('user_show', ['id'=>$user->getId()], Response::HTTP_SEE_OTHER);
        }

        fail:
        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
