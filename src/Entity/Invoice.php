<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\Column(length: 255)]
    private ?string $originalFilename = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $uploadedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $factoredAt = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $responseIa = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    private ?string $dirname = null;

    #[ORM\ManyToOne(inversedBy: 'invoices', targetEntity: Client::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $client;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: FactureProduit::class)]
    private $factureProduits;

    public function __construct()
    {
        $this->factureProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }

    public function setOriginalFilename(string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;
        return $this;
    }


    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeImmutable $uploadedAt): self
    {
        $this->uploadedAt = $uploadedAt;
        return $this;
    }

    public function getFactoredAt(): ?\DateTimeImmutable
    {
        return $this->factoredAt;
    }

    public function setFactoredAt(\DateTimeImmutable $factoredAt): self
    {
        $this->factoredAt = $factoredAt;
        return $this;
    }

    public function getResponseIa(): ?string
    {
        return $this->responseIa;
    }

    public function setResponseIa(string $responseIa): self
    {
        $this->responseIa = $responseIa;
        return $this;
    }

    public function getDirname(): ?string
    {
        return $this->dirname;
    }

    public function setDirname(string $dirname): self
    {
        $this->dirname = $dirname;
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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Collection<int, FactureProduit>
     */
    public function getFactureProduit(): Collection
    {
        return $this->factureProduits;
    }
}
