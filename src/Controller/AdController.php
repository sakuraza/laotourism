<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * Liste des annonces
     * 
     * @Route("/ads", name="ads_index")
     * 
     */
    public function index(AdRepository $repo) //injection de dépendance $repo instance de AdRepository
    {
        $ads = $repo->findAll();
        
        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
            ]);
    }

     /**
      * Permet de créer une annonce
      *
      * @Route("/ads/new", name="ads_create")
      *
      * @param Request $request
      * @param ObjectManager $manager
      * @return Response
      */
    public function create(Request $request, ObjectManager $manager)
    {
        $ad = new Ad();

        //createForm permet de créer un formulaire externe
        $form = $this->createForm(AnnonceType::class, $ad);

        //handleRequest() permet de parcourir la requête et d'extraire les informations du form
        $form->handleRequest($request);

        //dump($ad);
        
        //issubmitted() permet de savoir si le formulaire a été soumis ou pas / isValid() permet de savoir si le formulaire est valide par rapport au règles en place
        if ($form->isSubmitted() && $form->isValid()) {

            //Persist chaque(s) image(s) de l'annonce
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            
            //Prévient doctrine qu'on veut sauver
            $manager->persist($ad);
            //Envoie la requête SQL
            $manager->flush();
            
            $this->addFlash(
                "success",
                "L'anonce <strong>{$ad->getTitle()}</strong> a bien été enregistré !"
            );
            
            //Redirige vers l'affichage de l'annonce
            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet d'afficher le formulaire d'étidion
     *
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager)
    {
        //createForm permet de créer un formulaire externe
        $form = $this->createForm(AnnonceType::class, $ad);

        //handleRequest() permet de parcourir la requête et d'extraire les informations du form
        $form->handleRequest($request);

         //issubmitted() permet de savoir si le formulaire a été soumis ou pas / isValid() permet de savoir si le formulaire est valide par rapport au règles en place
         if ($form->isSubmitted() && $form->isValid()) {

            //Persist chaque(s) image(s) de l'annonce
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            
            //Prévient doctrine qu'on veut sauver
            $manager->persist($ad);
            //Envoie la requête SQL
            $manager->flush();
            
            $this->addFlash(
                "success",
                "Les modifications de l'anonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrées !"
            );
            
            //Redirige vers l'affichage de l'annonce
            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(), 
            'ad' => $ad
        ]);
    }

    /**
     * Permet d'afficher une seule annonce
     * La fonction recupère l'annonce $ad à partir de son slug grace au PARAM CONVERTER
     * 
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @return Response
     */
    public function show(Ad $ad)
    {
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

}
