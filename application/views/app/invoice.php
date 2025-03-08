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


$items = [];
foreach ($_POST['invoice_items'] as $key => $value) {

    foreach($this->Idea_cache->fetch(array(
        'i__id' => $_POST['invoice_items'][$key]['i__id'], //ACTIVE
    )) as $this_i){

        if($_POST['invoice_items'][$key]['quantity']<1){
            continue;
        }

        //Generate Paypal API Item Array:
        array_push($items, [
            'name' => $_POST['invoice_items'][$key]['name'],
            'description' => $_POST['invoice_items'][$key]['description'],
            'quantity' => $_POST['invoice_items'][$key]['quantity'],
            'unit_amount' => [
                'currency_code' => $_POST['invoice_items'][$key]['currency_code'],
                'value' => $_POST['invoice_items'][$key]['currency_value']
            ],
            'unit_of_measure' => 'QUANTITY' //Required by Paypal API
        ]);
    }

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

$set_email = false;
if(count($fetch_emails) && filter_var($fetch_emails[0]['x__message'], FILTER_VALIDATE_EMAIL)) {
    $set_email = $fetch_emails[0]['x__message'];
}
if(!$set_email){
    //No Valid email:
    $this->Mench_ledger->create(array(
        'x__type' => 4246, //Platform Bug Reports
        'x__player' => $player_e['e__id'],
        'x__next' => $_POST['focus__id'],
        'x__message' => 'No Valid email found for invoice',
    ));
    $set_email = 'support+email+error@atlascamp.org';
}


foreach($this->Idea_cache->fetch(array(
    'i__id' => $_POST['target_i__id'], //ACTIVE
)) as $i_target){

    foreach($this->Idea_cache->fetch(array(
        'i__id' => $_POST['focus__id'], //ACTIVE
    )) as $i){

        // Usage example
        try {
            // Sample invoice data
            $invoiceData = [
                'businessEmail' => website_setting(30882),
                'invoicer_logo_url' => 'https://s3foundation.s3-us-west-2.amazonaws.com/7e9d37da38c8d1d3c8adb2b5ff722945.jpg',
                'invoicer_given_name' => view__i_title($i_target, true),
                'invoicer_website' => 'https://'.get_domain('m__message', $player_e['e__id']),
                'invoicer_address' => 'Nadine, Lead Communicato +1-236-866-9373',

                'note' => $i['i__message']."\n\n".$i_target['i__message'],
                'currency_code' => $_POST['currency_code'],
                'min_payment' => ( $_POST['total_price'] > 1000 ? "1000" : "0" ),
                //'due_date' => date('Y-m-d'),
                'due_date' => date('Y-m-d', strtotime('August 1st 2025')),
                'total_amount' => $_POST['total_price'],
                'items' => $items,

                'recipient_email' => $set_email,
                'recipient_name' => count($fetch_first_names) && strlen($fetch_first_names[0]['x__message']) ? $fetch_first_names[0]['x__message'] : $player_e['e__title'],
                'recipient_surname' => count($fetch_last_names) ? $fetch_last_names[0]['x__message'] : '',
                'recipient_address' => 'https://'.get_domain('m__message', $player_e['e__id']).'/@'.$player_e['e__handle'],
            ];

            // Step 1: Get access token
            $accessToken = getAccessToken($this->config->item('paypal_client_id'), $this->config->item('paypal_secret'));

            // Step 2: Create invoice
            $invoiceId = createPaypalInvoice($accessToken, $invoiceData);

            // Step 3: Send invoice
            sendPaypalInvoice($accessToken, $invoiceId);

        } catch (Exception $e) {
            //It catches an exception even though invoice is created
        }




        //Delete Old Parent Invoice:
        foreach($this->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__previous' => $i['i__id'],
            'x__player' => $player_e['e__id'],
        ), array(), 0) as $x_discovery){
            $this->Mench_ledger->update($x_discovery['x__id'], array(
                'x__privacy' => 6173, //Transaction Deleted
            ), $player_e['e__id'], 12129 /* DISCOVERY ANSWER DELETED */);
        }

        //Delete Old Child Answers:
        foreach($this->Mench_ledger->fetch(array(
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 7712, //Input Choice
            'x__player' => $player_e['e__id'],
            'x__previous' => $i['i__id'],
        ), array('x__next')) as $x_selection){

            //Remove Selection:
            $this->Mench_ledger->update($x_selection['x__id'], array(
                'x__privacy' => 6173, //Transaction Deleted
            ), $player_e['e__id'], 12129 /* DISCOVERY ANSWER DELETED */);

            //Remove discovery if we can:
            if(!in_array($x_selection['i__type'], $this->config->item('n___42905'))){
                foreach($this->Mench_ledger->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'x__previous' => $x_selection['i__id'],
                    'x__player' => $player_e['e__id'],
                ), array(), 0) as $x_discovery){
                    $this->Mench_ledger->update($x_discovery['x__id'], array(
                        'x__privacy' => 6173, //Transaction Deleted
                    ), $player_e['e__id'], 12129 /* DISCOVERY ANSWER DELETED */);
                }
            }
        }


        //Save New Invoice:
        $this->Mench_ledger->mark_complete(44245, $player_e['e__id'], $i_target['i__id'], $i);


        //Save New Child Answers:
        foreach ($_POST['invoice_items'] as $key => $value) {
            foreach($this->Idea_cache->fetch(array(
                'i__id' => $_POST['invoice_items'][$key]['i__id'], //ACTIVE
            )) as $this_i){

                //Complete this item:
                $this->Mench_ledger->mark_complete(i__discovery_link($this_i), $player_e['e__id'], $i_target['i__id'], $this_i, array(), array(
                    'x__weight' => $_POST['invoice_items'][$key]['quantity'],
                ));

                //Save Answer:
                $this->Mench_ledger->create(array(
                    'x__type' => 7712, //Input Choice
                    'x__player' => $player_e['e__id'],
                    'x__previous' => $_POST['focus__id'],
                    'x__weight' => $_POST['invoice_items'][$key]['quantity'],
                    'x__next' => $_POST['invoice_items'][$key]['i__id'],
                ));
            }
        }


        //Return Data:
        return view__json(array(
            'status' => 1,
            'next__url' => $this->Mench_ledger->find_next($player_e['e__id'], $_POST['target_i__hashtag'], $i_target, 0, false),
            'message' => 'Success: Paypal Will Send Your Invoice to '.$set_email.' within 1-2 minutes',
            'invoiceData' => $invoiceData,
        ));


    }
}




