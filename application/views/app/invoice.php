<?php


// PayPal API credentials and configuration (Production)
$apiBaseUrl = 'https://api-m.paypal.com'; //Production
$businessEmail = 'support@atlascamp.org';


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
            //'invoice_number' => $invoiceData['invoice_number'],
            'reference' => $invoiceData['reference'],
            'invoice_date' => date('Y-m-d'),
            'currency_code' => 'USD',
            'note' => 'The Minimum amount due of $1000 is due immediately upon the receipt of this invoice. Full invoice balance is due by August 1st. This is a message in the idea',
            'payment_term' => [
                'term_type' => 'DUE_ON_DATE_SPECIFIED',
                'due_date' => $invoiceData['due_date']
            ]
        ],
        'invoicer' => [
            'name' => [
                'given_name' => 'Discotique Pancake Boutique'
            ],
            'email_address' => $businessEmail,
            'website' => 'https://discotique.org',
            'logo_url' => 'https://s3foundation.s3-us-west-2.amazonaws.com/7e9d37da38c8d1d3c8adb2b5ff722945.jpg'
        ],
        'primary_recipients' => [
            [
                'billing_info' => [
                    'additional_info_value' => $invoiceData['recipient_info'],
                    'email_address' => $invoiceData['recipient_email'],
                    'name' => [
                        'given_name' => $invoiceData['recipient_name'] ?? '',
                        'surname' => $invoiceData['recipient_name'] ?? ''
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

        'payments' => [
            [
                'type' => 'OTHER',
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => '0.00'
                ],
                'payment_date' => date('Y-m-d\TH:i:s\Z'),  // Current UTC time
                'payment_method' => 'FREE',  // Indicates no payment needed
                'note' => 'Complimentary service - no charge'
            ]
        ],

        'configuration' => [
            'allow_tip' => false,
            /*
            'partial_payment' => [
                'allow_partial_payment' => true,
                'minimum_amount_due' => [
                    'currency_code' => 'USD',
                    'value' => '13.00'
                ]
            ],*/
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
        return one_two_explode('/invoices/','',$data['href']);
    }

    throw new Exception("Failed to create invoice. HTTP Code: $httpCode, Error: $error, Response: $response");
}

// Function to send PayPal invoice
function sendPaypalInvoice($accessToken, $apiBaseUrl, $invoiceId)
{
    $curl = curl_init();

    $payload = [
        'send_to_recipient' => true,
        'send_to_invoicer' => false,
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
        'recipient_info' => 'https://discotique.org/@Alivava',
        //'due_date' => date('Y-m-d', strtotime('August 1st 2025')),
        'due_date' => date('Y-m-d'),
        'items' => [
            [
                'name' => 'Premium Service Package',
                'description' => 'Monthly service subscription',
                'quantity' => 1,
                'unit_amount' => [
                    'currency_code' => 'USD',
                    'value' => '0'
                ],
                'unit_of_measure' => 'QUANTITY'
            ]
        ],
        'total_amount' => '0'
    ];

    // Step 1: Get access token
    $accessToken = getAccessToken($this->config->item('paypal_client_id'), $this->config->item('paypal_secret'), $apiBaseUrl);

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




