<?php

// src/Service/UploadEntityManager.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Invoice;
use App\Entity\RejectFile;
use App\Service\Tools;


class UploadEntityManager
{
    private $parameterBag;
    private $tools;
    private $entMan;

    public function __construct(ParameterBagInterface $parameterBag, Tools $tools, EntityManagerInterface $entMan,)
    {
        $this->tools = $tools;
        $this->parameterBag = $parameterBag;
        $this->entMan = $entMan;
    }


    public function createEntityRejectFile(File $file, UploadedFile $uploadedFile, array $arrayDto): bool
    {
        dump("createEntityRejectFile");
        dump("file");
        dump($file);
        dump("uploadedFile");
        dump($uploadedFile);
        dump("arrayDto");
        dump($arrayDto);

        $rejectFile = new RejectFile();
        $rejectFile->setFilename($file->getFilename());
        $rejectFile->setOriginalFilename($uploadedFile->getClientOriginalName());
        // $rejectFile->setUploadedAt( $uploadedAt);
        // $rejectFile->setResponseIa( $responseIa);
        // $rejectFile->setDirname( $dirname);

        dump("rejectFile");
        dump($rejectFile);
        return true;
    }
}
