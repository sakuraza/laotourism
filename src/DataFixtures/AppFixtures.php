<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Utilisation de la librairie Faker qui permet de générer des données aléatoires
        $faker = Factory::create('FR-fr');

        //Création de 30 annonces
        for ($i=1; $i <= 30 ; $i++) { 
            $ad = new Ad();
            $title = $faker->sentence(6); //6 mots
            $coverImage = $faker->imageUrl(1000, 350); //récuperer sur pixel.it
            $introduction = $faker->paragraph(2); //2 phrases
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>'; //5 paragraphes qui sont séparés par </p> et <p>
            $ad ->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40,200))
                ->setRooms(mt_rand(1,5));

            for ($j=1; $j < mt_rand(2,5); $j++) { 
                $image = new image();
                $image  ->setUrl($faker->imageUrl())
                        ->setCaption($faker->sentence())
                        ->setAd($ad);

                $manager->persist($image);
            }
            //Manager de doctrine : C'est lui qui fait les manipulations au sein de la bdd
            $manager->persist($ad); //Prévient doctrine qu'onn veut Sauvegarde l'annonce
        }
        $manager->flush(); // envoi la requête finale
    }
}
