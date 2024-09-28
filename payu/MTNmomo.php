<?php
// File: MTNMoMoPayment.php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MTNMoMoPayment {
    private $baseUrl = 'https://sandbox.momodeveloper.mtn.com'; // Use the production URL in live environment
    private $subscriptionKey;
    private $apiUser;
    private $apiKey;
    private $tokenUrl = '/collection/token/';
    private $requestToPayUrl = '/collection/v1_0/requesttopay';

    public function __construct($subscriptionKey, $apiUser, $apiKey) {
        $this->subscriptionKey = $subscriptionKey;
        $this->apiUser = $apiUser;
        $this->apiKey = $apiKey;
    }

    private function getAccessToken() {
        $client = new Client(['base_uri' => $this->baseUrl]);

        try {
            $response = $client->request('POST', $this->tokenUrl, [
                'headers' => [
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                    'Authorization' => 'Basic ' . base64_encode($this->apiUser . ':' . $this->apiKey)
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['access_token'];
        } catch (RequestException $e) {
            // Handle error
            echo "Error getting access token: " . $e->getMessage();
            return null;
        }
    }

    public function requestToPay($amount, $currency, $externalId, $partyId, $payerMessage, $payeeNote) {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return false;
        }

        $client = new Client(['base_uri' => $this->baseUrl]);
        $referenceId = $this->generateUUID();

        try {
            $response = $client->request('POST', $this->requestToPayUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'X-Reference-Id' => $referenceId,
                    'X-Target-Environment' => 'sandbox', // Use 'production' in live environment
                    'Content-Type' => 'application/json',
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey
                ],
                'json' => [
                    'amount' => $amount,
                    'currency' => $currency,
                    'externalId' => $externalId,
                    'payer' => [
                        'partyIdType' => 'MSISDN',
                        'partyId' => $partyId
                    ],
                    'payerMessage' => $payerMessage,
                    'payeeNote' => $payeeNote
                ]
            ]);

            // Payment request successful
            return $referenceId;
        } catch (RequestException $e) {
            // Handle error
            echo "Error requesting payment: " . $e->getMessage();
            return false;
        }
    }

    public function getPaymentStatus($referenceId) {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return false;
        }

        $client = new Client(['base_uri' => $this->baseUrl]);

        try {
            $response = $client->request('GET', $this->requestToPayUrl . '/' . $referenceId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'X-Target-Environment' => 'sandbox', // Use 'production' in live environment
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['status'];
        } catch (RequestException $e) {
            // Handle error
            echo "Error getting payment status: " . $e->getMessage();
            return false;
        }
    }

    private function generateUUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}

// Usage:
$subscriptionKey = 'your_subscription_key';
$apiUser = 'your_api_user';
$apiKey = 'your_api_key';

$payment = new MTNMoMoPayment($subscriptionKey, $apiUser, $apiKey);

$amount = '100';
$currency = 'EUR';
$externalId = 'unique_transaction_id';
$partyId = '46733123453'; // Customer's phone number
$payerMessage = 'Payment for order #123';
$payeeNote = 'Payment received for order #123';

$referenceId = $payment->requestToPay($amount, $currency, $externalId, $partyId, $payerMessage, $payeeNote);

if ($referenceId) {
    echo "Payment request sent. Reference ID: " . $referenceId . "\n";

    // Wait for a while before checking status
    sleep(30);

    $status = $payment->getPaymentStatus($referenceId);
    echo "Payment status: " . $status . "\n";
} else {
    echo "Failed to send payment request.\n";
}