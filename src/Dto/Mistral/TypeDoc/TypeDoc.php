<?php
namespace App\Dto\Mistral\TypeDoc;

class TypeDoc
{
    private string $type;

    public function __construct(array $data)
    {
        $this->type = $data["TypeDoc"] ?? "";
    }

    public function getType(): string
    {
        return $this->type;
    }
}