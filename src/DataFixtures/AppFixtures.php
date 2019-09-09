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
        // Génération des prénoms et noms
        $firstnameUsers = ['Gabriel', 'Jules', 'Nathan', 'Lucas', 'Hugo', 'Leo', 'Raphael', 'Ethan', 'Louise', 'Emma', 'Chloe', 'Manon', 'Alice', 'Lina', 'Lea', 'Camille', 'Theo', 'Axel', 'Jacques', 'Monique', 'Ibrahim', 'Fathia', 'Oscar', 'Oumar', 'Alpha'];
        $lastnameUsers = ['Fleury', 'Leclerc', 'Bouchez', 'Boucher', 'Berger', 'Carpentier', 'Dumas', 'Lacroix', 'Sanchez', 'Vasseur', 'Reynault', 'Hugo', 'Hobb', 'Asimov', 'Gavras', 'Muller', 'Faure', 'Morel', 'Daoud', 'Azel', 'Makhlouf', 'El-Ghoziane', 'Ali']; 
        // Generate random birthdate
        $eigtheenYear = time() - 60 * 60 * 24 * 7 * 53 * 18 ;
        $eigthy = time() - 60 * 60 * 24 * 7 * 53 * 80;
       
        // Génération des adresses 
        $adresseUsers = ['62  Chemin Challet', '126  Place du Jeu de Paume', '1 Rue du Ballon', '150 Rue Solférino', '36 Rue de Gand', "81 Rue d'Angleterre", '107 Rue Saint-André', '106 Avenue Henri Delecaux', '368 Avenue du Maréchal de Lattre de Tassigny', '23 Allée Vauban'    ];
        $cityUsers = ['Lille', 'Lille', 'Lille', 'Lille', 'Lambersart', 'Saint-André-lez-Lille', 'La Madeleine' ];
        $cpUsers = ['59800', '59800','59800','59800','59000','59000', '59130', '59350', '59110' ]; 
        // Génération des numéros de Téléphone
        $phoneUsers = ['0320458963', '0625789623', '0658962312', '0652894512', '0320568941', '0320896632', '0689451203', '0320985633', '0756894512' ];
        // Génération des descriptions
        $descriptionUsers = [ 'La journée je sauve le monde et pour me detendre de fait des petits plats.', 
        "Je joue du tuba depuis maintenant 10 ans. A la recherche de nouvelles expériences, je souhaite me lancer dans cette nouvelle aventure qu'est NutNut Food pour m'améliorer en cuisine et pourquoi pas me faire des amis", 
        "Je suis arrivée sur Lille depuis seulement quelques mois. N'ayant trouvé personne qui pourrait m'aider socialement dans mon club de bilboquet, je me suis inscrite sur ce site. J'adore faire la cuisine et mon péché mignon c'est le sorbet aux fraises. On goûte ensemble ?", 
        "Amoureux culinaire depuis ma plus tendre enfance, j'aime l'idée de partager et de faire partager son savoir autour d'un bon pigeon en sauce. Infirmier de profession, j'aime me retrouver dans ma cuisine pour me détendre et enfin faire le vide dans ma tête. Je vous attends !",
         "Eleveur de sanglier le jour et chef étoilé la nuit, j'ai hâte de vous faire découvrir mes spécialités ! Par contre je vous préviens, j'aime et ne cuisine que le samedi soir à partir de minuit",
        "Ancienne Cantatrice, je chantais dans le poulailler de mes parents pour qu'elles fassent des oeufs. Depuis ma récente opération des sphincters, je ne peux malheureusement plus chanter mais apprécie maintenant mettre les poulets dans la cocotte !",
        "Je fais du patinage artistique depuis mes douzes ans et suis actuellement champion Français en la matière. Par contre, je suis végétarien et vous propose de vous partager mes plus belles recettes ! Avec moi vous oublierez la viande... ",
        "On a décidé de s'inscrire sur ce site pour doper nos mornes soirées familiales. Chez nous, ce sera cuisine en famille ! "];

        for($i=0; $i<10;$i++)
        {           
            $user = new User();
            $username = (1 === $i) ? 'toto@toto.fr' : 'titi'.$i.'@titi.fr';
            $roles = (1 === $i) ? 'ROLE_ADMIN' : 'ROLE_USER';
            $user->setUsername($username);
            $user->setRoles($roles);
            $user->setFirstname($firstnameUsers[rand(0,24)]);
            $user->setLastname($lastnameUsers[rand(0,22)]);
            // Générateur de birthdates aléatoires
            $randomTimestamp = rand(time() - 60 * 60 * 24 * 7 * 53 * 80, time() - 60 * 60 * 24 * 7 * 53 * 18);
            $user->setBirthdate(new \Datetime('@'.$randomTimestamp));
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, 'test')
            );
            $user->setAdresse($adresseUsers[rand(0,9)]);
            $user->setCity($cityUsers[rand(0,6)]);
            $user->setCp($cpUsers[rand(0,8)]);
            $user->setTelephone($phoneUsers[rand(0,8)]);
            $user->setDescription($descriptionUsers[rand(0,7)]);            
            $user->setPwdToken('xxxxxxxx'.$i);
            $user->setActivateToken('xxxxxxxxx'.$i);
            $user->setPwdTokenExpire(time()+3600);
            $user->setActivateTokenExpire(time()+3600);
            $user->setAccountActivate(false);



            $users[] = $user;
            $manager->persist($user);
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
            $recette->setCategory($categories[rand(0,3)]);
            $recette->setNote(3.5);
            $recette->addDiet($diets[rand(0,3)]);
            $recette->setUser($users[rand(0,9)]);
            $recettes[] = $recette;
            $manager->persist($recette);
        }

        for($i = 0 ; $i < 50 ; $i++)
        {
            $note = new Note();
            $note->setNote(rand(0,10));
            $note->setCommentaire('Mon commentaire super commentaire de foufou numéro ' . $i);
            $note->setValidate(true);
            $note->setRecette($recettes[rand(0,50)]);
            $manager->persist($note);
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
