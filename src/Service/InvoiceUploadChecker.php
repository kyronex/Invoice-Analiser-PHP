<?php

// src/Service/InvoiceUploadChecker.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;
//use App\Entity\Invoice;
use App\Dto\Mistral\TypeDoc\TypeDoc;
use App\Dto\Mistral\Autre\Autre;
use App\Dto\Mistral\Client\Client;
use App\Dto\Mistral\Facture\Facture;
use App\Dto\Mistral\Produits\Produits;
use App\Service\ApiMistral;
use App\Service\Tools;
use App\Service\InvoiceFileManager;
use App\Service\DtoValidator;


class InvoiceUploadChecker
{
    private $slugger;
    private $entMan;
    private $parameterBag;
    private $apiMistral;
    private $tools;
    private $invoiceFileManager;
    private $dtoValidator;


    public function __construct(
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager,
        ParameterBagInterface $parameterBag,
        ApiMistral $apiMistral,
        Tools $tools,
        InvoiceFileManager $invoiceFileManager,
        DtoValidator $dtoValidator
    ) {
        $this->slugger = $slugger;
        $this->entMan = $entityManager;
        $this->parameterBag = $parameterBag;
        $this->apiMistral = $apiMistral;
        $this->tools = $tools;
        $this->dtoValidator = $dtoValidator;
        $this->invoiceFileManager = $invoiceFileManager;
    }

    public function checker(UploadedFile $file): bool
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        try {
            $file->move(
                $this->parameterBag->get('invoices_directory'),
                $newFilename
            );
        } catch (FileException $e) {
            //dump($e);
            return false;
        }
        // $invoice = new Invoice();
        // $invoice->setFilename($newFilename);
        // $invoice->setOriginalFilename($originalFilename);
        // $invoice->setDirname($this->parameterBag->get('invoices_directory') . $newFilename);
        // $invoice->setUploadedAt(new \DateTimeImmutable());

        // dump("invoice");
        // dump($invoice);

        $response = $this->apiMistral->getChatCompletionDoc($this->parameterBag->get('invoices_directory') . $newFilename);

        if ($response) {
            // dump("this->apiMistral->getApiResponseFormat('array')");
            // dump($this->apiMistral->getApiResponseFormat('array'));

            $dtoTypeDoc = new TypeDoc($this->apiMistral->getApiResponseFormat('array'));
            $dtoAutre = new Autre($this->apiMistral->getApiResponseFormat('array'));
            $dtoClient = new Client($this->apiMistral->getApiResponseFormat('array'));
            $dtoFacture = new Facture($this->apiMistral->getApiResponseFormat('array'));
            $dtoProduits = new Produits($this->apiMistral->getApiResponseFormat('array'));

            $arrayDto = [
                'autre' => $dtoAutre,
                'client' => $dtoClient,
                'facture' => $dtoFacture,
                'produits' => $dtoProduits
            ];

            dump("arrayDto");
            dump($arrayDto);

            $dtoErrors = $this->dtoValidator->validateArrayDto($arrayDto);
            
            dump("dtoErrors");
            dump($dtoErrors);
            // $errors = $this->validator->validate($dtoFacture);
            // dump("errors");
            // dump($errors);
            exit();
            if ($dtoTypeDoc->getType() == "Facture") {
                dump("In if Facture");

                $this->invoiceFileManager->firstMoveToStorageError($file);
                $this->invoiceFileManager->firstMoveToStorage($file, $dtoTypeDoc);

                // dump("Client");
                // dump($dtoClient->getClient());
                // dump("FIN Client");

                // dump("Facture");
                // dump($dtoFacture->getFacture());
                // dump("FIN Facture");

                // dump("Produits");
                // dump($dtoProduits->getProduits());
                // dump("FIN Produits");
            } else {
                dump("In else Facture");
            }
        } else {
            $this->invoiceFileManager->firstMoveToStorageError($file);
        }
        return $response;
    }


}
