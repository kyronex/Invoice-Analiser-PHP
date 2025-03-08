<?php

// src/Service/InvoiceUploadChecker.php
namespace App\Service;

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


class InvoiceUploadChecker
{
    private $slugger;
    private $entMan;
    private $parameterBag;
    private $apiMistral;
    private $tools;


    public function __construct(
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager,
        ParameterBagInterface $parameterBag,
        ApiMistral $apiMistral,
        Tools $tools
    ) {
        $this->slugger = $slugger;
        $this->entMan = $entityManager;
        $this->parameterBag = $parameterBag;
        $this->apiMistral = $apiMistral;
        $this->tools = $tools;
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
        $invoice = new Invoice();
        $invoice->setFilename($newFilename);
        $invoice->setOriginalFilename($originalFilename);
        $invoice->setDirname($this->parameterBag->get('invoices_directory') . $newFilename);
        $invoice->setUploadedAt(new \DateTimeImmutable());

        dump("invoice");
        dump($invoice);

        $response = $this->apiMistral->getChatCompletionDoc($this->parameterBag->get('invoices_directory') . $newFilename);

        dump("this->apiMistral->getUrlNgrokDoc()");
        dump($this->apiMistral->getUrlNgrokDoc());
        dump("this->apiMistral->getApiResponseStatusCode()");
        dump($this->apiMistral->getApiResponseStatusCode());        
        dump("response");
        dump($response);

        if ($response) {
            dump("this->apiMistral->getApiResponseFormat('array')");
            dump($this->apiMistral->getApiResponseFormat('array'));

            $dtoTypeDoc = new TypeDoc($this->apiMistral->getApiResponseFormat('array'));
            $dtoAutre = new Autre($this->apiMistral->getApiResponseFormat('array'));
            $dtoClient = new Client($this->apiMistral->getApiResponseFormat('array'));
            $dtoFacture = new Facture($this->apiMistral->getApiResponseFormat('array'));
            $dtoProduits = new Produits($this->apiMistral->getApiResponseFormat('array'));
            if ($dtoTypeDoc->getType() == "Facture") {
                dump("In if Facture");

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
        }
        return $response ;
    }
}
