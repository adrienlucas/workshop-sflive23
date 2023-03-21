<?php
declare(strict_types=1);

namespace App\Message;

use App\ThirdParty\PyPayGateway;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Workflow\WorkflowInterface;

#[AsMessageHandler]
class SubmitPaymentRequestHandler
{
    public function __construct(
        private readonly WorkflowInterface $invoicePaymentStateMachine,
        private readonly PyPayGateway $pypayGateway,
    )
    {
    }

    public function __invoke(SubmitPaymentRequestMessage $message): void
    {
        $invoice = $message->invoice;
        if(!$this->invoicePaymentStateMachine->can($invoice, 'submit_request')) {
            throw new InvalidPaymentStateException($invoice, 'submit_request');
        }

        $this->pypayGateway->requestPayment($invoice);

        $this->invoicePaymentStateMachine->apply($invoice, 'submit_request');
    }
}