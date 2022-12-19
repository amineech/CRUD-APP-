<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController {

    #[Route('/', 'accueil')]
    public function AccueilAction() {
        // se connecter
        return $this->redirectToRoute('login');
    }

}