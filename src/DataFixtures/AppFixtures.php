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

        $users = [];
        for($i=0; $i<10;$i++)
        {           
            $user = new User();
            $username = (1 === $i) ? 'matthieu' : 'user-'.$i;
            $roles = (1 === $i) ? ['ROLE_ADMIN'] : ['ROLE_USER'];
            $user->setUsername($username);
            $user->setRoles($roles);
            $user->setFirstname('Monique'.$i);
            $user->setLastname('Poteau '.$i);
            $user->setBirthdate($date);
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, 'test')
            );
            $user->setAdresse('Adresse'.$i);
            $user->setCity('Lille');
            $user->setCp('59000');
            $user->setTelephone('0320400000');
            $user->setDescription('La journée je sauve le monde et pour me detendre de fait des petits plats.'. $i);            
            $user->setPwdToken('xxxxxxxx'.$i);
            $user->setActivateToken('xxxxxxxxx'.$i);
            $user->setPwdTokenExpire(time()+3600);
            $user->setActivateTokenExpire(time()+3600);
            $user->setAccountActivate(false);



            $users[] = $user;
            $manager->persist($user);
        }

        $notes = [];
        for($i = 0 ; $i < 50 ; $i++)
        {
            $note = new Note();
            $note->setNote(rand(0,10));
            $note->setCommentaire('Mon commentaire super commentaire de foufou numéro ' . $i);
            $note->setValidate(true);
            $notes[] = $note;
            $manager->persist($note);
        }


        $diets = [];
        $dietNames = [
            'Vegetarien',
            'Vegan',
            'Hallal',
            'Carne'
        ];
        foreach($dietNames as $name)
        {
            $diet = new Diet();
            $diet->setName($name);
            $diets[] = $diet;
            $manager->persist($diet);
        }

        $categories = [];
        $names = [
            'Aperitif',
            'Entrée',
            'Plat',
            'Dessert'
        ];
        foreach($names as $name)
        {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
            $categories[] = $category;
        }

        $recettes = [];
        for($i=0; $i<200;$i++)
        {
            $recette = new Recette();
            $recette->setTitle('Mon poulet coco '.$i);
            $recette->setPhoto('no-photo.jpg');
            $recette->setDescription('Je fais des quiches au saumon depuis que je suis enfant, recette de ma grand-mere..'.$i);
            $recette->setOnline(false);
            $recette->setValidate(false);
            $recette->setCreationDate($date);
            $recette->setNote($notes[rand(0,9)]);
            $recette->setCategory($categories[rand(0,3)]);
            $recette->addDiet($diets[rand(0,3)]);
            $recette->setUser($users[rand(0,9)]);
            $recettes[] = $recette;
            $manager->persist($recette);
        }



        for($i = 0 ; $i < 10 ; $i++)
        {
            $ingredient = new Ingredient();
            $ingredient->setName('Poireau n° ' . $i);
            $ingredient->setQuantity(rand(0,10) . 'Litre(s)'); 
            $ingredient->setRecette($recettes[rand(0,9)]);
            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
