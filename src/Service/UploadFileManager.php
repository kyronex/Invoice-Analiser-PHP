<?php

// src/Service/UploadFileManager.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadFileManager
{
    private $parameterBag;
    private const TARGET_DIRECTORIES = [
        "invoice" => "dir_storage_invoices",
        "reject"  => "dir_storage_rejects",
        "error"   => "dir_storage_errors"
    ];

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function moveToStorageTarget(File $file, string $target): ?File
    {
        try {
            if (!isset(self::TARGET_DIRECTORIES[$target])) {
                return null;
            }
            $targetDirectory = $this->parameterBag->get(self::TARGET_DIRECTORIES[$target]);
            $file->move($targetDirectory);
            return new File($targetDirectory . $file->getFilename());
        } catch (FileException $e) {
            //dump($e);
            return null;
        }
    }
}
