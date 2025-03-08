<?php

namespace App\Controller;

use App\Service\InvoiceUploadChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\InvoiceType;
use App\Entity\Invoice;

class InvoiceUploadController extends AbstractController
{

    public function __construct(private readonly InvoiceUploadChecker $invoiceUploadChecker) {}

    #[Route('/invoice/upload', name: 'app_invoice_upload')]
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $entMan): Response
    {
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            if ($file) {
                $IUCheck = $this->invoiceUploadChecker->checker($file);
                dump("IUCheck");
                dump($IUCheck);
                if ($IUCheck) {
                    $this->addFlash('success', 'Le fichier a été téléchargé avec succès');
                    dump("Bump");
                } else {
                    $this->addFlash('error', 'Erreur téléchargement du fichier');
                }
                exit;
                //$entMan->persist($invoice);
                //$entMan->flush();
                return $this->redirectToRoute('app_invoice_upload');
            }
        }

        return $this->render('invoice_upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
