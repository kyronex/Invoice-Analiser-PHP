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
    private $apiKey;
    private $fileConf;
    private $apiNgrok;
    private $urlNgrokDoc;
    private $apiConf;
    private $apiResponseStatusCode;
    private $apiResponseRaw;
    private $apiResponseToArray;
    private $apiResponseUsage;
    private $apiResponseMessage;
    private $apiResponseObjet;
    private $apiResponseArray;
    private $tools;

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

    private function setUrlNgrokDoc($doc)
    {
        $this->urlNgrokDoc = str_replace("/var/www/html/public", $this->apiNgrok->getUrl(), $doc);
    }

    public function getUrlNgrokDoc()
    {
        return $this->urlNgrokDoc;
    }

    private function setApiResponseStatusCode($statusCode)
    {
        $this->apiResponseStatusCode = $statusCode;
    }

    public function getApiResponseStatusCode()
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

    public function getApiResponseFormat($format): \stdClass|array
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

    private function setApiResponseMessage($message)
    {
        $this->apiResponseMessage = $message;
    }

    public function getApiResponseMessage()
    {
        return $this->apiResponseMessage;
    }

    private function setApiResponseUsage($usage)
    {
        $this->apiResponseUsage = $usage;
    }

    public function getApiResponseUsage()
    {
        return $this->apiResponseUsage;
    }

    private function setApiResponseToArray($response)
    {
        $this->apiResponseToArray = $response;
    }

    public function getApiResponseToArray()
    {
        return $this->apiResponseToArray;
    }

    private function setApiResponseRaw($response)
    {
        $this->apiResponseRaw = $response;
    }

    public function getApiResponseRaw()
    {
        return $this->apiResponseRaw;
    }

    public function getApiConf()
    {
        return $this->apiConf;
    }
}
