<?php

$player_e = superpower_unlocked(null, 0, $this->player_e);
if(!$player_e){
    return view_json(array(
        'status' => 0,
        'message' => view_unauthorized_message(),
    ));
} elseif (!isset($_POST['target_ideahashtag']) || !isset($_POST['target_ideaid']) || !isset($_POST['invoice_items']) || !isset($_POST['do_skip'])) {
    return view_json(array(
        'status' => 0,
        'message' => 'Missing Core Data',
    ));
} elseif(!(filter_var(website_setting(30882), FILTER_VALIDATE_EMAIL) && strlen(website_setting(44355))>10 && strlen(website_setting(44354))>10)) {
    return view_json(array(
        'status' => 0,
        'message' => 'Paypal Invoicing is Not Active on This Domain... Contact Webmaster...',
    ));
}


$items = [];
foreach ($_POST['invoice_items'] as $key => $value) {

    foreach($this->Nodeideas->fetch(array(
        'ideaid' => $_POST['invoice_items'][$key]['ideaid'], //ACTIVE
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
$fetch_emails = $this->Ledger->fetch(array(
    'linkplayerup' => 3288, //Email
    'linkplayerdown' => $player_e['playerid'],
    'linkplayertype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
));
$fetch_phones = $this->Ledger->fetch(array(
    'linkplayerup' => 4783, //Phone
    'linkplayerdown' => $player_e['playerid'],
    'linkplayertype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
));
$fetch_first_names = $this->Ledger->fetch(array(
    'linkplayerup' => 42584, //First Name
    'linkplayerdown' => $player_e['playerid'],
    'linkplayertype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
));
$fetch_last_names = $this->Ledger->fetch(array(
    'linkplayerup' => 30198, //Last Name
    'linkplayerdown' => $player_e['playerid'],
    'linkplayertype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
));

$set_email = false;
if(count($fetch_emails) && filter_var($fetch_emails[0]['linktext'], FILTER_VALIDATE_EMAIL)) {
    $set_email = $fetch_emails[0]['linktext'];
}
$set_phone = false;
if(count($fetch_phones) && strlen($fetch_phones[0]['linktext'])>=8) {
    $set_phone = $fetch_phones[0]['linktext'];
}

if(!$set_email){
    //No Valid email:
    $this->Ledger->create(array(
        'linkplayertype' => 44179, //Triggered
        'linkplayerup' => 4246, //Platform Bug Reports
        'linkplayerdown' => $player_e['playerid'],
        'linkplayercreator' => $player_e['playerid'],
        'linkidearight' => $_POST['focus__id'],
        'linktext' => 'No Valid email found for invoice',
    ));
    return view_json(array(
        'status' => 0,
        'message' => 'Your account does not have a valid email address for us to send your invoice. Click on Edit Profile from Top/Right menu, edit your email address, and try again.',
    ));
}



foreach($this->Nodeideas->fetch(array(
    'ideaid' => $_POST['target_ideaid'], //ACTIVE
)) as $idea_target){

    foreach($this->Nodeideas->fetch(array(
        'ideaid' => $_POST['focus__id'], //ACTIVE
    )) as $i){

        $website_logo = one_two_explode('img src="','"',get_domain('m__cover'));
        $invoice_due_dates = $this->Ledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkidearight' => $i['ideaid'],
            'linkplayerup' => 44378, //Invoice Due Date
        ));
        $invoice_min_payments = $this->Ledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkidearight' => $i['ideaid'],
            'linkplayerup' => 44379, //Invoice Min Payment
        ));
        $min_pay = ( count($invoice_min_payments) && floatval($invoice_min_payments[0]['linktext'])>0 ? floatval($invoice_min_payments[0]['linktext']) : 0 );

        // Usage example
        try {
            // Sample invoice data
            $invoiceData = [
                'invoicer_logo_url' => $website_logo,
                'invoicer_given_name' => view_idea_title($idea_target, true),
                'invoicer_address_line_1' => '', //Atlas Foundation; Non-Profit #774760508BC0001
                'invoicer_address_line_2' => '', //1122 W 41st Ave, Vancouver, BC, V6M 1W8, Canada
                'invoicer_website' => 'https://'.get_domain('m__message', $player_e['playerid']),
                'invoicer_email' => website_setting(30882),

                'note' => $i['ideatext'],
                'currency_code' => $_POST['currency_code'],
                'min_payment' => ( $min_pay>0 && $_POST['total_price'] >= $min_pay ? $min_pay."" : "0" ),
                'due_date' => date('Y-m-d', ( $_POST['total_price']>0 && strtotime($invoice_due_dates[0]['linktext'])>time() ? strtotime($invoice_due_dates[0]['linktext']) : time() )),
                'total_amount' =>  $_POST['total_price'],
                'items' => $items,

                'recipient_name' => count($fetch_first_names) && strlen($fetch_first_names[0]['linktext']) ? $fetch_first_names[0]['linktext'] : $player_e['playertext'],
                'recipient_surname' => count($fetch_last_names) ? $fetch_last_names[0]['linktext'] : '',
                'recipient_address_line_1' => 'https://'.get_domain('m__message', $player_e['playerid']).'/@'.$player_e['playerhandle'],
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
        foreach($this->Ledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'linkidealeft' => $i['ideaid'],
            'linkplayercreator' => $player_e['playerid'],
        ), array(), 0) as $x_discovery){
            $this->Ledger->update($x_discovery['linkid'], array(), $player_e['playerid']);
        }

        //Delete Old Child Answers:
        foreach($this->Ledger->fetch(array(
            'linkplayertype' => 7712, //Input Choice
            'linkplayercreator' => $player_e['playerid'],
            'linkidealeft' => $i['ideaid'],
        ), array('linkidearight')) as $x_selection){

            //Remove Selection:
            $this->Ledger->update($x_selection['linkid'], array(), $player_e['playerid']);

            //Remove discovery if we can:
            if(!in_array($x_selection['ideatype'], $this->config->item('playerids___42905'))){
                foreach($this->Ledger->fetch(array(
                        'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                    'linkidealeft' => $x_selection['ideaid'],
                    'linkplayercreator' => $player_e['playerid'],
                ), array(), 0) as $x_discovery){
                    $this->Ledger->update($x_discovery['linkid'], array(), $player_e['playerid']);
                }
            }
        }


        //Save New Invoice:
        $this->Ledger->mark_complete(44245, $player_e['playerid'], $idea_target['ideaid'], $i);


        //Save New Child Answers:
        foreach ($_POST['invoice_items'] as $key => $value) {
            foreach($this->Nodeideas->fetch(array(
                'ideaid' => $_POST['invoice_items'][$key]['ideaid'], //ACTIVE
            )) as $this_i){

                //Complete this item:
                $this->Ledger->mark_complete(idea_discovery_link($this_i), $player_e['playerid'], $idea_target['ideaid'], $this_i, array(), array(
                    'linknumber' => $_POST['invoice_items'][$key]['quantity'],
                ));

                //Save Answer:
                $this->Ledger->create(array(
                    'linkplayertype' => 7712, //Input Choice
                    'linkplayercreator' => $player_e['playerid'],
                    'linkidealeft' => $_POST['focus__id'],
                    'linknumber' => $_POST['invoice_items'][$key]['quantity'],
                    'linkidearight' => $_POST['invoice_items'][$key]['ideaid'],
                ));
            }
        }


        //Find Next:
        $idea_redirect_url = idea_redirect_url($i);
        if(!$idea_redirect_url){
            $find_next = $this->Ledger->find_next($player_e['playerid'], $_POST['target_ideahashtag'], $i);
        }


        //Return Data:
        return view_json(array(
            'status' => 1,
            'next__url' => ( $idea_redirect_url ? $idea_redirect_url : ( $find_next ? $find_next : 'start' ) ),
            'message' => ( $_POST['total_price']>0 ? 'Success: Paypal invoice emailed to '.$set_email.' which you should receive in 1-2 minutes' : 'You have a Zero Balance invoice, so you are all set!' ),
            'invoiceData' => $invoiceData,
        ));


    }
}

