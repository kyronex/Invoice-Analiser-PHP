<?php

namespace App\Entity;

use App\Repository\EvoProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvoProduitRepository::class)]
class EvoProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'evoProduits', targetEntity: Produit::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $produit;

    #[ORM\Column(type: 'float')]
    private ?float $prix = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $changedAt = null;

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setChangedAt(\DateTimeImmutable $changedAt): self
    {
        $this->changedAt = $changedAt;
        return $this;
    }

    public function getChangedAt(): ?\DateTimeImmutable
    {
        return $this->changedAt;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }
}
