<?php

// Initialize cURL session
$ch = curl_init('https://api.paypal.com/v2/invoicing/invoices');

// Set cURL options
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $this->config->item('paypal_access_token'),
    "Accept: application/json"
]);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "detail" => [
        "reference"      => "Title",         // Optional reference
        "note"           => "Messages",
        "invoice_date"   => date("Y-m-d"),      // Invoice date in YYYY-MM-DD format
        "currency_code"  => "USD",              // Currency code
        "payment_term"   => [
            "term_type" => "NET_0",
            "due_date"    => date("Y-m-d")
        ],
    ],
    "configuration" => [
        "allow_tip"                      => false,         // Optional reference
        "tax_calculated_after_discount"  => true,
        "tax_inclusive"                  => true,
        "invoice_date"   => date("Y-m-d"),      // Invoice date in YYYY-MM-DD format
        "currency_code"  => "USD",              // Currency code
        "partial_payment"   => [
            "allow_partial_payment" => true,
            "minimum_amount_due"   => [
                "currency_code" => "USD",
                "value"         => "1000.00"
            ],
        ],
    ],
    "invoicer" => [
        "name"          => [
            "given_name" => "Atlas",
            "surname"    => "Camp"
        ],
        "email_address" => "support@atlascamp.org",
    ],
    "primary_recipients" => [
        [
            "billing_info" => [
                "name"          => [
                    "given_name" => "Jane",
                    "surname"    => "Doe"
                ],
                "email_address" => "recipient@example.com"
            ]
        ]
    ],
    "items" => [
        [
            "name"        => "Product Name",
            "quantity"    => "1",
            "unit_amount" => [
                "currency_code" => "USD",
                "value"         => "100.00"
            ]
        ]
    ]
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request
$response = curl_exec($ch);

// Get HTTP status code for error handling
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    if ($httpCode == 201) { // HTTP 201 Created
        echo "Invoice created successfully:\n" . $response;
    } else {
        echo "Failed to create invoice. HTTP Status Code: $httpCode\nResponse: $response";
    }
}

// Close cURL session
curl_close($ch);

?>