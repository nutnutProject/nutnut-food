<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\DietRepository;
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
     * @Route("/category/{slug}", name="category_show")
     * 
     * Route servant a afficher les catégories en même temps que les recettes
     */
    public function categoryShow(Category $category,
        CategoryRepository $categoryRepository, DietRepository $dietRepository) 
        {
        // Permet d'avoir les recettes par catégories
        $recettes = $category->getRecettes();
        $categories = $categoryRepository->findAll();
        $diets = $dietRepository->findAll();

        return $this->render('view/list.html.twig', [
            'recettes' => $recettes,
            'categories' => $categories,
            'diets' => $diets,
            // Permet de retrouver la route courante
            'current_category' => $category,
            'current_diet' => false,
            
        ]);
    }

}
