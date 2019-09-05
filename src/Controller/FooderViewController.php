<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FooderViewController extends AbstractController
{

/**
 * @Route("/fooder/{id}", name="fooder_show")
 * 
 * permet d'aller sur le profil d'un fooder
 */
public function show(User $user): Response
{
    return $this->render('fooder_view/show_fooder.html.twig', [
        'user' => $user,
    ]);
}


}



