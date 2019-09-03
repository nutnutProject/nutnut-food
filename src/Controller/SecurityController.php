<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
    }

    /**
     * @route("/inscription",name="security_registration")
     */
    public function registration(Request $request, \Swift_Mailer $mailer){

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Génération des token
            $pwd_token = md5(uniqid());
            $activateToken = md5(uniqid());

            $user->setPwdToken($pwd_token);
            $user->setActivateToken($activateToken);

            // Remplissage du rôle utilisateur
            $user->setRoles('[ROLE_USER]');

            // Enregistrement dans la bdd
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Préparation du mail contenant le lien d'activation
            $destinataire = $user->getUsername();
            $sujet = "Activer votre compte" ;

            // Le lien d'activation est composé du token
            $message = 'Bienvenue sur Nut Nut Food,

            Pour activer votre compte, veuillez cliquer sur le lien ci dessous
            ou copier/coller dans votre navigateur internet.

            http://votresite.com/activation/'.$pwd_token.'


            ---------------
            Ceci est un mail automatique, Merci de ne pas y répondre.';

            // Envoi du mail de confiration de souscription
            $message = (new \Swift_Message('Hello'))
                ->setFrom('inscription@nutnutfood.fr')
                ->setTo($destinataire)
                ->setSubject($sujet)
                ->setBody($message);
            $mailer->send($message);
        }

        return $this->render('security/registration.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/activation/{token}",name="activation")
     */
    public function activation($token)
    {
        // Récupérer le repository de l'entité User
        $productRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $productRepository->findOneBy(['activateToken' => $token]);

        // Modification de l'entrée utilisateur dans la bdd avec suppresion de la valeur du champ activateToken
        

        dd($user);

    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //    $this->redirectToRoute('target_path');
        // }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}
// Tu vas y arriver Pierre !