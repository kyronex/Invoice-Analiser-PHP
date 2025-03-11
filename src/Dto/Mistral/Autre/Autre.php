<?php

namespace App\Dto\Mistral\Autre;

use App\Service\Tools;
class Autre
{
    private $tools;
    private array $autre;

    public function __construct(array $data)
    {
        $this->tools = new Tools();
        if (isset($data["Autre"])) {
            $this->autre = $this->tools->arrayizeData($data["Autre"]);
        } else {
            $this->autre = $this->tools->arrayizeData("");
        }
    }

    public function getAutre(): array
    {
        return $this->autre;
    }
}
