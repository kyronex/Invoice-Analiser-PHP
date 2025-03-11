<?php

// src/Service/DtoValidator.php
namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoValidator
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateArrayDto(array $arrayDto): array
    {
        $dtoErrors = [];
        foreach ($arrayDto as $key => $dto) {
            $errors = $this->validator->validate($dto);
            if (count($errors) > 0) {
                $dtoErrors[$key] = $errors;
            }
        }
        return $dtoErrors;
    }
}
