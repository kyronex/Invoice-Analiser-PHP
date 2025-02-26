<?php

namespace App\Controller;

use App\Service\ApiMistral;
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

    public function __construct(ApiMistral $apiMistral)
    {
        $this->apiMistral = $apiMistral;
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

                dump($invoice);
                
                dump($this->apiMistral->getApiConf());
                $prompt = $this->apiMistral->getApiConf()->userPrompt;

                $response = $this->apiMistral->getChatCompletionDoc($this->getParameter('invoices_directory') . $newFilename , $prompt);                
                dump($response);

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
}
