<?php

namespace App\Controller;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

class InvoiceController extends AbstractController
{
    #[Route('/checkout')]
    public function checkout(
        WorkflowInterface $invoicePaymentStateMachine,
        EntityManagerInterface $entityManager,
    ): JsonResponse
    {
        $invoice = new Invoice();
        $invoicePaymentStateMachine->getMarking($invoice);

        $entityManager->persist($invoice);
        $entityManager->flush();

        return $this->json([
            'invoice_id' => $invoice->getId(),
            'payment_state' =>$invoice->getPaymentState(),
        ]);
    }

    #[Route('/pay/{id}')]
    public function pay(Invoice $invoice): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/InvoiceController.php',
        ]);
    }

    #[Route('/abort/{id}')]
    public function abort(Invoice $invoice): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/InvoiceController.php',
        ]);
    }
}