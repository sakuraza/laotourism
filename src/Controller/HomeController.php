<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends Controller{
    /**
     * Montre la page qui dit bonjour
     * @Route("/salut/{prenom}/age/{age}", name="hello")
     * @Route("/salut", name="hello_base")
     * @Route("/salut/{prenom}/", name="hello_prenom")
     *
     * @return void
     */
    public function hello($prenom = "anonyme", $age=0){
        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom,
                'age' => $age
            ]
        );
    }
    
    /**
    * @Route("/", name="homepage")
    */
    public function home(){
        $prenoms = ['Lior' => 31, 'Joseph' =>12 , 'Anne' => 5];
        return $this->render(
            'home.html.twig',
            [
                'title' => "Bonjour à tous!!!",
                'age' => 12,
                'tableau' => $prenoms
            ]
        );
    }
}
?>