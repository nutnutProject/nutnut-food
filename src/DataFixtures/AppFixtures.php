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
         $firstnameUsers =   ['Chloe',    'Juliette', 'Alice', 'Lina', 'Salim',  'Mescipa', 'Theo',  'Axel', 'Jacques', 'Monique', 'Ibrahim', 'Fathia', 'Arnauld', 'Oumar',    'Alpha', 'Abdjiel', 'Abdel',    'Salek',    'Akira', 'Rick',    'Akito'  ]; 
         $lastnameUsers = [ 'Reynault', 'Hugo',    'Hobb', 'Asimov', 'Gavras', 'Adid',     'Faure', 'Morel', 'Daoud', 'Azel', 'Makhlouf', 'El-Ghoziane', 'Ali', 'Fontaine', 'Hervo', 'Gosselin', 'Nagasaki', 'Riboux', 'Cateau', 'Martin', 'Hiroshige']; 
        // Génération des adresses 
        $adresseUsers = ['62  Chemin Challet', '126  Place du Jeu de Paume', '1 Rue du Ballon', '150 Rue Solférino', '36 Rue de Gand', "81 Rue d'Angleterre", '107 Rue Saint-André', '106 Avenue Henri Delecaux', '368 Avenue du Maréchal de Lattre de Tassigny', '23 Allée Vauban'    ];
        $cityUsers = ['Lille', 'Lille', 'Lille', 'Lille', 'Lambersart', 'Saint-André-lez-Lille', 'La Madeleine', 'Saint-Maurice Pelvoision', 'Lille-Moulin', ];
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
            "On a décidé de s'inscrire sur ce site pour doper nos mornes soirées familiales. Chez nous, ce sera cuisine en famille ! ",
            "Je me suis inscrit car au début je préférait le sport, mais comme j'ai payé mon abonnement pour rien et que je continuais à manger comme 4, autant partager. Je pense que Nutnut Food va même réussir à me muscler",
            "Je n'irais pas pas 4 chemins, je cherche l'amour, le grand, qu'on pourra partager autour d'une bonne quiche au saumon.",
            "Etant de la génération 4.0, je me suis toujours dis que j'aimerai réussir de manière numérique à fédérer autour d'un bon coq au vin. Grace à ce site, c'est parti ! Sachez par contre qu'il est or de question de me faire cuisiner une tarte, ça me fait vomir",
            " Je ne sais pas ce que je fais ici, c'est mon tonton qui m'a conseillé de m'inscrire pour que je joue moins au jeux vidéos. Si quelqu'un est chaud à prendre pour prétexte de faire une tourte pour me permettre de faire une lan chez lui, je suis chaud bouillant",
            "Depuis tout petit j'aime la soupe. C'est comme ça je n'y peut rien. Mais comme je ne sais pas cuisiner autre chose, j'attends beaucoup des fooders pour me faire évoluer dans mon alimentation. En échange je suis de super bonne compagnie.",
            "Je pourrais dire que je me suis inscrit pour passer du bon temps, ce qui est vrai, mais bon. Je suis nul en description, venez chez moi faire mes recettes et on se connaitra mieux.",
            "Bonjour, je fais super bien la cuisine. J'en profite pour passer une annonce, je vends mon scooter pas cher. Si intéressez, venez apprendre une recette chez moi, je vous le montrerais. Des bises, merci",
            "J'ai le don particulier de changer un vieux sandwich en mine d'or pour patachon. Du coup n'hésitez pas, je vais vous montrer ce que c'est la bonne cuisine, bande de vilains.",
            'La journée je sauve le monde avec mon slip en lycra et mes collants vert et le soir, pour me detendre, je fais des petits plats.', 
            "Je joue du tuba depuis maintenant 10 ans. A la recherche de nouvelles expériences, je souhaite me lancer dans cette nouvelle aventure qu'est NutNut Food pour m'améliorer en cuisine et pourquoi pas me faire des amis", 
            "Je suis arrivée sur Lille depuis seulement quelques mois. N'ayant trouvé personne qui pourrait m'aider socialement dans mon club de bilboquet, je me suis inscrite sur ce site. J'adore faire la cuisine et mon péché mignon c'est le sorbet aux fraises. On goûte ensemble ?", 
            "Amoureux culinaire depuis ma plus tendre enfance, j'aime l'idée de partager et de faire partager son savoir autour d'un bon pigeon en sauce. Infirmier de profession, j'aime me retrouver dans ma cuisine pour me détendre et enfin faire le vide dans ma tête. Je vous attends !",
             "Eleveur de sanglier le jour et chef étoilé la nuit, j'ai hâte de vous faire découvrir mes spécialités ! Par contre je vous préviens, j'aime et ne cuisine que le samedi soir à partir de minuit"];
        // Génération des photos d'users
        $imageUsers = ['img/user/tuyaux.png', 'img/user/permanente.jpg', 'img/user/grimace.jpg', 'img/user/heros.jpg', 'img/user/peucheuse.jpg', 'img/user/scaphandre2.jpg', 'img/user/telephone.png', 'img/user/chauvesouris.png', 'img/user/tulipe.png', 'img/user/antennes.png', 'img/user/scaphandre.png', 'img/user/passoire.png', 'img/user/lunette.png', 'img/user/caque.png', 'img/user/tourbillon.jpg', 'img/user/pull.jpg', 'img/user/pull2.jpg', 'img/user/barbe.jpg', 'img/user/barbe2.jpg', 'img/user/maison.jpg', 'img/user/costume.jpg'];
    
        // 'img/user/tuyaux.png', 'img/user/permanente.jpg', 'img/user/grimace.jpg', 'img/user/heros.jpg', 'img/user/peucheuse.jpg', 'img/user/costume.jpg', 'img/user/scaphandre2.jpg', 'img/user/telephone.png', 'img/user/chauvesouris.png', 'img/user/tulipe.png']; 21

        for($i=0; $i<21;$i++)
        {           
            $user = new User();
            $username = (1 === $i) ? 'toto@toto.fr' : 'titi'.$i.'@titi.fr';
            $roles = (1 === $i) ? 'ROLE_ADMIN' : 'ROLE_USER';
            $user->setUsername($username);
            $user->setRoles($roles);
            $user->setImage($imageUsers[$i]);
            $user->setFirstname( $firstnameUsers[$i]);
            $user->setLastname($lastnameUsers[$i]);
            // Générateur de birthdates aléatoires
            $eigtheenYear = time() - 60 * 60 * 24 * 7 * 53 * 18 ;
            $eigthy = time() - 60 * 60 * 24 * 7 * 53 * 80;
            $randomTimestamp = rand($eigtheenYear, $eigthy);
            $user->setBirthdate(new \Datetime('@'.$randomTimestamp));
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, 'testtest')
            );
            $user->setAdresse($adresseUsers[rand(0,9)]);
            $user->setCity($cityUsers[rand(0,6)]);
            $user->setCp($cpUsers[rand(0,8)]);
            $user->setTelephone($phoneUsers[rand(0,8)]);
            $user->setDescription($descriptionUsers[$i]);            
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

        $recettePhoto = [ 'muffin.jpg', 'pizza.jpg', 'salad.jpg',  'poisson.jpg', 'quiche.jpg',  'ratatouille.jpg', 'dessert.jpg',   'chili.jpg', 'hachis.jpg', 'cheesecake.jpg', 'canard.jpg', 'paella.jpg', 'charlotte.jpg',  'caviar.jpg',  'houmous.jpg', 'tajine.jpg', 'couscous.jpg', 'painauchocolat.jpg', 'corne.jpg', 'moussake.jpg', 'lasagne.jpg', 'carbonara.jpg', 'carbo.jpg', 'junk.jpg' ];

        $recetteTitles = ['Les muffins de tati, championne MuffinTexas 98', 'La pizza aux moules, recette incontournable', "Salade d'été goumande et vegan",'La Sôle au citron, spécial gourmandise', 'Quiche Lorraine poivron fêta', 'Ratatouille provencale au thym et pastis', 'Brownie coulant, recette de miss Belgique 1999' ,"Chili Con Carne comme je l'ai appris lors d'un treck", 'Hachis Permentier du Chef Gonzales de Mirabeau', 'CheeseCake a la française', "Magret de canard grillé sauce menthe framboise. ", "Paella façon José", 'Charlotte aux fraises', "Caviar d'aubergine de la West Coast wesh !", "Houmous de ma Mamie Libanaise, recette rare et unique", 'Tajine Kefta au citron', 'Couscous Tunisien Traditionnel', 'Pain au chocolat de la boulangerie, recette compliquée !', 'Tcharak (Corne de gazelle Algerienne)', 'Moussaka, la vraie recette', 'Lasagne végétarienne', 'Spaghetti Carbonara de ma grand-mère Fiorangela', 'LA Carbonnade Flamande de fou', 'Le burger de la muerte'];

        $recetteDescriptions = ['Super Muffin de la tati Scarlett originaire du Texas. Elle a gagné la médaille du prestige au concours MuffinTexas de 1998, également celle du pâté picard mais je n\'ai pas le droit de la  celle-là . Je vous propose de vous partager cette recette qui ravivera vos papilles et celles de vos invités.. ', 
        "Ma pizza aux moules est la meilleures de dunckerque. La recette vient de mon voyage en italie de l'année dernière que j'ai mis au gout du carnavel",
         "Ma salade d'été ravivera vos papilles. Je vous propose également de vous apprendre en même temps comment laver vos sols sans Javel. Du deux en un !",
        "Ma sole au citron, c'est comme ça que j'ai ravis le coeur de ma femme. Ne ramenez pas vos ingrédients pour la touche de gourmandise, c'est moi qui offre !",
        "Ma quiche Lorraine athénienne, tous le monde veut la recette dans ma famille. Mais comme je ne suis plus en odeur de sainteté depuis que j'ai dérobé l'urne funéraire de mamie, je vous propose de vous l'apprendre a vous et pas à d'autres. C'est super bon avec les petits bouts de poivron !",
        "Ma ratatouille, c'est la meilleure du nord. Rien qu'a voir comment je l'arrose avec du bon pastis qu'on se l'imagine en train de la manger. Ya bien quelqu'un une fois qui ne l'a pas aimée mais c'était un abruti. ",
        "Mon brownie coulant, il déchire. Je la tiens de miss Belgique 1999 qui me l'a donnée alors que je lui demandais un autographe. Vous n'en serez pas déçu !",
        "Super chili que j'ai appris lors de mon treck en amérique latine. Je vous propose d'utiliser mes petites epices que j'ai ramenées de là-bas. De plus, pour ceux qui ont l'estomac fragile ne vous en faites pas, il est tout doux !",
        "Hachis parmentier dont j'ai appris avec le chef toqué Gonzales de Mirabeau. Je ne l'ai pas mis dans ma description mais j'ai été son commis à Lyon et il m'a appris de supers trucs que je pourrais vous apprendre avec cette recette",
        "C'est un cheeseCake assez simple de vue, mais super compliqué à réaliser. Je vous promets de bien vous apprendre toutes les étapes pour le réussir !",
        "Cette recette est un régal de tous les instant. Elle résout également les problèmes de coeurs et aide à passer les examens.", 
        "La paella façon José, c'est un super moyen d'impressionner vos collègues de boulot. Par contre, merci de ramener des crevettes fraiche sinon c'est pas la peine.",
        "La charlotte aux fraises c'est dur à faire, mais c'est super pour le dessert d'anniversaire des bambins. Je vous promets de faire de vous le roi ou la reine des parents avec cette recette !", 
        "Caviar d'aubergine que j'ai appris lors d'un stage de basket que j'ai fait sur la west coast. Il n'y a pas de rapport, mais c'est le sel de cette recette",
        "Super purée de pois chiche comme vous n'en avez jamais mangé. Par contre si vous aimé bien relevé ramenez le double d'ail.", 
        "Super tajine, même la photo que j'ai faite avec mon appareil photo dernier cri met l'eau à la bouche, avouez ! ",
        "Recette au couscous qui vient de mon grand-père. Vous voulez voyager ? Hé bien venez !",

        "le pain au chocolat, c'est facile a manger mais c'est une tannée à fabriquer. Je vous propose d'apprendre à en faire au moins d'aussi bon que ceux qu'on achète à super U dans du plastique",
        "Les petites corne de gazelle, c'est comme les chips, quand on a commencé, tout le paquet part d'un coup !! Ramenez le double d'ingredient si vous souhaitez régaler tout le monde!",
        "J'ai appris la recette lors d'une rainbow. Tout le monde était nu et la moussaka sentait super bon. On a tout mangé et on s'est fait des calins. ",
        "Ma lasagne végé, c'est la meilleure de Lille", 
        "Les spaghettis de ma mamie sont les meilleurs du monde. Vous ne serez pas déçus !",
        "Ma petite carbonnade flamande, recette que je tiens de l'évéché de brugge. Autant vous dire que ça fait des siècles qu'une petite poignée de gens la cuisine. Vous voulez faire partie des heureux élus ?", 
        " Mon burger de la muerte, c'est qu'il est tellement bon qu'on peut mourir de bonheur."];



        $recettes = [];
        for($i=0; $i<23;$i++)
        {
            //Creation d'une date entre maintenant et trois mois
            $now = time();
            $threeMonth = time() - 60 * 60 * 24 * 7 * 12 ;
            $randomTimestamp = rand($now, $threeMonth);

            $recette = new Recette();
            $recette->setTitle($recetteTitles[$i]);
            $recette->setPhoto('img/'.$recettePhoto[$i]);
            $recette->setDescription($recetteDescriptions[$i]);
            $recette->setOnline(true);
            $recette->setValidate(true);
            $recette->setCreationDate(new \Datetime('@'.$randomTimestamp));
            $recette->setCategory($categories[rand(0,3)]);
            $recette->setNote(rand(0,5));
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
            $note->setRecette($recettes[rand(0,22)]);
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
