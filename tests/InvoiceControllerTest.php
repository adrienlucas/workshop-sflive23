<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InvoiceControllerTest extends WebTestCase
{
    public function testInvoiceCheckout(): void
    {
        $client = static::createClient();
        $client->request('GET', '/checkout');

        $this->assertResponseIsSuccessful();
        $jsonResponse = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('invoice_id', $jsonResponse);
        $this->assertSame(1, $jsonResponse['invoice_id']);

        $this->assertArrayHasKey('payment_state', $jsonResponse);
        $this->assertSame('required', $jsonResponse['payment_state']);
    }
}
