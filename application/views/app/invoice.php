<?php


// Usage example
try {
    // Sample invoice data
    $invoiceData = [
        'businessEmail' => 'support@atlascamp.org',
        'invoicer_logo_url' => 'https://s3foundation.s3-us-west-2.amazonaws.com/7e9d37da38c8d1d3c8adb2b5ff722945.jpg',
        'invoicer_given_name' => 'Discotique Pancake Boutique',
        'invoicer_website' => 'https://discotique.org',

        'currency_code' => 'USD',
        'min_payment' => 66,
        'note' => 'The Minimum amount due of $1000 is due immediately upon the receipt of this invoice. Full invoice balance is due by August 1st. This is a message in the idea',

        'recipient_email' => 'shervinenayati@mench.com',
        'recipient_name' => 'Test Wow'.rand(1000, 9999),
        'recipient_info' => 'https://discotique.org/@Alivava',
        'due_date' => date('Y-m-d', strtotime('August 1st 2025')),
        'total_amount' => '777',
        'items' => [
            [
                'name' => 'Premium Service Package',
                'description' => 'Monthly service subscription',
                'quantity' => 1,
                'unit_amount' => [
                    'currency_code' => 'USD',
                    'value' => '777'
                ],
                'unit_of_measure' => 'QUANTITY'
            ]
        ],
    ];

    // Step 1: Get access token
    $accessToken = getAccessToken($this->config->item('paypal_client_id'), $this->config->item('paypal_secret'));

    // Step 2: Create invoice
    $invoiceId = createPaypalInvoice($accessToken, $invoiceData);

    // Step 3: Send invoice
    $sent = sendPaypalInvoice($accessToken, $invoiceId);

    if ($sent) {
        echo "Invoice created and sent successfully! Invoice ID: $invoiceId\n";
        echo "Sent to: " . $invoiceData['recipient_email'];
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}




