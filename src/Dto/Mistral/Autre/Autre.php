<?php

namespace App\Dto\Mistral\Autre;

use App\Service\Tools;
// TODO ajout MissingKeyTest et MissingKeyException , DefaultValue en fonction des different typage sur tout les DTO 
class Autre
{
    private $tools;
    private array $autre;

    public function __construct(array $data)
    {
        $this->tools = new Tools();
        $this->autre = $this->tools->arrayizeData($data["Autre"]);
    }

    public function getAutre(): array
    {
        return $this->autre;
    }
}
