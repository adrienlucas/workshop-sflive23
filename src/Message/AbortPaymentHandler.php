<?php
declare(strict_types=1);

namespace App\Message;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Workflow\WorkflowInterface;

#[AsMessageHandler]
class AbortPaymentHandler
{
    public function __construct(
        private readonly WorkflowInterface $invoicePaymentStateMachine,
    )
    {
    }

    public function __invoke(AbortPaymentMessage $message): void
    {
        $invoice = $message->invoice;
        if(!$this->invoicePaymentStateMachine->can($invoice, 'abort')) {
            throw new InvalidPaymentStateException($invoice, 'abort');
        }

        $this->invoicePaymentStateMachine->apply($invoice, 'abort');
    }
}