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
        $response = $this->ollamaService->getMistralResponse("Décris moi la théorie de la relativité stp");

        return $this->render('mistral.html.twig', [
            'response' => $response,
        ]);
    }
}
