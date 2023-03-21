<?php
declare(strict_types=1);

namespace App\Message;

use App\Entity\Invoice;

class SubmitPaymentRequestMessage
{
    public function __construct(
        public readonly Invoice $invoice,
    ) {}
}