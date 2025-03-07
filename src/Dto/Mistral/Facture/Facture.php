<?php

namespace App\Dto\Mistral\Facture;

use App\Service\Tools;
use DateTimeImmutable;

class Facture
{
    private $tools;
    private array $facture;
    private string $reference;
    private DateTimeImmutable  $date;
    private array $autre;

    public function __construct(array $data)
    {
        $this->tools = new Tools();
        $this->facture = $this->tools->arrayizeData($data["Facture"]);
        $this->reference = $data["Facture"]["refFacture"] ?? "";
        $date = DateTimeImmutable::createFromFormat("Y-m-d", $data["Facture"]["dateFacture"]);
        $this->date = $date ?: new DateTimeImmutable("1900-01-01");
        $this->autre = $this->tools->arrayizeData($data["Facture"]["autreFacture"]);
    }

    public function getFacture(): array
    {
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
