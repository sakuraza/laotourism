<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        //Utilisation de la librairie Faker qui permet de générer des données aléatoires https://github.com/fzaninotto/Faker
        $faker = Factory::create('fr-FR');

        //Création des utilisateurs
        $users = [];
        $genres = ['male', 'female'];
        for ($i=1; $i <= 10 ; $i++) { 
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99). '.jpg';

            $picture .= ($genre == "male" ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');
        
            $user->setFirstName($faker->firstname)
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>' .join('</p><p>', $faker->paragraphs(3)) . '</p>')
                 ->sethash($hash)
                 ->setPicture($picture)
                 ;

            $manager->persist($user);
            $users[] = $user;
        }

        //Création de 30 annonces
        for ($i=1; $i <= 30 ; $i++) { 
            $ad = new Ad();
            $title = $faker->sentence(6); //6 mots
            $coverImage = $faker->imageUrl(1000, 350); //récuperer sur http://lorempixel.com (largeur, hauteur)
            $introduction = $faker->paragraph(2); //2 phrases
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>'; //5 paragraphes qui sont séparés par </p> et <p>
            
            $user = $users[mt_rand(0,count($users) - 1)];
            
            $ad ->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40,200))
                ->setRooms(mt_rand(1,5))
                ->setAuthor($user);

            for ($j=1; $j < mt_rand(2,5); $j++) { 
                $image = new image();
                $image  ->setUrl($faker->imageUrl())
                        ->setCaption($faker->sentence())
                        ->setAd($ad);

                $manager->persist($image);
            }
            //Manager de doctrine : C'est lui qui fait les manipulations au sein de la bdd
            $manager->persist($ad); //Prévient doctrine qu'on veut Sauvegarde l'annonce
        }
        $manager->flush(); // envoi la requête finale
    }
}
