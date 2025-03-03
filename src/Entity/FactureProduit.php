<?php

namespace App\Entity;

use App\Repository\FactureProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureProduitRepository::class)]
class FactureProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'factureProduits', targetEntity: Invoice::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $invoice;

    #[ORM\ManyToOne(inversedBy: 'factureProduits', targetEntity: Produit::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $produit;

    #[ORM\Column]
    private ?int $quantite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): self
    {
        $this->invoice = $invoice;
        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }
}
