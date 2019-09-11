<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Diet;
use App\Entity\Recette;
use App\Repository\CategoryRepository;
use App\Repository\DietRepository;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class AjaxSearchController extends AbstractController
{

    /**
     * @Route("/ajax/recettes", name="ajax_search" )
     */
    public function ajaxBar(Request $request, RecetteRepository $recetteRepository, \Twig_Environment $templating)
    {


            if($request->get('query'))
            {
                $query = $request->get('query');
                $response = $recetteRepository->findByRequest($query);
                $output = $templating->render('search/search_list.html.twig', array(
                    'response' => $response,
                ));

                // '<ul class="dropdown-menu"
                //     style= "display:block;
                //     position:relative">';
                // foreach($response as $recette)
                // {
                //     $output .= '<li> <a href="#">' .$row->getTitle(). '</a> </li>';
                // }
                // $output .= '</ul>';

                return new JsonResponse( array(
                    'status' => true,
                    'content' => $output,
                ));
            }

            return new JsonResponse( array(
                'status' => false
            ));
        }
}


