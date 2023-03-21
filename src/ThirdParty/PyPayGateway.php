<?php
declare(strict_types=1);

namespace App\ThirdParty;

use App\Entity\Invoice;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PyPayGateway
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {}

    public function requestPayment(Invoice $invoice): void
    {
        $this->httpClient->request('POST', 'http://localhost:5000/payment', [
            'json' => [
                'invoice_id' => $invoice->getId(),
            ],
        ]);
    }
}