<?php

namespace App\Dto\Mistral\Client;

use App\Service\Tools;

class Client
{
    private $tools;

    private array $client;
    private string $nom;
    private string $prenom;
    private string $adresse;
    private array $autre;

    public function __construct(array $data)
    {
        $this->tools = new Tools();
        $this->client = $this->tools->arrayizeData($data["Client"]);
        $this->nom = $data["Client"]["nomClient"] ?? "";
        $this->prenom = $data["Client"]["prenomClient"] ?? "";
        $this->adresse = $data["Client"]["adresseClient"] ?? "";
        $this->autre = $this->tools->arrayizeData($data["Client"]["autreClient"]);
    }

    public function getClient(): array
    {
        return $this->client;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function getAutre(): array
    {
        return $this->autre;
    }
}
