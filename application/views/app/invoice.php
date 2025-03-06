<?php

// PayPal API URLs
$authUrl = "https://api.paypal.com/v1/oauth2/token";
$invoiceUrl = "https://api.paypal.com/v2/invoicing/invoices";

// Function to get an access token
function getAccessToken($clientId, $clientSecret, $authUrl)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $authUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Accept-Language: en_US",
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    return $result['access_token'] ?? null;
}

// Function to create and send an invoice
function createInvoice($accessToken, $invoiceUrl)
{
    $invoiceData = [
        "detail" => [
            "currency_code" => "USD",
            "note" => "Thank you for your business!",
            "term" => "Due upon receipt",
            "invoice_date" => date("Y-m-d"),
        ],
        "invoicer" => [
            "name" => [
                "given_name" => "Your Name",
                "surname" => "Your Last Name",
            ],
            "email_address" => "support@atlascamp.org",
        ],
        "primary_recipients" => [
            [
                "billing_info" => [
                    "name" => [
                        "given_name" => "Ali",
                        "surname" => "Baba",
                    ],
                    "email_address" => "shervinenayati@mench.com",
                ],
            ],
        ],
        "items" => [
            [
                "name" => "Service/Product Name",
                "description" => "Detailed description of service or product",
                "quantity" => "1",
                "unit_amount" => [
                    "currency_code" => "USD",
                    "value" => "100.00",
                ],
            ],
        ],
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $invoiceUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($invoiceData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $accessToken,
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Get the PayPal access token
$accessToken = getAccessToken($this->config->item('paypal_client_id'), $this->config->item('paypal_secret'), $authUrl);

if ($accessToken) {
    // Create the invoice
    $invoiceResponse = createInvoice($accessToken, $invoiceUrl);

    if (isset($invoiceResponse['id'])) {
        echo "Invoice Created Successfully: " . $invoiceResponse['href'];
    } else {
        echo "Error creating invoice: ";
        print_r($invoiceResponse);
    }
} else {
    echo "Error retrieving PayPal access token.";
}

