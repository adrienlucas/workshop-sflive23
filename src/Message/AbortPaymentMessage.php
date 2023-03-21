<?php
declare(strict_types=1);

namespace App\Message;

use App\Entity\Invoice;

class AbortPaymentMessage
{
    public function __construct(
        public readonly Invoice $invoice,
    ) {}
}