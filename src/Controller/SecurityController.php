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
            $user->setPwdTokenExpire(time()+3600);
            $user->setActivateTokenExpire(time()+3600);
            $user->setAccountActivate(false);

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

            $this->addFlash('success', 'Votre inscription a été prise en compte. Un email vous a été envoyé. Merci de confirmer votre inscription en cliquant sur le lien contenu dans ce mail.');

            return $this->redirectToRoute('home');
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
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['activateToken' => $token]);

        if($user === null)
        {
            // Le champ 'activateToken' n'a pas été trouvé dans la table 'User"
            return $this->redirectToRoute('home');
        }

        // Modification de l'entrée utilisateur dans la bdd avec suppresion de la valeur du champ activateToken
        try{
            $user->setActivateToken("");
            $user->setAccountActivate(true);
            $entityManager->flush();

        } catch (\Exception $e) {
            $this->addFlash('warning', $e->getMessage());
            return $this->redirectToRoute('home');
        }

        $this->addFlash('success', 'Votre compte a bien été activé. Vous pouvez maintenant vous connecter.');

        $this->redirectToRoute('home'); 


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

    /**
     * @Route("/",name="forget_password")
     */

    public function forgetPassword()
    {

    }
}
// Tu vas y arriver Pierre !