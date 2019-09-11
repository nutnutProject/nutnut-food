<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\Note;
use App\Entity\User;
use App\Entity\UserRequest;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\CategoryRepository;
use App\Repository\DietRepository;
use App\Repository\RecetteRepository;
use PhpParser\Builder\Property;
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
    public function list(Request $request, RecetteRepository $recetteRepository, CategoryRepository $categoryRepository, DietRepository $dietRepository, $page = 1)
    {
        //Récupère dans request les données envoyées dans le formulaire de recherche
        $query = $request->query->get('query');
       

        if ($request == null){
         // Trouver toutes les recettes
            $recettes = $recetteRepository->findValidateOnlineRecettes();
        } else {
            $recettes = $recetteRepository->findByRequest($query);    
        }

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
        $user = $this->getUser();

        if ($user)
        {
            // Ajout du commentaire et de la note dans la table note
            $note = new Note();
            $note->setCommentaire($request->request->get('commentaire'));
            $note->setNote($request->request->get('note'));
            $note->setValidate(false);
            $note->setRecette($recette);
            $note->setUser($user);
            $note->setCreationDate(new \DateTime());

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
        }
        return $this->redirectToRoute("recettes_list");
        
    }


    /**
     * @Route("/recettes/{slug}", name="recette_show")
     */
    public function show(Recette $recette, Request $request, \Swift_Mailer $mailer)
    {
        $recette_apprise = 0;
        $user = $this->getUser();

        // Récupération des commentaires
        $noteRepository = $this->getDoctrine()->getRepository(Note::class);
        $notes = $noteRepository->findBy(['recette' => $recette]);

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
            'user' => $user,
            'recette' => $recette,
            'recette_apprise' => $recette_apprise,
            'notes' => $notes,
        ]);
    }

    /**
     * @Route("/fooder/{firstname}-{id}/{page}", name="fooder_show")
     * 
     * permet d'aller sur le profil d'un fooder
     */
    public function showFooder(User $user, $id, $page=1)
    {
        // Récupération des recettes de l'utilisateur
        $recetteRepository = $this->getDoctrine()->getRepository(Recette::class);
        $recettes = $recetteRepository->findBy(['user' => $id]);

        // Pour chaque recette de l'utilisateur, on récupère les commentaires
        foreach ($recettes as $recette) {
            //dd($recette->getId());
            $noteRepository = $this->getDoctrine()->getRepository(Note::class);
            $note = $noteRepository->findBy(['recette' => $recette->getId()]);           
            if ($note)
            {
                $notes[] = $note[0];
            }
        }

        $max_pages= ceil(count($recettes)/8);
        $debut = ($page -1 )*8;
        $fin = $debut+8;
        if ($page * 8 > count($recettes))
        {
            $fin = count($recettes);
        }
        $recette=[];
        for ($i = $debut; $i < $fin; $i++)
        {
            $recette[] = $recettes[$i];
        }
        return $this->render('view/show_fooder.html.twig', [
            'recettes' => $recette,
            'user' => $user,
            'max_pages' => $max_pages,
            'current_page' => $page,
            'notes' => $notes,
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
     * @Route("/mentionslegales", name="mentionslegales")
     * 
     */
    public function mention()
    {
        return $this->render('view/mentions.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     * 
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $contact = New Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        $email = $contact->getEmail();
        $sujet = $contact->getSujet();
        $message = $contact->getMessage();

        if ($this->getUser())
        {
            $email = $this->getUser()->getUsername();
        }

        if ($form->isSubmitted() && $form->isValid()){  

            $mail = (new \Swift_Message($sujet))
                ->setFrom($email)
                ->setTo('contact@nutnutfood.fr')
                ->setBody($message);

            $mailer->send($mail);
            
            $this->addFlash('success', 'Votre message a bien été envoyé');

            return $this->redirectToRoute('home');
        }

        return $this->render('view/contact.html.twig',[
            'form' => $form->CreateView(),
            'userEmail' => $email,
        ]);
    }











}
    
