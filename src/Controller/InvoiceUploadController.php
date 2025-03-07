<?php

namespace App\Controller;

use App\Service\ApiMistral;
use App\Service\Tools;
use App\Dto\Mistral\TypeDoc\TypeDoc;
use App\Dto\Mistral\Autre\Autre;
use App\Dto\Mistral\Client\Client;
use App\Dto\Mistral\Facture\Facture;
use App\Dto\Mistral\Produits\Produits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\InvoiceType;
use App\Entity\Invoice;

class InvoiceUploadController extends AbstractController
{
    private $apiMistral;
    private $tools;


    public function __construct(ApiMistral $apiMistral)
    {
        $this->apiMistral = $apiMistral;
        $this->tools = new Tools();
    }

    #[Route('/invoice/upload', name: 'app_invoice_upload')]
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $entMan): Response
    {
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('invoices_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    //dump($e);
                    $this->addFlash('error', 'Erreur téléchargement du fichier');
                    return $this->redirectToRoute('app_upload');
                }

                $invoice->setFilename($newFilename);
                $invoice->setOriginalFilename($originalFilename);
                $invoice->setDirname($this->getParameter('invoices_directory') . $newFilename);
                $invoice->setUploadedAt(new \DateTimeImmutable());

                dump("invoice");
                dump($invoice);

                $response = $this->apiMistral->getChatCompletionDoc($this->getParameter('invoices_directory') . $newFilename);

                dump("this->apiMistral->getUrlNgrokDoc()");
                dump($this->apiMistral->getUrlNgrokDoc());

                dump("this->apiMistral->getApiResponseStatusCode()");
                dump($this->apiMistral->getApiResponseStatusCode());

                if ($response) {
                    dump("this->apiMistral->getApiResponseFormat('array')");
                    dump($this->apiMistral->getApiResponseFormat('array'));

                    $dtoTypeDoc = new TypeDoc($this->apiMistral->getApiResponseFormat('array'));
                    $dtoAutre = new Autre($this->apiMistral->getApiResponseFormat('array'));
                    $dtoClient = new Client($this->apiMistral->getApiResponseFormat('array'));
                    $dtoFacture = new Facture($this->apiMistral->getApiResponseFormat('array'));
                    $dtoProduits = new Produits($this->apiMistral->getApiResponseFormat('array'));
                    if ($dtoTypeDoc->getType() == "Facture") {
                        // dump("Client");
                        // dump($dtoClient->getClient());
                        // dump("FIN Client");

                        // dump("Facture");
                        // dump($dtoFacture->getFacture());
                        // dump("FIN Facture");

                        // dump("Produits");
                        // dump($dtoProduits->getProduits());
                        // dump("FIN Produits");
                    }
                    dump("dtoAutre->getAutre()");
                    dump($dtoAutre->getAutre());
                }

                exit;
                //$entMan->persist($invoice);
                //$entMan->flush();

                $this->addFlash('success', 'Le fichier a été téléchargé avec succès');
                return $this->redirectToRoute('app_upload');
            }
        }

        return $this->render('invoice_upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function moveUpload(): bool
    {

        return true;
    }
}
