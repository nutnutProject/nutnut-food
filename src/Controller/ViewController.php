<?php

namespace App\Controller;

use App\Entity\Recette;
use \App\Entity\User;
use App\Entity\UserRequest;
use App\Repository\CategoryRepository;
use App\Repository\DietRepository;
use App\Repository\RecetteRepository;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/** 
 * Controller servant à afficher et lister les recettes, les catégories et les users 
 */

class ViewController extends AbstractController
{
    /**
     * @Route("/recettes", name="recettes_list")
     */
    public function list(RecetteRepository $recetteRepository, CategoryRepository $categoryRepository, DietRepository $dietRepository)
    {
        // Trouver toutes les recettes
        $recettes = $recetteRepository->findAll();
        // Trouver toutes les categories
        $categories = $categoryRepository->findAll();
        //Trouver tous les diets
        $diets = $dietRepository->findAll();



        return $this->render('view/list.html.twig', [
            'recettes' => $recettes,
            'categories' => $categories,
            'diets' => $diets,
            'current_category' => false,
            'current_diet' => false,
        ]);
    }

    /**
     * @Route("/recettes/{slug}", name="recette_show")
     */
    public function show(Recette $recette)
    {
        $recette_apprise = 0;
        $user = $this->getUser();

        //Si user connecté, récupération de l'id
        if ($user)
        {
            $userRequestRepository = $this->getDoctrine()->getRepository(UserRequest::class);

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
     * @Route("/fooder/{firstname}.{id}", name="fooder_show")
     * 
     * permet d'aller sur le profil d'un fooder
     */
    public function showFooder(User $user)
    {
        return $this->render('view/show_fooder.html.twig', [
            'user' => $user,
        ]);
    }

}
    
