<?php

// src/Service/ApiMistral.php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiMistral
{
    private $client;
    private $apiKey;
    private $fileConf;
    private $apiConf;

    public function __construct(HttpClientInterface $client, string $apiKey, string $fileConf)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->fileConf = $fileConf;
        $this->apiConf = null;

        try {
            if (is_file($this->fileConf)) {
                $jsonContent = file_get_contents($this->fileConf);
                $this->apiConf = json_decode($jsonContent);
            } else {
                throw new \Exception("Erreur lors de la lecture du fichier de configuration.");
            }
            if ($this->apiConf == null) {
                throw new \Exception("Erreur lors de la recuperation de la configuration.");
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function getChatCompletionDoc(string $doc , string $prompt)
    {

        dump($doc) ; 

        $jsonRes = [
            'model' => $this->apiConf->modelP,
            'response_format' => [
                'type' => 'json_object',
            ],
            'messages' => [
                [
                    'content' =>  $this->apiConf->systemPrompt,
                    'role' => 'system',
                ],
                [
                    'content' => [
                        [
                            'type' => 'document_url',
                            // 'document_url' => $doc,
                            'document_url' => "https://rdelbaere.fr/cv",
                        ],
                    ],
                    'role' => 'user',
                ],
                [
                    'content' => $prompt,
                    'role' => 'user',
                ],
            ],
        ];

        $response = $this->client->request('POST', $this->apiConf->url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
            'json' => $jsonRes
        ]);

        return $response->toArray();
    }

    public function getApiConf()
    {
        return $this->apiConf;
    }
}
