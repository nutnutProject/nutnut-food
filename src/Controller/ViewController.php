<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Repository\CategoryRepository;
use App\Repository\DietRepository;
use App\Repository\RecetteRepository;
use App\Repository\UserRepository;
use \App\Entity\User;
use Directory;
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

        return $this->render('view/show_recette.html.twig', [
            'recette' => $recette,
        ]);
    }

}
    
