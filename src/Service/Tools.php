<?php

// src/Service/Tools.php
namespace App\Service;

class Tools
{
    public function __construct() {}

    public function cleanString(string $string): ?string
    {
        // Supprimer les espaces et les caractères de contrôle invisibles
        $cleaned = preg_replace('/\p{Cc}/u', '', $string);
        $cleaned = preg_replace('/\s+/', ' ', $string);

        $cleaned = str_replace(["\r", "\n"], '', $cleaned);
        $cleaned = str_replace("\t", '', $cleaned);

        $cleaned = mb_trim($cleaned, "UTF-8");

        return $cleaned;
    }

    public function arrayizeData($data): ?array
    {
        $arrayliseData = [];
        if (isset($data)) {
            if (is_array($data)) {
                $arrayliseData = $data;
            } else {
                $arrayliseData = array($data);
            }
        }
        return $arrayliseData;
    }
}
