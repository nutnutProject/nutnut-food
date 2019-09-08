<?php

namespace App\Controller;

use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index()
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    public function searchBar()
    {
       $form = $this->createFormBuilder(null) 
        ->add('titre', TextType::class, [
            'attr' => [
                'class' => 'form-control form-control-lg mr-sm-2 center',
                'placeholder' => 'Recercher une recette'
            ]
        ])
        ->add('rechercher', SubmitType::class, [
            'attr' => [
                'class' => 'btn blue-gradient btn-rounded  btn-lg my-0'
            ]
        ])
        ->getForm();

        return $this->render('search/searchbar.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/recherche", name="research")
     */
    public function titleRequest(Request $request, RecetteRepository $recetteRepository)
    {

    }
}
