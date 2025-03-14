<?php

// src/Service/UploadEntityManager.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Exception;
use App\Entity\Invoice;
use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Entity\RejectFile;
use App\Service\Tools;
use DateTimeImmutable;
// TODO songer a mettre en place des transaction commit() ou rollback() 
class UploadEntityManager
{
    private $parameterBag;
    private $tools;
    private $entMan;
    private $clientRepo;

    public function __construct(ParameterBagInterface $parameterBag, Tools $tools, EntityManagerInterface $entMan, ClientRepository $clientRepo)
    {
        $this->tools = $tools;
        $this->parameterBag = $parameterBag;
        $this->entMan = $entMan;
        $this->clientRepo = $clientRepo;
    }

    public function createEntityClient(array $arrayDto): ?Client
    {
        $paramClientRepo = [
            "nom" => $arrayDto["client"]->getNom(),
            "prenom"  => $arrayDto["client"]->getPrenom(),
            "adresse"   => $arrayDto["client"]->getAdresse()
        ];
        $existingClients = $this->clientRepo->findDuplicate($paramClientRepo);
        $selectedClient = [
            "client" => null,
            "score"  => 0
        ];
        foreach ($existingClients as $key => $Client) {
            $compareScore = $this->tools->compareStrings($Client->getAdresse(), $arrayDto["client"]->getAdresse());
            if ($compareScore["score"] > $selectedClient["score"]) {
                $selectedClient["client"] = $Client;
                $selectedClient["score"] = $compareScore["score"];
                if ($compareScore["score"] == 100) {
                    break;
                }
            }
        }
        if ($selectedClient["score"] < 95) {
            try {
                // Test si client existe 
                $client = new Client();
                $client->setNom($arrayDto["client"]->getNom());
                $client->setPrenom($arrayDto["client"]->getPrenom());
                $client->setAdresse($arrayDto["client"]->getAdresse());
                $client->setCreatedAt(new DateTimeImmutable("now"));

                // TODO tester si le client existe et si la date est + ancienne que getInitializedAt()
                $client->setInitializedAt($arrayDto["facture"]->getDate());
                $this->entMan->persist($client);
                $this->entMan->flush();
                return $client;
            } catch (Exception $e) {
                return null;
            }
        }
        return $selectedClient["client"];
    }

    public function createEntityInvoice(array $arrayDto): ?Invoice
    {
        try {
            $invoice = new Invoice();
            // $invoice->setFilename($file->getFilename());
            // $invoice->setOriginalFilename($uploadedFile->getClientOriginalName());
            // $invoice->setUploadedAt(new DateTimeImmutable("now"));
            // $invoice->setResponseIa($arrayDto["responseJson"]->getResponseJson());
            // $invoice->setDirname($file->getRealPath());
            // $this->entMan->persist($invoice);
            // $this->entMan->flush();
            return $invoice;
        } catch (Exception $e) {
            return null;
        }
    }


    public function createEntityRejectFile(File $file, UploadedFile $uploadedFile, array $arrayDto): ?RejectFile
    {
        try {
            $rejectFile = new RejectFile();
            $rejectFile->setFilename($file->getFilename());
            $rejectFile->setOriginalFilename($uploadedFile->getClientOriginalName());
            $rejectFile->setUploadedAt(new DateTimeImmutable("now"));
            $rejectFile->setResponseIa($arrayDto["responseJson"]->getResponseJson());
            $rejectFile->setDirname($file->getRealPath());
            $this->entMan->persist($rejectFile);
            $this->entMan->flush();
            return $rejectFile;
        } catch (Exception $e) {
            return null;
        }
    }
}
