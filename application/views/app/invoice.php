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

foreach ($_POST['invoice_items'] as $key => $value) {
    unset($_POST['invoice_items'][$key]['i__id']);
}


//Fetch User Data:
$fetch_emails = $this->Mench_ledger->fetch(array(
    'x__following' => 3288, //Email
    'x__follower' => $player_e['e__id'],
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
));
$fetch_first_names = $this->Mench_ledger->fetch(array(
    'x__following' => 42584, //First Name
    'x__follower' => $player_e['e__id'],
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
));
$fetch_last_names = $this->Mench_ledger->fetch(array(
    'x__following' => 30198, //Last Name
    'x__follower' => $player_e['e__id'],
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
));


foreach($this->Idea_cache->fetch(array(
    'i__id' => $_POST['target_i__id'], //ACTIVE
)) as $i){

    // Usage example
    try {
        // Sample invoice data
        $invoiceData = [
            'businessEmail' => website_setting(30882),
            'invoicer_logo_url' => 'https://s3foundation.s3-us-west-2.amazonaws.com/7e9d37da38c8d1d3c8adb2b5ff722945.jpg',
            'invoicer_given_name' => 'Discotique Pancake Boutique 2025',
            'invoicer_website' => 'https://discotique.org/Discotique2025',

            'currency_code' => $_POST['currency_code'],
            'min_payment' => ( $_POST['total_price'] > 1000 ? "1000" : "0" ),
            //'note' => $i['i__message'],
            'note' => 'Wow nice',

            'recipient_email' => ( count($fetch_emails) && filter_var($fetch_emails[0]['x__message'], FILTER_VALIDATE_EMAIL) ? $fetch_emails[0]['x__message'] : 'shervin+missingemail@mench.com' ),
            'recipient_name' => count($fetch_first_names) && strlen($fetch_first_names[0]['x__message']) ? $fetch_first_names[0]['x__message'] : $player_e['e__title'],
            'recipient_surname' => count($fetch_last_names) ? $fetch_last_names[0]['x__message'] : '',
            'due_date' => date('Y-m-d'), //date('Y-m-d', strtotime('August 1st 2025'))
            'total_amount' => $_POST['total_price'],
            'items' => object_to_array($_POST['invoice_items']),
        ];

        // Step 1: Get access token
        $accessToken = getAccessToken($this->config->item('paypal_client_id'), $this->config->item('paypal_secret'));

        // Step 2: Create invoice
        $invoiceId = createPaypalInvoice($accessToken, $invoiceData);

        // Step 3: Send invoice
        sendPaypalInvoice($accessToken, $invoiceId);

    } catch (Exception $e) {
        return view__json(array(
            'status' => 0,
            'message' => $invoiceData,
        ));
    }

    return view__json(array(
        'status' => 1,
        'next__url' => $this->Mench_ledger->find_next($player_e['e__id'], $i['i__hashtag'], $i, 0, false),
        'message' => 'Success: Check you email to find your Invoice within 1-2 minutes',
    ));

}




