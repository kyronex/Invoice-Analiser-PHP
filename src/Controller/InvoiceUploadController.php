<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceUploadController extends AbstractController
{
    #[Route('/invoice/upload', name: 'app_invoice_upload')]
    public function index(): Response
    {
        return $this->render('invoice_upload/index.html.twig', [
            'controller_name' => 'InvoiceUploadController',
        ]);
    }
}
