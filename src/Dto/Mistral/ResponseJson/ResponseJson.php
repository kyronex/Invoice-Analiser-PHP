<?php

namespace App\Dto\Mistral\ResponseJson;

use Symfony\Component\Validator\Constraints as Assert;

class ResponseJson
{
    #[Assert\NotBlank]
    private string $responseJson;

    public function __construct(string $data)
    {
        $this->responseJson = $data;
    }

    public function getResponseJson(): string
    {
        return $this->responseJson;
    }
}
