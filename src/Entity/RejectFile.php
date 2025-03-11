<?php

namespace App\Entity;

use App\Repository\RejectFileRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: RejectFileRepository::class)]
class RejectFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $filename = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $originalFilename = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(DateTimeImmutable::class)]
    private ?DateTimeImmutable $uploadedAt = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $responseIa = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $dirname = null;


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

    public function getUploadedAt(): ?DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(DateTimeImmutable $uploadedAt): self
    {
        $this->uploadedAt = $uploadedAt;
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
}
