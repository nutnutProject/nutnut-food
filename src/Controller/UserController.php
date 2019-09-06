<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\User;
use App\Form\RecetteType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    /**
     * @Route ("/moncompte/{id}/mes-recettes", name="user_recettes")
     */
    public function userRecette(User $user)
    {
        return $this->render('user/user_mes_recettes.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/moncompte/{id}/mes-recettes/new", name="recette_new", methods={"GET","POST"})
     */
    public function newRecette(User $user, Request $request): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recette);
            $entityManager->flush();

            return $this->render('user/user_mes_recettes.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('recette/new.html.twig', [
            'user' => $user,
            'recette' => $recette,
            'form' => $form->createView(),
        ]);
    }

        /**
     * @Route("/moncompte/{id}/mes-recettes/{slug}/edit", name="recette_edit", methods={"GET","POST"})
     */
    public function editRecette(Request $request, User $user, Recette $recette): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->render('user/user_mes_recettes.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('recette/edit.html.twig', [
            'user' => $user,
            'recette' => $recette,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/moncompte/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
