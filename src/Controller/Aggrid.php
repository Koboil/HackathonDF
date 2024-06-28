<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Aggrid extends AbstractController
{   
    #[Route('/aggrid', name: 'aggrid')]
    public function index()
    {
        // faire remonter les données
        $params = [];
        return $this->render('aggrid.html.twig',$params);
    }
}