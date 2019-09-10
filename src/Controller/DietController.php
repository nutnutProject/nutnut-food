<?php

namespace App\Controller;

use App\Entity\Diet;
use App\Form\DietType;
use App\Repository\CategoryRepository;
use App\Repository\DietRepository;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/diet")
 */
class DietController extends AbstractController
{
    /**
     * @Route("/{slug}/{page}", name="diet_show")
     * 
     * Route servant à afficher les tags en même temps que les catégories et recettes
     */
    public function dietShow(Request $request, Diet $diet, DietRepository $dietRepository, CategoryRepository $categoryRepository, RecetteRepository $recetteRepository ,$page=1)
    {
        $request = isset($_GET["query"]) ? trim($_GET["query"]) : null;

        if ($request == null){
        // Permet d'avoir les recettes par catégories
            $recettes = $diet->getRecettes();
        } else {
            $recettes = $recetteRepository->findDietRecettesByRequest($request);
        }
        //Permet d'avoir les recettes par tag


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

        $diets = $dietRepository->findAll();

        //Récupère les catégories
        $categories = $categoryRepository->findAll();

        return $this->render('view/list.html.twig', [
            'recettes'=> $recette,
            'categories' => $categories,
            'diets' => $diets,
            'current_diet' => $diet,
            'current_category' => false,
            'current_page' => $page,
            'max_pages' => $max_pages,
        ]);
    }
}