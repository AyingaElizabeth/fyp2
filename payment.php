// payment.php
<?php
// MTN MoMo API credentials
$apiUser = "your_api_user"; // Replace with your API user
$apiKey = "3ad44dc9450242409cce79944b84a735"; // Replace with your API key
$subscriptionKey = "7a3a025c526a4b5a8465d53305c8c242"; // Replace with your subscription key
$baseUrl = "https://sandbox.momodeveloper.mtn.com"; // Use the sandbox URL for testing

// Function to make a payment request
function makePaymentRequest($amount, $currency, $externalId, $payeeNumber, $payerMessage) {
    global $apiUser, $apiKey, $subscriptionKey, $baseUrl;

    $url = $baseUrl . "/collection/v1_0/requesttopay";
    
    $headers = [
        "Authorization: Basic " . base64_encode("$apiUser:$apiKey"),
        "X-Target-Environment: sandbox",
        "Ocp-Apim-Subscription-Key: $subscriptionKey",
        "Content-Type: application/json",
    ];


            

    $payload = json_encode([
        "amount" => $amount,
        "currency" => $currency,
        "externalId" => $externalId,
        "payer" => [
            "partyIdType" => "MSISDN",
            "partyId" => $payeeNumber
        ],
        "payerMessage" => $payerMessage,
        "payeeNote" => "Payment for services"
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Example usage
$response = makePaymentRequest("1000", "UGX", "123456789", "256780123456", "Payment for goods");
echo $response;
?>
