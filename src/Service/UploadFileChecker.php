<?php

// src/Service/UploadFileChecker.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\ApiMistral;
use App\Dto\Mistral\TypeDoc\TypeDoc;
use App\Dto\Mistral\Autre\Autre;
use App\Dto\Mistral\Client\Client;
use App\Dto\Mistral\Facture\Facture;
use App\Dto\Mistral\Produits\Produits;
use App\Dto\Mistral\ResponseJson\ResponseJson;
use App\Service\DtoValidator;
use App\Service\UploadFileManager;
use App\Service\UploadEntityManager;

class UploadFileChecker
{
    private $parameterBag;
    private $slugger;
    private $apiMistral;
    private $dtoValidator;
    private $uploadFileManager;
    private $uploadEntityManager;

    public function __construct(
        ParameterBagInterface $parameterBag,
        SluggerInterface $slugger,
        ApiMistral $apiMistral,
        DtoValidator $dtoValidator,
        UploadFileManager $uploadFileManager,
        UploadEntityManager $uploadEntityManager
    ) {
        $this->parameterBag = $parameterBag;
        $this->slugger = $slugger;
        $this->apiMistral = $apiMistral;
        $this->dtoValidator = $dtoValidator;
        $this->uploadFileManager = $uploadFileManager;
        $this->uploadEntityManager = $uploadEntityManager;
    }

    public function checker(UploadedFile $uploadedFile): bool
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . "-" . uniqid() . "." . $uploadedFile->guessExtension();
        try {
            $uploadedFile->move(
                $this->parameterBag->get("invoices_directory"),
                $newFilename
            );
            $processFile = new File($this->parameterBag->get("invoices_directory") . $newFilename);
        } catch (FileException $e) {
            //dump($e);
            return false;
        }
        
        $response = $this->apiMistral->getChatCompletionDoc($processFile->getPathname());
        if ($response) {
            $dtoTypeDoc = new TypeDoc($this->apiMistral->getApiResponseFormat("array"));
            $dtoAutre = new Autre($this->apiMistral->getApiResponseFormat("array"));
            $dtoClient = new Client($this->apiMistral->getApiResponseFormat("array"));
            $dtoFacture = new Facture($this->apiMistral->getApiResponseFormat("array"));
            $dtoProduits = new Produits($this->apiMistral->getApiResponseFormat("array"));
            $dtoResponseJson = new ResponseJson($this->apiMistral->getApiResponseToArrayJson());

            $arrayDto = [
                "type" => $dtoTypeDoc,
                "autre" => $dtoAutre,
                "client" => $dtoClient,
                "facture" => $dtoFacture,
                "produits" => $dtoProduits,
                "responseJson" => $dtoResponseJson
            ];

            if ($dtoTypeDoc->getType() == "Facture") {
                //dump("In if Facture");
                $dtoErrors = $this->dtoValidator->validateArrayDto($arrayDto);
                if (count($dtoErrors) > 0) {
                    if ($processFile = $this->uploadFileManager->moveToStorageTarget($processFile, "reject")) {
                        $rejectFileEntity = $this->uploadEntityManager->createEntityRejectFile($processFile, $uploadedFile, $arrayDto);
                    } else {
                        return false;
                    }
                }
                if ($processFile = $this->uploadFileManager->moveToStorageTarget($processFile, "invoice")) {
                    $Client = $this->uploadEntityManager->createEntityClient($arrayDto);
                    //$invoiceFileEntity = $this->uploadEntityManager->createEntityInvoice($arrayDto);
                } else {
                    return false;
                }
            } else {
                if ($processFile = $this->uploadFileManager->moveToStorageTarget($processFile, "reject")) {
                    $rejectFileEntity = $this->uploadEntityManager->createEntityRejectFile($processFile, $uploadedFile, $arrayDto);
                } else {
                    return false;
                }
            }
        } else {
            $processFile = $this->uploadFileManager->moveToStorageTarget($processFile, "error");
        }
        return $response;
    }
}
