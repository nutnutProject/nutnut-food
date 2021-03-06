<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
    }

    /**
     * @route("/inscription",name="security_registration")
     */
    public function registration(Request $request, \Swift_Mailer $mailer)
    {    
        if(!$this->getUser())
        {
            $user = new User();
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $userRepository = $this->getDoctrine()->getRepository(User::class);

                if ($userRepository->findBy(['username' => $user->getUsername()]))
                {
                    $this->addFlash('danger', 'Un utilisateur est déjà inscrit avec cette adresse email');            
                    return $this->redirectToRoute('home');
                }

                if ($user->getPassword() != $request->get('pwd_confirmed'))
                {
                    $this->addFlash('danger', 'Les mots de passe saisis ne sont pas identique');            
                    return $this->redirectToRoute('home');
                }
                
                // Génération des token
                $pwd_token = md5(uniqid());
                $activateToken = md5(uniqid());

                $file = $_FILES['user'];

                // Traitement de l'image
                if (!preg_match("/\.(jpg|jpeg|gif|png)$/",$file['name']['image']))
                {
                    $errors['cover'] = "Le type de l'image n'est pas valide";
                }
        
                // Controle de la taille du fichier
                if ($file['size']['image'] > 2000000 )
                {
                    $errors['cover'] = "La taille de l'image est supérieur à 2Mo.";
        
                }

                //Récupération de l'extension d'origine
                if(empty($errors))
                {
                    $pathinfo = pathinfo($file['name']['image']);
                    $extension = $pathinfo['extension'];
                }
        
                // Définition du nom de fichier, celui-ci doit être unique
                $filename = uniqid();
                $filename .= "." .$extension;
        
                // Définition de l'emplacement du fichier
                $filepath = "img/user/".$filename;
                
                // Déplacement du fichier dans le dossier "img/"
                copy($file['tmp_name']['image'], $filepath);

                $user->setImage($filepath);
                $user->setPwdToken($pwd_token);
                $user->setActivateToken($activateToken);
                $user->setPwdTokenExpire(time()+3600);
                $user->setActivateTokenExpire(time()+3600);
                $user->setAccountActivate(false);


                $user->setPassword(
                    $this->passwordEncoder->encodePassword($user, $user->getPassword())
                );

                // Calcul de l'age
                $now = new DateTime('now');
                $age = $user->getBirthdate();
                $difference = $now->diff($age);

                if ((int)$difference->format('%y') < 18 )
                {
                    $this->addFlash('danger', 'Vous devez être majeur afin de pouvoir vous inscrire.');
                    return $this->redirectToRoute('home');    
                }
                // Remplissage du rôle utilisateur
                $user->setRoles('ROLE_USER');

                // Enregistrement dans la bdd
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // Préparation du mail contenant le lien d'activation
                $destinataire = $user->getUsername();
                $sujet = "Activer votre compte" ;

                // Le lien d'activation est composé du token
                $body = 'Bienvenue sur Nut Nut Food,

                Pour activer votre compte, veuillez cliquer sur le lien ci dessous
                ou copier/coller dans votre navigateur internet.

                http://nutnutfood.gosselin.info/activation/'.$activateToken.'


                ---------------
                Ceci est un mail automatique, Merci de ne pas y répondre.';

                // Envoi du mail de confiration de souscription
                $message = (new \Swift_Message($sujet))
                    ->setFrom('nutnutfood@gosselin.info')
                    ->setTo($destinataire)
                    ->setBody($body);
                $mailer->send($message);

                
                $this->addFlash('success', 'Votre inscription a été prise en compte. Un email vous a été envoyé. Merci de confirmer votre inscription en cliquant sur le lien contenu dans ce mail.');


                return $this->redirectToRoute('home');
            }
            return $this->render('security/registration.html.twig',[
                'form' => $form->createView()
            ]);
        }
        return $this->redirectToRoute('home');    
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

        // Teste du token
        if (($user->getActivateTokenExpire() - time()) < 0)
        {
            $this->addFlash('alert', 'Votre token a expiré');
        }
        else
        {
            // Modification de l'entrée utilisateur dans la bdd avec suppresion de la valeur du champ activateToken
            try{
                $user->setActivateToken("");
                $user->setAccountActivate(true);
                $entityManager->flush();

                $this->addFlash('success', 'Votre compte a bien été activé. Vous pouvez maintenant vous y connecter.');

            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('home');
            }
        }
        return $this->redirectToRoute('home'); 
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

        if ($error)
        {
            $this->addFlash('alert',$error->getMessageKey());

        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/mot-de-passe-oublié",name="forget_password")
     */

    public function forgetPassword(Request $request, \Swift_Mailer $mailer)
    {



        
        if ($request->isMethod('POST'))
        {
            $username = $request->request->get('username');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByUsername($username);

            if ($user === null) {
                $this->addFlash('danger', 'Si un compte existe avec cette adresse email, un email vous sera envoyé.');
                return $this->redirectToRoute('home');
            }

            $pwd_token = md5(uniqid());
            $user->setPwdToken($pwd_token);
            $user->setPwdTokenExpire(time()+3600);
            $entityManager->flush();

            $destinataire = $user->getUsername();
            $sujet = "Mot de passe oublié" ;

            // Le lien d'activation est composé du token
            $body = 'Bienvenue sur Nut Nut Food,

            Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien ci dessous
            ou copier/coller dans votre navigateur internet.

            http://nutnutfood.gosselin.info/reset_password/'.$pwd_token.'


            ---------------
            Ceci est un mail automatique, Merci de ne pas y répondre.';

            // Envoi du mail de confiration de souscription
            $message = (new \Swift_Message($sujet))
                ->setFrom('nutnutfood@gosselin.info')
                ->setTo($destinataire)
                ->setBody($body);
            $mailer->send($message);
            
            $this->addFlash('notice', 'Si un compte existe avec cette adresse email, un email vous sera envoyé.');
 
            return $this->redirectToRoute('home');

        }
        return $this->render('security/forgotten_password.html.twig');

    }

    /**
     * @Route("/reset_password/{token}", name="reset_password")
     */
    public function resetPassword($token = 0, Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->findOneBy(['pwd_token' => $token]);

        if ($user == null) {
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('home');
        }

        // Modification du mot de passe
        if ($request->isMethod('POST'))
        {
            if ($request->request->get('password') == $request->request->get('confirmed_password'))
            {
                if (($user->getActivateTokenExpire() - time()) < 0)
                {
                    $this->addFlash('alert', 'Votre token a expiré');
                }
                else
                {
                    $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
                    $entityManager->flush();
                
                    $this->addFlash('success', 'Le mot de passe a bien été modifié.');
                }
            }
            else
            {
                $this->addFlash('alert', 'Les mots de passe saisis ne sont pas identiques.');
            }

            return $this->redirectToRoute('home');
        }
     
        return $this->render('security/reset_password.html.twig',[
            'token' => $token,
        ]);
    }

}






