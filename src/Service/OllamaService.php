<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Component\String\UnicodeString;

class OllamaService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getMistralResponse($data)
    {
        $url = $_SERVER['OLLAMA_URL'] ?? $_ENV['OLLAMA_URL'];

        try {
            $response = $this->client->request(
                'POST',
                $url, // Assurez-vous que c'est la bonne URL
                [
                    'json' => [
                        'model' => 'mistral',
                        'prompt' => $data,
                        'stream' => false
                    ]
                ]
            );

            return $response->toArray();

        } catch (TransportExceptionInterface | ClientExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function determineSeverity(string $openAiResponse): string
    {
        $prompt = "Détermine moi l'ordre de gravité de cette situation parmis 'critique', 'modérée', 'légère' ou 'Ok'. Je ne veux que tu réponde qu'avec un seul mot qui sera l'un des 4 proposé précédemment sachant qu'un état critique nécessite une intervention médicale importante dans les moindres délais, une situation modérée nécessitera une intervention médicale dans un délais plus ou moins court, une situation légère nécessitera un rendez vous au médecin généraliste par exemple et une situation ok ne nécessitera aucune intervention médicale : La photo montre une boule de pue à l'endroit de l'opération de l'apendice ! Le texte à analyser : {$openAiResponse}. Oublie pas que tu ne dois répondre que 'critique', 'modérée', 'légère' ou 'Ok' sans d'autre mot dans ta réponse !!";

        $ollamaResponse = $this->getMistralResponse($prompt);

        return $ollamaResponse['response'] ?? '?';
    }
}
