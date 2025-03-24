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
} elseif(!(filter_var(website_setting(30882), FILTER_VALIDATE_EMAIL) && strlen(website_setting(44355))>10 && strlen(website_setting(44354))>10)) {
    return view__json(array(
        'status' => 0,
        'message' => 'Paypal Invoicing is Not Active on This Domain... Contact Webmaster...',
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
            'description' => rtrim($_POST['invoice_items'][$key]['description'],'Show more'), //TO remove the 'Show more' that sometimes gets appended
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
    'link_up' => 3288, //Email
    'link_down' => $player_e['e__id'],
    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'link_void' => 0, //Not Void
));
$fetch_phones = $this->Mench_ledger->fetch(array(
    'link_up' => 4783, //Phone
    'link_down' => $player_e['e__id'],
    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'link_void' => 0, //Not Void
));
$fetch_first_names = $this->Mench_ledger->fetch(array(
    'link_up' => 42584, //First Name
    'link_down' => $player_e['e__id'],
    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'link_void' => 0, //Not Void
));
$fetch_last_names = $this->Mench_ledger->fetch(array(
    'link_up' => 30198, //Last Name
    'link_down' => $player_e['e__id'],
    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'link_void' => 0, //Not Void
));

$set_email = false;
if(count($fetch_emails) && filter_var($fetch_emails[0]['link_text'], FILTER_VALIDATE_EMAIL)) {
    $set_email = $fetch_emails[0]['link_text'];
}
$set_phone = false;
if(count($fetch_phones) && strlen($fetch_phones[0]['link_text'])>=8) {
    $set_phone = $fetch_phones[0]['link_text'];
}

if(!$set_email){
    //No Valid email:
    $this->Mench_ledger->create(array(
        'link_type' => 4246, //Platform Bug Reports
        'link_player' => $player_e['e__id'],
        'link_right' => $_POST['focus__id'],
        'link_text' => 'No Valid email found for invoice',
    ));
    return view__json(array(
        'status' => 0,
        'message' => 'Your account does not have a valid email address for us to send your invoice. Click on Edit Profile from Top/Right menu, edit your email address, and try again.',
    ));
}



foreach($this->Idea_cache->fetch(array(
    'i__id' => $_POST['target_i__id'], //ACTIVE
)) as $i_target){

    foreach($this->Idea_cache->fetch(array(
        'i__id' => $_POST['focus__id'], //ACTIVE
    )) as $i){

        $website_logo = one_two_explode('img src="','"',get_domain('m__cover'));
        $invoice_due_dates = $this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
            'link_right' => $i['i__id'],
            'link_up' => 44378, //Invoice Due Date
        ));
        $invoice_min_payments = $this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
            'link_right' => $i['i__id'],
            'link_up' => 44379, //Invoice Min Payment
        ));
        $min_pay = ( count($invoice_min_payments) && floatval($invoice_min_payments[0]['link_text'])>0 ? floatval($invoice_min_payments[0]['link_text']) : 0 );

        // Usage example
        try {
            // Sample invoice data
            $invoiceData = [
                'invoicer_logo_url' => $website_logo,
                'invoicer_given_name' => view__i_title($i_target, true),
                'invoicer_address_line_1' => '', //Atlas Foundation; Non-Profit #774760508BC0001
                'invoicer_address_line_2' => '', //1122 W 41st Ave, Vancouver, BC, V6M 1W8, Canada
                'invoicer_website' => 'https://'.get_domain('m__message', $player_e['e__id']),
                'invoicer_email' => website_setting(30882),

                'note' => $i['i__message'],
                'currency_code' => $_POST['currency_code'],
                'min_payment' => ( $min_pay>0 && $_POST['total_price'] >= $min_pay ? $min_pay."" : "0" ),
                'due_date' => date('Y-m-d', ( $_POST['total_price']>0 && strtotime($invoice_due_dates[0]['link_text'])>time() ? strtotime($invoice_due_dates[0]['link_text']) : time() )),
                'total_amount' =>  $_POST['total_price'],
                'items' => $items,

                'recipient_name' => count($fetch_first_names) && strlen($fetch_first_names[0]['link_text']) ? $fetch_first_names[0]['link_text'] : $player_e['e__title'],
                'recipient_surname' => count($fetch_last_names) ? $fetch_last_names[0]['link_text'] : '',
                'recipient_address_line_1' => 'https://'.get_domain('m__message', $player_e['e__id']).'/@'.$player_e['e__handle'],
                'recipient_address_line_2' => ( $set_phone ? $set_phone : '' ),
                'recipient_email' => $set_email,
            ];

            // Step 1: Get access token
            $accessToken = getAccessToken(website_setting(44354), website_setting(44355));

            // Step 2: Create invoice
            $invoiceId = createPaypalInvoice($accessToken, $invoiceData);

            // Step 3: Send invoice
            sendPaypalInvoice($accessToken, $invoiceId);

        } catch (Exception $e) {
            //It catches an exception even though invoice is created
        }




        //Delete Old Parent Invoice:
        foreach($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'link_left' => $i['i__id'],
            'link_player' => $player_e['e__id'],
        ), array(), 0) as $x_discovery){
            $this->Mench_ledger->update($x_discovery['link_id'], array(), $player_e['e__id']);
        }

        //Delete Old Child Answers:
        foreach($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type' => 7712, //Input Choice
            'link_player' => $player_e['e__id'],
            'link_left' => $i['i__id'],
        ), array('link_right')) as $x_selection){

            //Remove Selection:
            $this->Mench_ledger->update($x_selection['link_id'], array(), $player_e['e__id']);

            //Remove discovery if we can:
            if(!in_array($x_selection['i__type'], $this->config->item('n___42905'))){
                foreach($this->Mench_ledger->fetch(array(
                    'link_void' => 0, //Not Void
                    'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'link_left' => $x_selection['i__id'],
                    'link_player' => $player_e['e__id'],
                ), array(), 0) as $x_discovery){
                    $this->Mench_ledger->update($x_discovery['link_id'], array(), $player_e['e__id']);
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
                    'link_number' => $_POST['invoice_items'][$key]['quantity'],
                ));

                //Save Answer:
                $this->Mench_ledger->create(array(
                    'link_type' => 7712, //Input Choice
                    'link_player' => $player_e['e__id'],
                    'link_left' => $_POST['focus__id'],
                    'link_number' => $_POST['invoice_items'][$key]['quantity'],
                    'link_right' => $_POST['invoice_items'][$key]['i__id'],
                ));
            }
        }


        //Find Next:
        $i_redirect_url = i_redirect_url($i);
        if(!$i_redirect_url){
            $find_next = $this->Mench_ledger->find_next($player_e['e__id'], $_POST['target_i__hashtag'], $i);
        }


        //Return Data:
        return view__json(array(
            'status' => 1,
            'next__url' => ( $i_redirect_url ? $i_redirect_url : ( $find_next ? $find_next : 'start' ) ),
            'message' => ( $_POST['total_price']>0 ? 'Success: Paypal invoice emailed to '.$set_email.' which you should receive in 1-2 minutes' : 'You have a Zero Balance invoice, so you are all set!' ),
            'invoiceData' => $invoiceData,
        ));


    }
}

