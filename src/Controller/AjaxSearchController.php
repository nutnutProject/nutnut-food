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

class AjaxSearchController extends AbstractController
{
    /**
     * @Route("/ajax/search", name="ajax_search")
     */
    public function index()
    {
        return $this->render('ajax_search/index.html.twig', [
            'controller_name' => 'AjaxSearchController',
        ]);
    }

    /**
     * @Route("/recettes", name="recette_list" )
     */
    public function ajaxBar(Request $request, RecetteRepository $recetteRepository)
    {

            // if ($request != null){
            // $query = $request->query->get('query');

            // $recettes = $recetteRepository->findByRequest($query);

            // $recetteList = '<ul id="matchList">';

            $data = $request->get('query');
            $em =$this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT r
                FROM App\Entity\Recette r
                WHERE r.title LIKE :data
                ORDER BY r.title ASC'
            )
            ->setParameter('data', '%'.$data.'%');
            $results = $query->getResult();

            $recetteList = '<ul id="matchList">';

            foreach ($results as $result) {
                $matchStringBold = preg_replace('/('.$data.')/i', '<strong>$1</strong>', $result['title']); // Replace text field input by bold one
                $recetteList .= '<li id="'.$result['title'].'">'.$matchStringBold.'</li>'; // Create the matching list - we put maching name in the ID too
            }
            $recetteList .= '</ul>';

            $response = new JsonResponse();
            $response->setData(array('recetteList' => $recetteList));

            return $this->render('view/list.html.twig');
        }
}


