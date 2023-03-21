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

    public function testInvoicePayment(): void
    {
        $client = static::createClient();
        $client->request('GET', '/checkout');
        $checkoutResponse = json_decode($client->getResponse()->getContent(), true);

        $client->request('GET', '/pay/'.$checkoutResponse['invoice_id']);
        $this->assertResponseIsSuccessful();

        $payResponse = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('invoice_id', $payResponse);
        $this->assertSame($checkoutResponse['invoice_id'], $payResponse['invoice_id']);

        $this->assertArrayHasKey('payment_state', $payResponse);
        $this->assertSame('pending', $payResponse['payment_state']);
    }
}
