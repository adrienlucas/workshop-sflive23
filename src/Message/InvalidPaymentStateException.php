<?php
declare(strict_types=1);

namespace App\Message;

use App\Entity\Invoice;

class InvalidPaymentStateException extends \Exception
{
    public function __construct(Invoice $invoice, string $transition)
    {
        parent::__construct(sprintf(
            'Invalid payment state for invoice %s: %s',
            $invoice->getId(),
            $transition
        ));
    }
}