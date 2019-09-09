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
        
        $users = [];
        // Génération des prénoms et noms
        $firstnameUsers = ['Gabriel', 'Jules', 'Nathan', 'Lucas', 'Hugo', 'Leo', 'Raphael', 'Ethan', 'Louise', 'Emma', 'Chloe', 'Manon', 'Alice', 'Lina', 'Lea', 'Camille', 'Theo', 'Axel', 'Jacques', 'Monique', 'Ibrahim', 'Fathia', 'Oscar', 'Oumar', 'Alpha'];
        $lastnameUsers = ['Fleury', 'Leclerc', 'Bouchez', 'Boucher', 'Berger', 'Carpentier', 'Dumas', 'Lacroix', 'Sanchez', 'Vasseur', 'Reynault', 'Hugo', 'Hobb', 'Asimov', 'Gavras', 'Muller', 'Faure', 'Morel', 'Daoud', 'Azel', 'Makhlouf', 'El-Ghoziane', 'Ali']; 
        // Génération des adresses 
        $adresseUsers = ['62  Chemin Challet', '126  Place du Jeu de Paume', '1 Rue du Ballon', '150 Rue Solférino', '36 Rue de Gand', "81 Rue d'Angleterre", '107 Rue Saint-André', '106 Avenue Henri Delecaux', '368 Avenue du Maréchal de Lattre de Tassigny', '23 Allée Vauban'    ];
        $cityUsers = ['Lille', 'Lille', 'Lille', 'Lille', 'Lambersart', 'Saint-André-lez-Lille', 'La Madeleine' ];
        $cpUsers = ['59800', '59800','59800','59800','59000','59000', '59130', '59350', '59110' ]; 
        // Génération des numéros de Téléphone
        $phoneUsers = ['0320458963', '0625789623', '0658962312', '0652894512', '0320568941', '0320896632', '0689451203', '0320985633', '0756894512' ];
        // Génération des descriptions
        $descriptionUsers = [ 'La journée je sauve le monde avec mon slip en lycra et mes collants vert et le soir, pour me detendre, je fais des petits plats.', 
        "Je joue du tuba depuis maintenant 10 ans. A la recherche de nouvelles expériences, je souhaite me lancer dans cette nouvelle aventure qu'est NutNut Food pour m'améliorer en cuisine et pourquoi pas me faire des amis", 
        "Je suis arrivée sur Lille depuis seulement quelques mois. N'ayant trouvé personne qui pourrait m'aider socialement dans mon club de bilboquet, je me suis inscrite sur ce site. J'adore faire la cuisine et mon péché mignon c'est le sorbet aux fraises. On goûte ensemble ?", 
        "Amoureux culinaire depuis ma plus tendre enfance, j'aime l'idée de partager et de faire partager son savoir autour d'un bon pigeon en sauce. Infirmier de profession, j'aime me retrouver dans ma cuisine pour me détendre et enfin faire le vide dans ma tête. Je vous attends !",
         "Eleveur de sanglier le jour et chef étoilé la nuit, j'ai hâte de vous faire découvrir mes spécialités ! Par contre je vous préviens, j'aime et ne cuisine que le samedi soir à partir de minuit",
        "Ancienne Cantatrice, je chantais dans le poulailler de mes parents pour qu'elles fassent des oeufs. Depuis ma récente opération des sphincters, je ne peux malheureusement plus chanter mais apprécie maintenant mettre les poulets dans la cocotte !",
        "Je fais du patinage artistique depuis mes douzes ans et suis actuellement champion Français en la matière. Par contre, je suis végétarien et vous propose de vous partager mes plus belles recettes ! Avec moi vous oublierez la viande... ",
        "On a décidé de s'inscrire sur ce site pour doper nos mornes soirées familiales. Chez nous, ce sera cuisine en famille ! "];

        for($i=0; $i<20;$i++)
        {           
            $user = new User();
            $username = (1 === $i) ? 'toto@toto.fr' : 'titi'.$i.'@titi.fr';
            $roles = (1 === $i) ? 'ROLE_ADMIN' : 'ROLE_USER';
            $user->setUsername($username);
            $user->setRoles($roles);
            $user->setFirstname($firstnameUsers[rand(0,24)]);
            $user->setLastname($lastnameUsers[rand(0,22)]);
            // Générateur de birthdates aléatoires
            $eigtheenYear = time() - 60 * 60 * 24 * 7 * 53 * 18 ;
            $eigthy = time() - 60 * 60 * 24 * 7 * 53 * 80;
            $randomTimestamp = rand($eigtheenYear, $eigthy);
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

        $recetteTitles = ['Les muffins de tati Scarlett, championne MuffinTexas de 1998 ', 'La pizza aux moules, recette incontournable des Pouilles Dunkerquoise', "Salade d'été goumande et vegan",'La Sôle au citron, spécial régime touche gourmandise', 'Quiche Lorraine Athénienne - poivron fêta', 'Ratatouille provencale au thym et pastis', 'Brownie coulant, recette de miss Belgique 1999' ,"Chili Con Carne comme je l'ai appris dans mon treck en amérique latine", 'Hachis Permentier du Chef Gonzales de Mirabeau', 'CheeseCake a la française', "Magret de canard grillé sauce menthe framboise. Hmmmm, un régal", "Paella façon José, vous m'en direz des nouvelles", 'Charlotte aux fraises', "Caviar d'aubergine de la West Coast wesh !", "Houmous de ma Mamie Libanaise, recette rare et unique", 'Tajine Kefta au citron', 'Couscous Tunisien Traditionnel', 'Pain au chocolat comme chez le boulanger, recette très compliquée !', 'Tcharak (Corne de gazelle Algerienne)', 'Moussaka, la vraie recette', 'Lasagne végétarienne', 'Spaghetti Carbonara de ma grand-mère Fiorangela', 'LA Carbonnade Flamande, celle que Brueghel prenait au petit dej', 'Le burger de la muerte'];
        $recetteDescriptions = ['Super Muffin de la tati Scarlett originaire du Texas. Elle a gagné la médaille du prestige au concours MuffinTexas de 1998, également celle du pâté picard mais je n\'ai pas le droit de la  celle-là . Je vous propose de vous partager cette recette qui ravivera vos papilles et celles de vos invités.. ', "Ma pizza aux moules est la meilleures de dunckerque. La recette vient de mon voyage en italie de l'année dernière que j'ai mis au gout du carnavel", "Ma salade d'été ravivera vos papilles. Je vous propose également de vous apprendre en même temps comment laver vos sols sans Javel. Du deux en un !", "Ma sole au citron, c'est comme ça que j'ai ravis le coeur de ma femme. Ne ramenez pas vos ingrédients pour la touche de gourmandise, c'est moi qui offre !", "Ma quiche Lorraine athénienne, tous le monde veut la recette dans ma famille. Mais comme je ne suis plus en odeur de sainteté depuis que j'ai dérobé l'urne funéraire de mamie, je vous propose de vous l'apprendre a vous et pas à d'autres. C'est super bon avec les petits bouts de poivron !", "Mon brownie coulant, il déchire. Je la tiens de miss Belgique 1999 qui me l'a donnée alors que je lui demandais un autographe. Vous n'en serez pas déçu !", "Super chili que j'ai appris lors de mon treck en amérique latine. Je vous propose d'utiliser mes petites epices que j'ai ramenées de là-bas. De plus, pour ceux qui ont l'estomac fragile ne vous en faites pas, il est tout doux !", "Hachis parmentier dont j'ai appris avec le chef toqué Gonzales de Mirabeau. Je ne l'ai pas mis dans ma description mais j'ai été son commis à Lyon et il m'a appris de supers trucs que je pourrais vous apprendre avec cette recette", "C'est un cheeseCake assez simple de vue, mais super compliqué à réaliser. Je vous promets de bien vous apprendre toutes les étapes pour le réussir !", "Cette recette est un régal de tous les instant. Elle résout également les problèmes de coeurs et aide à passer les examens.", "La paella façon José, c'est un super moyen d'impressionner vos collègues de boulot. Par contre, merci de ramener des crevettes fraiche sinon c'est pas la peine.", "La charlotte aux fraises c'est dur à faire, mais c'est super pour le dessert d'anniversaire des bambins. Je vous promets de faire de vous le roi ou la reine des parents avec cette recette !", "Caviar d'aubergine que j'ai appris lors d'un stage de basket que j'ai fait sur la west coast. Il n'y a pas de rapport, mais c'est le sel de cette recette", "Super purée de pois chiche comme vous n'en avez jamais mangé. Par contre si vous aimé bien relevé ramenez le double d'ail.", "Super tajine, même la photo que j'ai faite avec mon appareil photo dernier cri met l'eau à la bouche, avouez ! ", "Recette au couscous qui vient de mon grand-père. Vous voulez voyager ? Hé bien venez !", "Ma lasagne végé, c'est la meilleure de Lille", "C'est super dur de savoir faire une bonne pâte feuilletée comme chez le boulanger. Je vous promets qu'après, ce sera un jeu d'enfant !", "Les petites corne de gazelle, c'est comme les chips, quand on a commencé, tout le paquet part d'un coup !! Ramenez le double d'ingredient si vous souhaitez régaler tout le monde!", "Les spaghettis de ma mamie sont les meilleurs du monde. Vous ne serez pas déçus !", "Ma petite carbonnade flamande, recette que je tiens de l'évéché de brugge. Autant vous dire que ça fait des siècles qu'une petite poignée de gens la cuisine. Vous voulez faire partie des heureux élus ?", " Mon burger de la muerte, c'est qu'il est tellement bon qu'on peut mourir de bonheur."];
        $recettePhoto = ['canard.jpg', 'caviar.jpg', 'charlotte.jpg', 'cheesecake.jpg', 'chili.jpg', 'corne.jpg', 'couscous.jpg', 'hachis.jpg', 'houmous.jpg', 'lasagne.jpg', 'moussake.jpg', 'paella.jpg', 'painauchocolat.jpg', 'muffin.jpg', 'carbo.jpg', 'derssert.jpg', 'quiche.jpg', 'ratatouille.jpg', 'salad.jpg', 'poisson.jpg', 'tagine-kefta.jpg' ];


        $recettes = [];
        for($i=0; $i<200;$i++)
        {
            //Creation d'une date entre maintenant et trois mois
            $now = time();
            $threeMonth = time() - 60 * 60 * 24 * 7 * 12 ;
            $randomTimestamp = rand($now, $threeMonth);

            $recette = new Recette();
            $recette->setTitle($recetteTitles[rand(0,23)] .' '. $i);
            $recette->setPhoto($recettePhoto[rand(0,12)]);
            $recette->setDescription($recetteDescriptions[rand(0,21)]);
            $recette->setOnline(true);
            $recette->setValidate(true);
            $recette->setCreationDate(new \Datetime('@'.$randomTimestamp));
            $recette->setCategory($categories[rand(0,3)]);
            $recette->setNote(3.5);
            $recette->addDiet($diets[rand(0,3)]);
            $recette->setUser($users[rand(0,9)]);
            $recettes[] = $recette;
            $manager->persist($recette);
        }


        $noteComment = ["Super hôte ! J'ai apprécié la manière dont j'ai pu apprendre plein de chose en sa présence.", "C'était tellement bon, veux-tu m'épouser ?", "Mouais, j'ai pas été fan de devoir faire la vaisselle après avoir fait la cuisine. C'était bon, mais j'aime pas qu'on me force à faire des choses dont je n'ai pas envie.", "C'était top mais la prochaine fois, ce sera mieux sans les enfants qui ont un rhume.", "Je pensais venir pour une moussaka et on m'a appris le caviard d'aubergine. C'était super bon mais je suis quand même un peu deçu", "Au top le chef !", "Super accueil, super cuisine !"];
        for($i = 0 ; $i < 50 ; $i++)
        {
            $note = new Note();
            $note->setNote(rand(0,4));
            $note->setCommentaire($noteComment[rand(0,6)]);
            $note->setValidate(true);
            $note->setCreationDate(new \DateTime());
            $note->setUser($users[rand(0,9)]);
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
