<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\Note;
use App\Entity\User;
use App\Entity\UserRequest;
use App\Repository\CategoryRepository;
use App\Repository\DietRepository;
use App\Repository\RecetteRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/** 
 * Controller servant à afficher et lister les recettes, les catégories et les users 
 */

class ViewController extends AbstractController
{
    /**
     * @Route("/recettes/{page}", name="recettes_list", requirements={"page"="\d+"})
     */
    public function list(RecetteRepository $recetteRepository, CategoryRepository $categoryRepository, DietRepository $dietRepository, $page = 1)
    {
        // Trouver toutes les recettes
        $recettes = $recetteRepository->findAll();

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

        // Trouver toutes les categories
        $categories = $categoryRepository->findAll();
        //Trouver tous les diets
        $diets = $dietRepository->findAll();

        return $this->render('view/list.html.twig', [
            'recettes' => $recette,
            'categories' => $categories,
            'diets' => $diets,
            'current_category' => false,
            'current_diet' => false,
            'current_page' => $page,
            'max_pages' => $max_pages,
        ]);
    }

    /**
     * @Route("/noter_la_recette/{slug}", name="add_note")
     */
    public function addNote(Recette $recette, Request $request)
    {
        
        // Ajout du commentaire et de la note dans la table note
        $note = new Note();
        $note->setCommentaire($request->request->get('commentaire'));
        $note->setNote($request->request->get('note'));
        $note->setValidate(false);
        $note->setRecette($recette);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($note);
        $entityManager->flush();

        // Calcul de la note moyenne de la recette
        $noteRepository = $this->getDoctrine()->getRepository(Note::class);
        $notes = $noteRepository->findBy(
            ['recette' => $recette],
        );
        $moyenne = 0;
        foreach ($notes as $note) {
            $moyenne += $note->getNote();
        }
        $moyenne = $moyenne/count($notes);

        // Ajout de la moyenne dans la recette
        $recette->setNote($moyenne);
        $entityManager->persist($recette);
        $entityManager->flush();

        return $this->redirectToRoute("recettes_list");
    }


    /**
     * @Route("/recettes/{slug}", name="recette_show")
     */
    public function show(Recette $recette, Request $request, \Swift_Mailer $mailer)
    {
        $recette_apprise = 0;
        $user = $this->getUser();

        //Si user connecté, récupération de l'id
        if ($user)
        {
            if ($request->isMethod('POST'))
            {
                $name = $request->request->get('name');
                $email = $request->request->get('email');
                $subject = $request->request->get('subject');
                $message = $request->request->get('message');

                $message = "Vous avez reçu un message de la part de ".$name."<br>Au sujet de la recette : ".$recette->getTitle()."<br>Voici le contenu du message :<br>".$message;

                //Récupération de l'adresse email du fooder
                $userRepository = $this->getDoctrine()->getRepository(User::class);
                $userFooder = $userRepository->findOneBy([
                    'id' => $recette->getUser(),
                ]);

                $email_fooder = $userFooder->getUsername();

                // Ajout dans la tablee UserRequest
                $userRequest = new UserRequest();
                $userRequest->setMessage($message);
                $userRequest->setObject($subject);
                $userRequest->setRecette($recette);
                $userRequest->setUser($user);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($userRequest);
                $entityManager->flush();

                // Envoi du mail de confiration de souscription
                $message = (new \Swift_Message($subject))
                    ->setFrom($email)
                    ->setTo($email_fooder)
                    ->setBody($message);

                $mailer->send($message);

                $this->addFlash('success', 'Votre message a bien été envoyé.');
            }

            $userRequestRepository = $this->getDoctrine()->getRepository(UserRequest::class);

            // On cherche si la recette est déjà apprise
            $userRequest = $userRequestRepository->findOneBy([
                'recette' => $recette,
                'user' => $user,
                ]);
            
            if ($userRequest){
                $recette_apprise = 1;
            }
        }

        return $this->render('view/show_recette.html.twig', [
            'recette' => $recette,
            'recette_apprise' => $recette_apprise,
        ]);
    }

    /**
     * @Route("/fooder/{firstname}-{id}", name="fooder_show")
     * 
     * permet d'aller sur le profil d'un fooder
     */
    public function showFooder(User $user)
    {
        return $this->render('view/show_fooder.html.twig', [
            'user' => $user,
        ]);
    }



   /**
     * @Route("/faq", name="faq")
     * 
     */
    public function faq()
    {
        return $this->render('view/faq.html.twig');
    }

       /**
     * @Route("/cgu", name="cgu")
     * 
     */
    public function cgu()
    {
        return $this->render('view/cgu.html.twig');
    }

       /**
     * @Route("/cgv", name="cgv")
     * 
     */
    public function cgv()
    {
        return $this->render('view/cgv.html.twig');
    }











}
    
