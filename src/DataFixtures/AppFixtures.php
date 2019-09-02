<?php

namespace App\DataFixtures;

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

        $manager->flush();

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
        $manager->flush();
    }
}
