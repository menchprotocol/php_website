<?php

$player_session = player_session(null, 0, $this->player_session);
if(!$player_session){
    return view_json(array(
        'status' => 0,
        'message' => blocked_reasoning(),
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

    foreach($this->Ideas->read(array(
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
$fetch_emails = $this->Chains->read(array(
    'chainplayerup' => 3288, //Email
    'chainplayerdown' => $player_session['playerid'],
    'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
));
$fetch_phones = $this->Chains->read(array(
    'chainplayerup' => 4783, //Phone
    'chainplayerdown' => $player_session['playerid'],
    'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
));
$fetch_first_names = $this->Chains->read(array(
    'chainplayerup' => 42584, //First Name
    'chainplayerdown' => $player_session['playerid'],
    'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
));
$fetch_last_names = $this->Chains->read(array(
    'chainplayerup' => 30198, //Last Name
    'chainplayerdown' => $player_session['playerid'],
    'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
));

$set_email = false;
if(count($fetch_emails) && filter_var($fetch_emails[0]['chaintext'], FILTER_VALIDATE_EMAIL)) {
    $set_email = $fetch_emails[0]['chaintext'];
}
$set_phone = false;
if(count($fetch_phones) && strlen($fetch_phones[0]['chaintext'])>=8) {
    $set_phone = $fetch_phones[0]['chaintext'];
}

if(!$set_email){
    //No Valid email:
    return view_json(log_error('Your account does not have a valid email address for us to send your invoice. Click on Edit Profile from Top/Right menu, edit your email address, and try again.', array(
        'chainplayerdown' => $player_session['playerid'],
        'chainplayercreator' => $player_session['playerid'],
        'chainidearight' => $_POST['focus__id'],
    )));
}



foreach($this->Ideas->read(array(
    'ideaid' => $_POST['target_ideaid'], //ACTIVE
)) as $idea_target){

    foreach($this->Ideas->read(array(
        'ideaid' => $_POST['focus__id'], //ACTIVE
    )) as $i){

        $website_logo = one_two_explode('img src="','"',get_domain('m__cover'));
        $invoice_due_dates = $this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'chainidearight' => $i['ideaid'],
            'chainplayerup' => 44378, //Invoice Due Date
        ));
        $invoice_min_payments = $this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'chainidearight' => $i['ideaid'],
            'chainplayerup' => 44379, //Invoice Min Payment
        ));
        $min_pay = ( count($invoice_min_payments) && floatval($invoice_min_payments[0]['chaintext'])>0 ? floatval($invoice_min_payments[0]['chaintext']) : 0 );

        // Usage example
        try {
            // Sample invoice data
            $invoiceData = [
                'invoicer_logo_url' => $website_logo,
                'invoicer_given_name' => view_idea_title($idea_target, true),
                'invoicer_address_line_1' => '', //Atlas Foundation; Non-Profit #774760508BC0001
                'invoicer_address_line_2' => '', //1122 W 41st Ave, Vancouver, BC, V6M 1W8, Canada
                'invoicer_website' => 'https://'.get_domain('m__message', $player_session['playerid']),
                'invoicer_email' => website_setting(30882),

                'note' => $i['ideatext'],
                'currency_code' => $_POST['currency_code'],
                'min_payment' => ( $min_pay>0 && $_POST['total_price'] >= $min_pay ? $min_pay."" : "0" ),
                'due_date' => date('Y-m-d', ( $_POST['total_price']>0 && strtotime($invoice_due_dates[0]['chaintext'])>time() ? strtotime($invoice_due_dates[0]['chaintext']) : time() )),
                'total_amount' =>  $_POST['total_price'],
                'items' => $items,

                'recipient_name' => count($fetch_first_names) && strlen($fetch_first_names[0]['chaintext']) ? $fetch_first_names[0]['chaintext'] : $player_session['playertext'],
                'recipient_surname' => count($fetch_last_names) ? $fetch_last_names[0]['chaintext'] : '',
                'recipient_address_line_1' => 'https://'.get_domain('m__message', $player_session['playerid']).'/@'.$player_session['playerhandle'],
                'recipient_address_line_2' => ( $set_phone ? $set_phone : '' ),
                'recipient_email' => $set_email,
            ];

            // Step 1: Get access token
            $accessToken = paypal_token(website_setting(44354), website_setting(44355));

            // Step 2: Create invoice
            $invoiceId = paypal_invoice($accessToken, $invoiceData);

            // Step 3: Send invoice
            sendPaypalInvoice($accessToken, $invoiceId);

        } catch (Exception $e) {
            //It catches an exception even though invoice is created
        }




        //Delete Old Parent Invoice:
        foreach($this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'chainidealeft' => $i['ideaid'],
            'chainplayercreator' => $player_session['playerid'],
        ), array(), 0) as $x_discovery){
            $this->Chains->delete($x_discovery['chainid'], $player_session['playerid']);
        }

        //Delete Old Child Answers:
        foreach($this->Chains->read(array(
            'chainplayertype' => 7712, //Input Choice
            'chainplayercreator' => $player_session['playerid'],
            'chainidealeft' => $i['ideaid'],
        ), array('chainidearight')) as $x_selection){

            //Remove Selection:
            $this->Chains->delete($x_selection['chainid'], $player_session['playerid']);

            //Remove discovery if we can:
            if(!in_array($x_selection['ideatype'], $this->config->item('playerids___42905'))){
                foreach($this->Chains->read(array(
                        'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                    'chainidealeft' => $x_selection['ideaid'],
                    'chainplayercreator' => $player_session['playerid'],
                ), array(), 0) as $x_discovery){
                    $this->Chains->delete($x_discovery['chainid'], $player_session['playerid']);
                }
            }
        }


        //Save New Invoice:
        $this->Chains->idea_discovered(44245, $player_session['playerid'], $idea_target['ideaid'], $i);


        //Save New Child Answers:
        foreach ($_POST['invoice_items'] as $key => $value) {
            foreach($this->Ideas->read(array(
                'ideaid' => $_POST['invoice_items'][$key]['ideaid'], //ACTIVE
            )) as $this_i){

                //Complete this item:
                $this->Chains->idea_discovered(idea_type_discovery($this_i), $player_session['playerid'], $idea_target['ideaid'], $this_i, array(), array(
                    'chainnumber' => $_POST['invoice_items'][$key]['quantity'],
                ));

                //Save Answer:
                $this->Chains->create(array(
                    'chainplayertype' => 7712, //Input Choice
                    'chainplayercreator' => $player_session['playerid'],
                    'chainidealeft' => $_POST['focus__id'],
                    'chainnumber' => $_POST['invoice_items'][$key]['quantity'],
                    'chainidearight' => $_POST['invoice_items'][$key]['ideaid'],
                ));
            }
        }


        //Find Next:
        $idea_redirect_url = idea_redirect_url($i);
        if(!$idea_redirect_url){
            $idea_next = $this->Chains->idea_next($player_session['playerid'], $_POST['target_ideahashtag'], $i);
        }


        //Return Data:
        return view_json(array(
            'status' => 1,
            'next__url' => ( $idea_redirect_url ? $idea_redirect_url : ( $idea_next ? $idea_next : 'start' ) ),
            'message' => ( $_POST['total_price']>0 ? 'Success: Paypal invoice emailed to '.$set_email.' which you should receive in 1-2 minutes' : 'You have a Zero Balance invoice, so you are all set!' ),
            'invoiceData' => $invoiceData,
        ));


    }
}

