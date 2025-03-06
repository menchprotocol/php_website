<?php


// PayPal API credentials and configuration (Production)
$clientId = $this->config->item('paypal_client_id');         // Replace with your PayPal Client ID
$clientSecret = $this->config->item('paypal_secret'); // Replace with your PayPal Secret
$apiBaseUrl = 'https://api-m.paypal.com'; // Production endpoint
$businessEmail = 'support@atlascamp.org'; // Your PayPal business email

// Function to get PayPal access token
function getAccessToken($clientId, $clientSecret, $apiBaseUrl)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "$apiBaseUrl/v1/oauth2/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_USERPWD => "$clientId:$clientSecret",
        CURLOPT_POSTFIELDS => "grant_type=client_credentials",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Accept-Language: en_US"
        ],
        CURLOPT_SSL_VERIFYPEER => true,  // Verify SSL in production
        CURLOPT_SSL_VERIFYHOST => 2      // Verify host in production
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    if ($httpCode == 200 && !$error) {
        $data = json_decode($response, true);
        return $data['access_token'];
    }

    throw new Exception("Failed to get access token. HTTP Code: $httpCode, Error: $error");
}

// Function to create PayPal invoice
function createPaypalInvoice($accessToken, $apiBaseUrl, $invoiceData, $businessEmail)
{
    $curl = curl_init();

    // Invoice payload
    $payload = [
        'detail' => [
            'invoice_number' => $invoiceData['invoice_number'],
            'reference' => $invoiceData['reference'],
            'invoice_date' => date('Y-m-d'),
            'currency_code' => 'USD',
            'payment_terms' => [
                'due_date' => $invoiceData['due_date'] ?? date('Y-m-d', strtotime('+30 days'))
            ]
        ],
        'invoicer' => [
            'name' => [
                'given_name' => 'Discotique Pancake Boutique'
            ],
            'email_address' => $businessEmail,
            'website' => 'https://discotique.org/Discotique2025'
        ],
        'primary_recipients' => [
            [
                'billing_info' => [
                    'email_address' => $invoiceData['recipient_email'],
                    'name' => [
                        'given_name' => $invoiceData['recipient_name'] ?? ''
                    ]
                ]
            ]
        ],
        'items' => $invoiceData['items'],
        'amount' => [
            'currency_code' => 'USD',
            'value' => $invoiceData['total_amount'],
            'breakdown' => [
                'item_total' => [
                    'currency_code' => 'USD',
                    'value' => $invoiceData['total_amount']
                ]
            ]
        ],

        // This triggers immediate sending instead of draft creation
        'send_to_recipient' => true,
        'send_to_invoicer' => true  // Set to true if you want a copy
    ];

    curl_setopt_array($curl, [
        CURLOPT_URL => "$apiBaseUrl/v2/invoicing/invoices",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    if ($httpCode == 201 && !$error) {
        $data = json_decode($response, true);
        print_r($payload);

        echo '==================';
        print_r($data);
        return $data['id'];
    }

    throw new Exception("Failed to create invoice. HTTP Code: $httpCode, Error: $error, Response: $response");
}

// Function to send PayPal invoice
function sendPaypalInvoice($accessToken, $apiBaseUrl, $invoiceId)
{
    $curl = curl_init();

    $payload = [
        'send_to_recipient' => true,
        'send_to_invoicer' => true,
        'scheduled_send_time' => date('Y-m-d\TH:i:s\Z', (now()+3600)),
        'subject' => 'Invoice from Your Company Name',  // Customize subject
        'note' => 'Thank you for your business!'       // Customize note
    ];

    curl_setopt_array($curl, [
        CURLOPT_URL => "$apiBaseUrl/v2/invoicing/invoices/$invoiceId/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    if ($httpCode == 202 && !$error) {
        return true;
    }

    throw new Exception("Failed to send invoice. HTTP Code: $httpCode, Error: $error, Response: $response");
}

// Usage example
try {
    // Sample invoice data
    $invoiceData = [
        'invoice_number' => 'INV-' . time(),
        'reference' => 'ORDER-' . rand(1000, 9999),
        'recipient_email' => 'shervinenayati@mench.com',
        'recipient_name' => 'Ali Baba'.rand(1000, 9999),
        'due_date' => date('Y-m-d', strtotime('+15 days')),
        'items' => [
            [
                'name' => 'Premium Service Package',
                'description' => 'Monthly service subscription',
                'quantity' => 1,
                'unit_amount' => [
                    'currency_code' => 'USD',
                    'value' => '199.99'
                ],
                'unit_of_measure' => 'QUANTITY'
            ]
        ],
        'total_amount' => '199.99'
    ];

    // Step 1: Get access token
    $accessToken = getAccessToken($clientId, $clientSecret, $apiBaseUrl);

    // Step 2: Create invoice
    $invoiceId = createPaypalInvoice($accessToken, $apiBaseUrl, $invoiceData, $businessEmail);

    // Step 3: Send invoice
    $sent = sendPaypalInvoice($accessToken, $apiBaseUrl, $invoiceId);

    if ($sent) {
        echo "Invoice created and sent successfully! Invoice ID: $invoiceId\n";
        echo "Sent to: " . $invoiceData['recipient_email'];
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}




