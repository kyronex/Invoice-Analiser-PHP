<?php

// src/Service/Tools.php
namespace App\Service;

use Normalizer;

class Tools
{
    public function __construct() {}

    /**
     * Combine plusieurs algorithmes pour une meilleure précision
     */
    public function compareStrings(string $str1, string $str2): array
    {
        // Normalisation
        $str1 = $this->cleanString($str1);
        $str2 = $this->cleanString($str2);

        // Différentes métriques
        $levenshtein = levenshtein($str1, $str2);
        similar_text($str1, $str2, $similarPercent);
        $soundexMatch = soundex($str1) == soundex($str2);
        $metaphoneMatch = metaphone($str1) == metaphone($str2);

        // Score composite
        $score = 0;
        $score += (100 - min(100, ($levenshtein / max(strlen($str1), strlen($str2))) * 100)) * 1.6; 
        $score += $similarPercent * 1.4; 
        $score += $soundexMatch ? 25 : 0;
        $score += $metaphoneMatch ? 25 : 0;

        return [
            'score' => $score / 3.5,  //(0-100%)
            'details' => [
                'levenshtein' => $levenshtein, //(plus c'est bas, mieux c'est)
                'similarText' => $similarPercent, //(0-100%)
                'soundex' => $soundexMatch,
                'metaphone' => $metaphoneMatch
            ]
        ];
    }

    public function cleanString(string $string): ?string
    {
        // Supprimer les espaces et les caractères de contrôle invisibles
        $cleaned = preg_replace('/\p{Cc}/u', '', $string);
        $cleaned = preg_replace('/\s+/', ' ', $string);

        $cleaned = str_replace(["\r", "\n"], '', $cleaned);
        $cleaned = str_replace("\t", '', $cleaned);

        $cleaned = $this->removeAccents($cleaned);
        $cleaned = mb_trim($cleaned, "UTF-8");

        return $cleaned;
    }

    public function removeAccents(string $string): ?string
    {
        $normalized = normalizer_normalize($string, Normalizer::FORM_D);
        // Filtrer les caractères non ASCII
        $cleaned = preg_replace('/[^\x00-\x7F]/u', '', $normalized);
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
