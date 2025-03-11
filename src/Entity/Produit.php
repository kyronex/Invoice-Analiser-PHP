<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $reference = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(DateTimeImmutable::class)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(DateTimeImmutable::class)]
    private ?DateTimeImmutable $initializedAt = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: FactureProduit::class)]
    private $factureProduits;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: EvoProduit::class)]
    private $evoProduits;

    public function __construct()
    {
        $this->factureProduits = new ArrayCollection();
        $this->evoProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getInitializedAt(): ?DateTimeImmutable
    {
        return $this->initializedAt;
    }

    public function setInitializedAt(DateTimeImmutable $initializedAt): self
    {
        $this->initializedAt = $initializedAt;
        return $this;
    }

    /**
     * @return Collection<int, FactureProduit>
     */
    public function getFactureProduits(): Collection
    {
        return $this->factureProduits;
    }

    /**
     * @return Collection<int, EvoProduit>
     */
    public function getEvoProduits(): Collection
    {
        return $this->evoProduits;
    }
}
