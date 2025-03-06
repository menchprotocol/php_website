<?php

$player_e = superpower_unlocked(null, 0, $this->player_e);
if(!$player_e){
    return view__json(array(
        'status' => 0,
        'message' => view__unauthorized_message(),
    ));
} elseif (!isset($_POST['target_i__hashtag']) || !isset($_POST['target_i__id']) || !isset($_POST['invoice_items']) || !isset($_POST['do_skip'])) {
    return view__json(array(
        'status' => 0,
        'message' => 'Missing Core Data',
    ));
}


foreach($this->Idea_cache->fetch(array(
    'i__id' => $_POST['target_i__id'], //ACTIVE
)) as $i){
    // Usage example
    try {
        // Sample invoice data
        $invoiceData = [
            'businessEmail' => 'support@atlascamp.org',
            'invoicer_logo_url' => 'https://s3foundation.s3-us-west-2.amazonaws.com/7e9d37da38c8d1d3c8adb2b5ff722945.jpg',
            'invoicer_given_name' => 'Discotique Pancake Boutique 2025',
            'invoicer_website' => 'https://discotique.org/Discotique2025',

            'currency_code' => 'USD',
            'min_payment' => 66,
            'note' => $i['i__message'],

            'recipient_email' => 'shervinenayati+test@mench.com',
            'recipient_name' => 'Test',
            'recipient_surname' => 'Wow'.rand(1000, 9999),
            'due_date' => date('Y-m-d', strtotime('August 1st 2025')),
            'total_amount' => $_POST['total_price'],
            'items' => $_POST['invoice_items'],
        ];

        // Step 1: Get access token
        $accessToken = getAccessToken($this->config->item('paypal_client_id'), $this->config->item('paypal_secret'));

        // Step 2: Create invoice
        $invoiceId = createPaypalInvoice($accessToken, $invoiceData);

        // Step 3: Send invoice
        sendPaypalInvoice($accessToken, $invoiceId);

    } catch (Exception $e) {
        //Some error
    }
}




