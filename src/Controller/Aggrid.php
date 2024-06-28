<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Aggrid extends AbstractController
{   


    #[Route('/aggrid', name: 'aggrid')]
    public function index(PatientRepository $PatientRepository)
    {
        $params = $PatientRepository->findPatientInfoBy();
        return $this->render('aggrid.html.twig',['gridData' => json_encode($params)]);
    }
}