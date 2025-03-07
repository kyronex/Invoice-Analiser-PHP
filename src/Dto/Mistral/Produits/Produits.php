<?php

namespace App\Dto\Mistral\Produits;

use App\Dto\Mistral\Produits\Produit;


class Produits
{
    private array $produits;

    public function __construct(array $data)
    {
        $this->createDtoProduits($data);
        
    }

    private function createDtoProduits(array $data)
    {
        if (isset($data["Produits"])) {
            foreach ($data["Produits"] as $value) {
                $dtoProduit = new Produit($value);
                $this->produits[] = $dtoProduit->getProduit();
            }
        }        
    }

    public function getProduits(): array
    {
        return $this->produits;
    }

    public function getOneProduits(int $key): array|false
    {
        if (array_key_exists($key, $this->getProduits())) {
           return $this->produits[$key];
        } else {
            return false;
        }
    }

}
