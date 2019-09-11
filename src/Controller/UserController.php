<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\User;
use App\Entity\Diet;
use App\Form\RecetteType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\RecetteRepository;
use App\Repository\UserRequestRepository;

use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class UserController extends AbstractController
{

    /**
     * @Route ("/moncompte", name="user")
     */
    public function userShow()
    {
        if ($this->getUser()) {

            return $this->render('user/show.html.twig', [
                'user' => $this->getUser(),
            ]);
        }
        return $this->redirectToRoute('home');
    }


    /**
     * @Route ("/moncompte/mes-recettes/{page}", name="user_recettes", requirements={"page"="\d+"})
     */
    public function userRecette(RecetteRepository $recetteRepository, $page = 1)
    {
        if ($this->getUser()) {
            $user = $this->getUser();

            $recettes = $recetteRepository->findBy(['user' => $user]);

            $max_pages = ceil(count($recettes) / 6);
            $debut = ($page - 1) * 6;
            $fin = $debut + 6;
            if ($page * 6 > count($recettes)) {
                $fin = count($recettes);
            }
            $recette = [];
            for ($i = $debut; $i < $fin; $i++) {
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
     * @Route("/moncompte/mes-recettes/new", name="recette_new", methods={"GET","POST"})
     */
    public function newRecette(Request $request): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser();

            $recette = new Recette();
            $form = $this->createForm(RecetteType::class, $recette);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $recette_id = $recette->getId();
                
                $file = $_FILES['recette'];

                // Traitement de l'image
                if (!preg_match("/\.(jpg|jpeg|gif|png)$/", $file['name']['photo'])) {
                    $errors['cover'] = "Le type de l'image n'est pas valide";
                }

                // Controle de la taille du fichier
                if ($file['size']['photo'] > 2000000) {
                    $errors['cover'] = "La taille de l'image est supérieur à 2Mo.";
                }

                //Récupération de l'extension d'origine
                if (empty($errors)) {
                    $pathinfo = pathinfo($file['name']['photo']);
                    $extension = $pathinfo['extension'];
                }

                // Définition du nom de fichier, celui-ci doit être unique
                $filename = uniqid();
                $filename .= "." . $extension;

                // Définition de l'emplacement du fichier
                $filepath = "img/recette/".$filename;

                // Déplacement du fichier dans le dossier "img/"
                copy($file['tmp_name']['photo'], $filepath);

                // Enregistrement du ou des régimes alimentaire
                foreach ($recette->getDiet() as $diet)
                {
                    // Ajouter dans la table recette_diet
                    $diet_id = $diet->getId();
                    $dietRepository = $this->getDoctrine()->getRepository(Diet::class);
                    $diet = $dietRepository->find($diet_id);
                    $recette->addDiet($diet);
                }

                $recette->setPhoto($filepath);
                $recette->setUser($user);
                $recette->setNote(0);
                $recette->setCreationDate(new \DateTime());
                $recette->setValidate(true);

                // Enregistrement de la recette
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($recette);
                $entityManager->flush();

                return $this->redirectToRoute('user_recettes', array('id' => $user->getId()));
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
     * @Route("/moncompte/mes-recettes/{id}/edit", name="recette_edit", methods={"GET","POST"})
     */
    public function editRecette(Request $request, Recette $recette): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser();

            $form = $this->createForm(RecetteType::class, $recette);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('user_recettes');
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
     * @Route("/moncompte/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser();

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

    /**
     * @Route("/moncompte/interests/{page}", name="user_interests")
     */
    public function interests(UserRequestRepository $userRequestRepository, $page = 1): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser();

            // Récupération des mes intérets, on récupère toutes les recettes interessées
            $userRequests = $userRequestRepository->findBy([
                'user' => $user,
            ]);
            $recettes = [];
            foreach ($userRequests as $userRequest) {
                $recetteRepository = $this->getDoctrine()->getRepository(Recette::class);
                $recettes[] = $recetteRepository->findBy(['id' => $userRequest->getRecette()->getId()]);
            }

            $max_pages = ceil(count($recettes) / 6);
            $debut = ($page - 1) * 6;
            $fin = $debut + 6;
            if ($page * 6 > count($recettes)) {
                $fin = count($recettes);
            }

            $recette = [];
            for ($i = $debut; $i < $fin; $i++)
            {
                $recette[] = $recettes[$i];
            }
            //dd($recette);
            return $this->render('user/interests.html.twig', [
                'recettes' => $recette,
                'user' =>  $user,
                'current_page' => $page,
                'max_pages' => $max_pages,
            ]);
        }
        return $this->redirectToRoute('home');
    }



    /**
     * @Route ("/moncompte/mod_mdp", name="user_mod_mdp")
     */
    public function mdp(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($this->getUser()) {
            $user = $this->getUser();
            if ($request->isMethod('POST')) {

                $old_pwd = $request->get('old_password');
                $new_pwd = $request->get('new_password');
                $new_pwd_confirm = $request->get('new_password_confirm');

                $checkPass = $passwordEncoder->isPasswordValid($user, $old_pwd);

                if ($checkPass === true) {
                    if ($new_pwd ==  $new_pwd_confirm) {

                        $new_pwd_encoded = $passwordEncoder->encodePassword($user, $new_pwd_confirm);
                        $user->setPassword($new_pwd_encoded);

                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->getRepository(User::class)->findBy(['id' => $user->getId()]);

                        $entityManager->flush();

                        $this->addFlash('success', 'Votre mot de passe est modifié, vous pouvez à présent vous connecter.');
                        return $this->redirectToRoute('app_logout');
                    }
                    else
                    {
                        $this->addFlash('danger', 'Les mots de passe saisis ne sont pas identique.');            
                        return $this->redirectToRoute('user_mod_mdp');
                    }
                }
                else
                {
                    $this->addFlash('alert', 'Le mot de passe saisi est erroné.');
                    return $this->redirectToRoute('user_mod_mdp');
                }
            }
            return $this->render('user/mod_mdp.html.twig',);
        }
        else
        {
            return $this->redirectToRoute('home');
        }
    }
}
    