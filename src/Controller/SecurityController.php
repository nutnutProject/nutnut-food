<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        // Si il y a des erreurs, on les affiches
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){}

    /**
     * @route("/inscription",name="security_registration")
     */
    public function registration(){
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        return $this->render('security/registration.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
