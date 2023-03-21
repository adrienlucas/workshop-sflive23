<?php

namespace App\Tests;

use App\Entity\Invoice;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Workflow\WorkflowInterface;

class InvoicePaymentWorkflowTest extends KernelTestCase
{
    public function testPaymentWorkflowTransitions(): void
    {
        self::bootKernel();
        // debug:container payment
        // > WorkflowInterface
        $paymentWorkflow = static::getContainer()->get('state_machine.invoice_payment');
        $this->assertInstanceOf(WorkflowInterface::class, $paymentWorkflow);

        $invoice = new Invoice();
        $this->assertNull($invoice->getPaymentState());

        $paymentWorkflow->getMarking($invoice);
        $this->assertSame('required', $invoice->getPaymentState());

        $this->assertFalse($paymentWorkflow->can($invoice, 'pay'));
        $this->assertFalse($paymentWorkflow->can($invoice, 'fail'));

        $this->assertTrue($paymentWorkflow->can($invoice, 'submit_request'));
        $this->assertTrue($paymentWorkflow->can($invoice, 'abort'));

        $paymentWorkflow->apply($invoice, 'submit_request');
        $this->assertSame('pending', $invoice->getPaymentState());

        $this->assertFalse($paymentWorkflow->can($invoice, 'submit_request'));
        $this->assertFalse($paymentWorkflow->can($invoice, 'abort'));

        $this->assertTrue($paymentWorkflow->can($invoice, 'pay'));
        $this->assertTrue($paymentWorkflow->can($invoice, 'fail'));

        $paymentWorkflow->apply($invoice, 'pay');
        $this->assertSame('paid', $invoice->getPaymentState());
        $this->assertFalse($paymentWorkflow->can($invoice, 'submit_request'));
        $this->assertFalse($paymentWorkflow->can($invoice, 'abort'));
        $this->assertFalse($paymentWorkflow->can($invoice, 'pay'));
        $this->assertFalse($paymentWorkflow->can($invoice, 'fail'));
    }
}
