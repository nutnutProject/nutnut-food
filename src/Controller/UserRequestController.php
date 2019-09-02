<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserRequestController extends AbstractController
{
    /**
     * @Route("/user/request", name="user_request")
     */
    public function index()
    {
        return $this->render('user_request/index.html.twig', [
            'controller_name' => 'UserRequestController',
        ]);
    }
}
