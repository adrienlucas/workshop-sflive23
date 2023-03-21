<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Message\AbortPaymentMessage;
use App\Message\SubmitPaymentRequestMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
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
    public function pay(Invoice $invoice, MessageBusInterface $messageBus): JsonResponse
    {
        $messageBus->dispatch(new SubmitPaymentRequestMessage($invoice));

        return $this->json([
            'invoice_id' => $invoice->getId(),
            'payment_state' =>$invoice->getPaymentState(),
        ]);
    }

    #[Route('/abort/{id}')]
    public function abort(Invoice $invoice, MessageBusInterface $messageBus): JsonResponse
    {
        $messageBus->dispatch(new AbortPaymentMessage($invoice));
        return $this->json([
            'invoice_id' => $invoice->getId(),
            'payment_state' => $invoice->getPaymentState(),
        ]);
    }
}
