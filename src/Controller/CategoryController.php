<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\DietRepository;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** 
 * @Route'("/category", name"category)
 */

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}/{page}", name="category_show")
     * 
     * Route servant a afficher les catégories en même temps que les recettes
     */
    public function categoryShow(Request $request, Category $category,
        CategoryRepository $categoryRepository, DietRepository $dietRepository, RecetteRepository $recetteRepository ,$page=1) 
        {
        //Récupère dans request les données envoyées dans le formulaire de recherche
        $query = isset($_GET["query"]) ? trim($_GET["query"]) : null;

        if ($request == null){
        // Permet d'avoir les recettes par catégories
            $recettes = $category->getRecettes();
        } else {
            $recettes = $recetteRepository->findCategoryRecettesByRequest($query, $category);
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

        $categories = $categoryRepository->findAll();
        $diets = $dietRepository->findAll();

        return $this->render('view/list.html.twig', [
            'recettes' => $recettes,
            'categories' => $categories,
            'diets' => $diets,
            // Permet de retrouver la route courante
            'current_category' => $category,
            'current_diet' => false,
            'current_page' => $page,
            'max_pages' => $max_pages,
        ]);
    }

}
