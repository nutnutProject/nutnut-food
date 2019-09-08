<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\User;
use App\Form\RecetteType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\RecetteRepository;

use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{   

    /**
     * @Route ("/moncompte/{id}/", name="user")
     */
    public function userShow(User $user)
    {
        if ($this->getUser())
        {
            if ($this->getUser() != $user)
            {
                $user = $this->getUser();
            }
    
            return $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
        }
        return $this->redirectToRoute('home');
    }


    /**
     * @Route ("/moncompte/{id}/mes-recettes/{page}", name="user_recettes", requirements={"page"="\d+"})
     */
    public function userRecette(User $user, RecetteRepository $recetteRepository, $page = 1)
    {
        if ($this->getUser())
        {
            if ($this->getUser() != $user)
            {
                $user = $this->getUser();
            }

            $recettes = $recetteRepository->findBy(['user'=>$user]);

            $max_pages= ceil(count($recettes)/6);
            $debut = ($page -1 )*6;
            $fin = $debut+6;
            if ($page * 6 > count($recettes))
            {
                $fin = count($recettes);
            }
            for ($i = $debut; $i < $fin; $i++)
            {
                $recette[] = $recettes[$i];
            }

            return $this->render('user/user_mes_recettes.html.twig', [
                'user' => $user,
                'recettes' => $recette,
                'current_page' => $page,
                'max_pages' => $max_pages,
            ]);
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/moncompte/{id}/mes-recettes/new", name="recette_new", methods={"GET","POST"})
     */
    public function newRecette(User $user, Request $request): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser() != $user)
            {
                $user = $this->getUser();
            }

            $recette = new Recette();
            $form = $this->createForm(RecetteType::class, $recette);
            $form->handleRequest($request);
           

            if ($form->isSubmitted() && $form->isValid()) {

                foreach ($recette->getDiet() as $value) {
                    dd($value);
                }

                $recette->setUser($user);
                $recette->setNote(0);
                $recette->setCreationDate(new \DateTime());
                $recette->setValidate(true);
                
                // Enregistrement du ou des régimes alimentaire



                // Enregistrement de la recette
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($recette);
                $entityManager->flush();

                return $this->redirectToRoute('user_recettes',array('id' => $user->getId()));
            }

            return $this->render('recette/new.html.twig', [
                'user' => $user,
                'recette' => $recette,
                'form' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/moncompte/{id}/mes-recettes/{slug}/edit", name="recette_edit", methods={"GET","POST"})
     */
    public function editRecette(Request $request, User $user, Recette $recette): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser() != $user)
            {
                $user = $this->getUser();
            }
            
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
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/moncompte/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        if ($this->getUser())
        {
            if ($this->getUser() != $user)
            {
                $user = $this->getUser();
            }

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
        return $this->redirectToRoute('home');
    }
}
