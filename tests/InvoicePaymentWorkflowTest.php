<?php

namespace App\Tests;

use App\Entity\Invoice;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Workflow\WorkflowInterface;

class InvoicePaymentWorkflowTest extends KernelTestCase
{
    public function testPaymentWorkflowTransitions(): void
    {
        $kernel = self::bootKernel();
        // debug:container payment
        // > WorkflowInterface
        $paymentWorkflow = static::getContainer()->get('state_machine.invoice_payment');
        $this->assertInstanceOf(WorkflowInterface::class, $paymentWorkflow);

        $invoice = new Invoice();
        $this->assertNull($invoice->getPaymentState());

        $paymentWorkflow->getMarking($invoice);
        $this->assertSame('required', $invoice->getPaymentState());
    }
}
