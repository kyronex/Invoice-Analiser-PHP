<?php

// src/Service/ApiMistral.php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Service\Tools;
use App\Service\ApiNgrok;

// TODO definir les type de sortie des methode 
class ApiMistral
{
    private $client;
    private $apiNgrok;
    private $tools;
    private string $apiKey;
    private string $fileConf;
    private ?string $urlNgrokDoc;
    private ?object $apiConf;
    private ?int $apiResponseStatusCode;
    private ?object $apiResponseRaw;
    private ?array $apiResponseToArray;
    private ?array $apiResponseUsage;
    private ?string $apiResponseMessage;
    private ?object $apiResponseObjet;
    private ?array $apiResponseArray;

    public function __construct(HttpClientInterface $client, string $apiKey, string $fileConf, ApiNgrok $apiNgrok,)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->fileConf = $fileConf;
        $this->apiNgrok = $apiNgrok;
        $this->urlNgrokDoc = null;
        $this->apiConf = null;
        $this->apiResponseStatusCode = null;
        $this->apiResponseRaw = null;
        $this->apiResponseToArray = null;
        $this->apiResponseUsage = null;
        $this->apiResponseMessage = null;
        $this->apiResponseObjet = null;
        $this->apiResponseArray = null;
        $this->tools = new Tools();

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

    public function getChatCompletionDoc(string $doc): bool
    {
        $this->setUrlNgrokDoc($doc);

        $jsonRes = [
            'model' => $this->apiConf->modelM,
            'response_format' => [
                'type' => 'json_object',
            ],
            'messages' => [
                [
                    'content' =>  $this->apiConf->systemPromptInvoice,
                    'role' => 'system',
                ],
                [
                    'content' => [
                        [
                            'type' => 'document_url',
                            'document_url' => $this->getUrlNgrokDoc()
                        ],
                    ],
                    'role' => 'user',
                ],
                [
                    'content' => $this->apiConf->userPromptInvoice,
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

        $this->setApiResponseStatusCode($response->getStatusCode());

        if ($this->getApiResponseStatusCode() == 200) {
            $this->setApiResponseRaw($response);
            $this->setApiResponseToArray($response->toArray());
            $this->setApiResponseUsage($response->toArray()["usage"]);
            $this->setApiResponseMessage($response->toArray()["choices"][0]["message"]["content"]);
            $this->setApiResponseObjet();
            $this->setApiResponseArray();
            return true;
        } else {
            return false;
        }
    }

    private function setUrlNgrokDoc(string $doc)
    {
        $this->urlNgrokDoc = str_replace("/var/www/html/public", $this->apiNgrok->getUrl(), $doc);
    }

    public function getUrlNgrokDoc(): ?string
    {
        return $this->urlNgrokDoc;
    }

    private function setApiResponseStatusCode(int $statusCode)
    {
        $this->apiResponseStatusCode = $statusCode;
    }

    public function getApiResponseStatusCode(): ?int
    {
        return $this->apiResponseStatusCode;
    }

    private function setApiResponseObjet()
    {
        $this->apiResponseObjet = json_decode($this->tools->cleanString($this->getApiResponseMessage()));
    }

    private function setApiResponseArray()
    {
        $this->apiResponseArray = json_decode($this->tools->cleanString($this->getApiResponseMessage()), true);
    }

    public function getApiResponseFormat(string $format): \stdClass|array
    {
        switch ($format) {
            case 'array':
                return $this->apiResponseArray;
                break;

            default:
                return $this->apiResponseObjet;
                break;
        }
    }

    private function setApiResponseMessage(string $message)
    {
        $this->apiResponseMessage = $message;
    }

    public function getApiResponseMessage(): string
    {
        return $this->apiResponseMessage;
    }

    private function setApiResponseUsage(array $usage)
    {
        $this->apiResponseUsage = $usage;
    }

    public function getApiResponseUsage(): array
    {
        return $this->apiResponseUsage;
    }

    private function setApiResponseToArray(array $response)
    {
        $this->apiResponseToArray = $response;
    }

    public function getApiResponseToArray(): array
    {
        return $this->apiResponseToArray;
    }

    private function setApiResponseRaw(object $response)
    {
        $this->apiResponseRaw = $response;
    }

    public function getApiResponseRaw(): object
    {
        return $this->apiResponseRaw;
    }

    public function getApiConf(): object
    {
        return $this->apiConf;
    }
}
