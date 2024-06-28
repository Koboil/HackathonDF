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
        try {
            $response = $this->client->request(
                'POST',
                'http://ollama:11434/api/generate', // Assurez-vous que c'est la bonne URL
                [
                    'json' => [
                        'model' => 'mistral',
                        'prompt' => $data,
                        'stream' => false
                    ]
                ]
            );

            $content = $response->toArray();
            return json_decode(json_encode($content['response']));

        } catch (TransportExceptionInterface | ClientExceptionInterface | ServerExceptionInterface | RedirectionExceptionInterface $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
