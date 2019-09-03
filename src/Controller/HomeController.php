<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\RecetteRepository;
use App\Entity\Recette;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategoryRepository $categoryRepository, RecetteRepository $recetteRepository)
    {
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);

        $categories = $categoryRepository->findCategory();

        return $this->render('home/home.html.twig', [
           'categories' => $categories,
        ]);
    }
}
