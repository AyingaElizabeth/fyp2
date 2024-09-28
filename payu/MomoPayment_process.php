<?php
// File: process_payment.php

require_once 'MTNMoMoPayment.php';

// Load configuration
$config = require 'config.php';

// Initialize the payment object
$payment = new MTNMoMoPayment(
    $config['mtn_subscription_key'],
    $config['mtn_api_user'],
    $config['mtn_api_key']
);

// Get payment details (in a real application, these would come from your order processing system)
$amount = '100';
$currency = 'EUR';
$externalId = 'order_' . time(); // Generate a unique ID for this transaction
$partyId = '46733123453'; // Customer's phone number
$payerMessage = 'Payment for order #123';
$payeeNote = 'Payment received for order #123';

// Request payment
$referenceId = $payment->requestToPay($amount, $currency, $externalId, $partyId, $payerMessage, $payeeNote);

if ($referenceId) {
    echo "Payment request sent. Reference ID: " . $referenceId . "\n";

    // In a real application, you would store the referenceId and handle the response asynchronously
    // For this example, we'll wait a bit and then check the status
    sleep(30);

    $status = $payment->getPaymentStatus($referenceId);
    echo "Payment status: " . $status . "\n";

    // Handle the payment status
    switch ($status) {
        case 'SUCCESSFUL':
            // Payment successful, update your database, send confirmation to customer, etc.
            break;
        case 'PENDING':
            // Payment is still processing
            break;
        case 'FAILED':
            // Payment failed, handle accordingly
            break;
        default:
            // Unexpected status
            break;
    }
} else {
    echo "Failed to send payment request.\n";
    // Handle the error, maybe try again or notify the user
}