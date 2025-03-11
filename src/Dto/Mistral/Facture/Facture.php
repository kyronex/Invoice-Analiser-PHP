<?php

namespace App\Dto\Mistral\Facture;

use App\Service\Tools;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable;

class Facture
{
    private $tools;
    private array $facture;
    #[Assert\NotBlank]
    private string $reference;
    #[Assert\NotNull]
    #[Assert\Type(DateTimeImmutable::class)]
    private DateTimeImmutable  $date;
    private array $autre;

    public function __construct(array $data)
    {
        $this->tools = new Tools();
        $this->reference = $data["Facture"]["refFacture"] ?? "";
        $date = DateTimeImmutable::createFromFormat("Y-m-d", $data["Facture"]["dateFacture"]);
        $this->date = $date ?: new DateTimeImmutable("1900-01-01");
        if (isset($data["Facture"]["autreFacture"])) {
            $this->autre = $this->tools->arrayizeData($data["Facture"]["autreFacture"]);
        } else {
            $this->autre = $this->tools->arrayizeData("");
        }
        $this->facture = $this->getFacture();
    }

    public function getFacture(): array
    {
        $this->facture["reference"] = $this->getReference();
        $this->facture["date"] = $this->getDate();
        $this->facture["autre"] = $this->getAutre();
        return $this->facture;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getAutre(): array
    {
        return $this->autre;
    }
}
