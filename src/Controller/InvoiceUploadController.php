<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\InvoiceType;
use App\Entity\Invoice;

class InvoiceUploadController extends AbstractController
{
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
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('invoices_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Le fichier a été téléchargé avec succès');
                }

                $invoice->setFilename($newFilename);
                $invoice->setOriginalFilename($originalFilename);
                $invoice->setUploadedAt(new \DateTimeImmutable());

                $entMan->persist($invoice);
                $entMan->flush();

                $this->addFlash('success', 'Le fichier a été téléchargé avec succès');
                return $this->redirectToRoute('app_upload');
            }
        }

        return $this->render('invoice_upload/index.html.twig', [
            'form' => $form->createView(),
        ]);

        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
