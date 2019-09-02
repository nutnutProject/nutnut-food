<?php

namespace App\DataFixtures;

use App\Entity\Recette;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i=0; $i<10;$i++)
        {
            $date = new DateTime('now');
            $user = new User();
            $user->setFirstname('Firstname '.$i);
            $user->setLastname('Lastname '.$i);
            $user->setBirthdate($date);
            $user->setPassword('xxxxxxxx');
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

        $manager->flush();
    }
}
