<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OllamaService;

class DefaultController extends AbstractController
{
    private OllamaService $ollamaService;

    public function __construct(OllamaService $ollamaService)
    {
        $this->ollamaService = $ollamaService;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $response = $this->ollamaService->getMistralResponse("Détermine moi l'ordre de gravité de cette situation parmis \"critique\", \"modérée\",
         \"légère\" ou \"Ok\". Je ne veux que tu réponde qu'avec un seul mot qui sera l'un des 4 proposé précédemment sachant qu'un état critique nécessite une intervention médicale importante dans les moindres délais, une situation modérée nécessitera une intervention médicale 
         dans un délais plus ou moins court, une situation légère nécessitera un rendez vous au médecin généraliste par exemple et une situation ok ne nécessitera aucune intervention 
         médicale : La photo montre une boule de pue à l'endroit de l'opération de l'apendice !");

        return $this->render('mistral.html.twig', [
            'response' => $response,
        ]);
    }
}
