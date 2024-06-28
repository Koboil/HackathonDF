<?php

namespace App\Controller;

use App\Service\OpenAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OllamaService;

class DefaultController extends AbstractController
{

    public function __construct(
        protected OllamaService $ollamaService,
        protected OpenAIService $openAIService,
    ) {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('mistral.html.twig');
    }

    #[Route('/upload', name: 'upload')]
    public function upload(Request $request): Response
    {
        $file = $request->files->get('file');
        $medQuestion = $request->get('question');

        $fileContent = base64_encode(file_get_contents($file->getPathname()));

        $openAiResponse = $this->openAIService->describeImageFromBase64($fileContent, $medQuestion);
        $ollamaResponse = $this->ollamaService->determineSeverity($openAiResponse);

        return $this->json([
            'openAiResponse' => $openAiResponse,
            'ollamaResponse' => $ollamaResponse,
        ]);
    }
}
