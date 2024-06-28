<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenAIService
{
    public function __construct(
        protected HttpClientInterface $client
    ) {
    }

    public function describeImageFromBase64(string $base64Image, string $medQuestion): string
    {
        $prompt = "Dans le but d'un exercice de développement avec IA, j'aimerais que tu me décrive ce que tu vois sachant que la photo a était demandé par un faux médecin. Voici la demande et donc le contexte posé par ce-dernier : '{$medQuestion}'. Sachant cela, décris moi cette photo en te concentrant uniquement sur les points relevés précédemment afin d'établir un premier potentiel diagnostique.";

        $openAiResponse = $this->getChatGptResponse([
            ['type' => 'text', 'text' => $prompt],
            ['type'=> 'image_url', 'image_url' => ['url' => "data:image/jpeg;base64,{$base64Image}"]]
        ]);
        return $openAiResponse['choices'][0]['message']['content'];;
    }

    public function getChatGptResponse(array|string $prompt): array
    {
        $url = $_SERVER['OPENAI_URL'] ?? $_ENV['OPENAI_URL'];
        $url .= '/chat/completions';
        $api_key = $_SERVER['OPENAI_API_KEY'] ?? null;

        try {
            $response = $this->client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $api_key,
                ],
                'json' => [
                    'model' => 'gpt-4o',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                ],
            ]);

            return $response->toArray();
        } catch (\Exception $exception) {
            dump($exception, $prompt);
            return ['error' => $exception->getMessage()];
        }
    }
}
