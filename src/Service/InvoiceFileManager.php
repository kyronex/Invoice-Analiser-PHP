<?php

// src/Service/InvoiceFileManager.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\ApiMistral;
use App\Service\Tools;
use App\Entity\Invoice;
use App\Dto\Mistral\TypeDoc\TypeDoc;
use App\Dto\Mistral\Autre\Autre;
use App\Dto\Mistral\Client\Client;
use App\Dto\Mistral\Facture\Facture;
use App\Dto\Mistral\Produits\Produits;
use Symfony\Bundle\MakerBundle\Str;

class InvoiceFileManager
{
    private $parameterBag;
    private $tools;

    public function __construct(ParameterBagInterface $parameterBag, Tools $tools)
    {
        $this->tools = $tools;
        $this->parameterBag = $parameterBag;
    }


    public function firstMoveToStorage(UploadedFile $file , TypeDoc $dtoTypeDoc) :bool
    {
        return true ;
    }

    public function firstMoveToStorageError(UploadedFile $file ) :bool
    {
        dump("firstMoveToStorageError");
        dump($this->parameterBag->get("dir_storage_errors"));
        exit();
        // TODO deplace code vers UPLOADS ou REJECTS Vers Service approprier
        // rename($doc, $_ENV["DIR_STORAGE_UPLOADS"] . pathinfo($doc, PATHINFO_BASENAME));
        return true ;
    }
}
