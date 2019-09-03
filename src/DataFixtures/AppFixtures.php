<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Diet;
use App\Entity\Recette;
use App\Entity\User;
use App\Entity\Ingredient;
use App\Entity\Note;


use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $date = new DateTime('now');

        for($i=0; $i<10;$i++)
        {           
            $user = new User();
            $user->setFirstname('Firstname '.$i);
            $user->setLastname('Lastname '.$i);
            $user->setBirthdate($date);
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, 'test')
            );
            $user->setAdresse('Adresse'.$i);
            $user->setCity('Lille');
            $user->setCp('59000');
            $user->setTelephone('0320400000');
            $user->setDescription('Description'. $i);            
            $user->setPwdToken('xxxxxxxx');            
            $user->setEmail('email'.$i.'@email.fr');
            $manager->persist($user);
        }

        for($i=0; $i<10;$i++)
        {
            $recette = new Recette();
            $recette->setTitle('Titre '.$i);
            $recette->setPhoto('no-photo.jpg');
            $recette->setDescription('Description de la recette '.$i);
            $recette->setOnline(false);
            $recette->setValidate(false);
            $recette->setCreationDate($date);
            $manager->persist($recette);
        }

        for($i=0; $i<4; $i++)
        {
            $category = new Category();
            $category->setName('Categorie '. $i);
            $manager->persist($category);
        }

        for($i = 0 ; $i < 4 ; $i++)
        {
            $diet = new Diet();
            $diet->setName('Régime '. $i);
            $manager->persist($diet);
        }

        for($i = 0 ; $i < 10 ; $i++)
        {
            $note = new Note();
            $note->setNote(rand(0,10));
            $note->setCommentaire('Mon commentaire super commentaire de foufou numéro ' . $i);
            $note->setValidate(true);
            $manager->persist($note);
        }

        for($i = 0 ; $i < 10 ; $i++)
        {
            $ingredient = new Ingredient();
            $ingredient->setName('Poireau n° ' . $i);
            $ingredient->setQuantity(rand(0,10) . 'Litre(s)'); 
            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
