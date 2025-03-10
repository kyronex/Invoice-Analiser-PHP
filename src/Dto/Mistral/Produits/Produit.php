<?php

namespace App\Dto\Mistral\Produits;

use App\Service\Tools;

class Produit
{
    private $tools;
    private array $produit;
    private string $description;
    private string $nom;
    private string $reference;
    private int $quantite;
    private float $tauxTva;
    private float $prixHT;

    public function __construct(array $data)
    {
        $this->tools = new Tools();
        $this->description = $data["descriptionProduit"] ?? "";
        $this->nom = $data["nomProduit"] ?? "";
        $this->reference = $data["refProduit"] ?? "";
        $this->quantite = $data["quantiteProduit"] ?? 0;
        $this->tauxTva = $data["tauxTvaProduit"] ?? 0;
        $this->prixHT = $data["prixHTProduit"] ?? 0;
        $this->produit = $this->getProduit();

    }

    public function getProduit(): array
    {
        $this->produit["description"] = $this->getDescription();
        $this->produit["nom"] = $this->getNom();
        $this->produit["reference"] = $this->getReference();
        $this->produit["quantite"] = $this->getQuantite();
        $this->produit["tauxTva"] = $this->getTauxTva();
        $this->produit["prixHT"] = $this->getPrixHT();
        return $this->produit;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }
    
    public function getTauxTva(): float
    {
        return $this->tauxTva;
    }

    public function getPrixHT(): float
    {
        return $this->prixHT;
    }
}
