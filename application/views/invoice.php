<?php

$source_session = source_session(null, 0, $this->source_session);
if(!$source_session){
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
    'chainsourceup' => 3288, //Email
    'chainsourcedown' => $source_session['sourceid'],
    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
));
$fetch_phones = $this->Chains->read(array(
    'chainsourceup' => 4783, //Phone
    'chainsourcedown' => $source_session['sourceid'],
    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
));
$fetch_first_names = $this->Chains->read(array(
    'chainsourceup' => 42584, //First Name
    'chainsourcedown' => $source_session['sourceid'],
    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
));
$fetch_last_names = $this->Chains->read(array(
    'chainsourceup' => 30198, //Last Name
    'chainsourcedown' => $source_session['sourceid'],
    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
));

$set_email = false;
if(count($fetch_emails) && filter_var($fetch_emails[0]['chainvalue'], FILTER_VALIDATE_EMAIL)) {
    $set_email = $fetch_emails[0]['chainvalue'];
}
$set_phone = false;
if(count($fetch_phones) && strlen($fetch_phones[0]['chainvalue'])>=8) {
    $set_phone = $fetch_phones[0]['chainvalue'];
}

if(!$set_email){
    //No Valid email:
    return view_json(log_error('Your account does not have a valid email address for us to send your invoice. Click on Edit Profile from Top/Right menu, edit your email address, and try again.', array(
        'chainsourcedown' => $source_session['sourceid'],
        'chainsourcecreator' => $source_session['sourceid'],
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
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42991')) . ')' => null, //Active Writes
            'chainidearight' => $i['ideaid'],
            'chainsourceup' => 44378, //Invoice Due Date
        ));
        $invoice_min_payments = $this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42991')) . ')' => null, //Active Writes
            'chainidearight' => $i['ideaid'],
            'chainsourceup' => 44379, //Invoice Min Payment
        ));
        $min_pay = ( count($invoice_min_payments) && floatval($invoice_min_payments[0]['chainvalue'])>0 ? floatval($invoice_min_payments[0]['chainvalue']) : 0 );

        // Usage example
        try {
            // Sample invoice data
            $invoiceData = [
                'invoicer_logo_url' => $website_logo,
                'invoicer_given_name' => view_idea_title($idea_target, true),
                'invoicer_address_line_1' => '', //Atlas Foundation; Non-Profit #774760508BC0001
                'invoicer_address_line_2' => '', //1122 W 41st Ave, Vancouver, BC, V6M 1W8, Canada
                'invoicer_website' => 'https://'.get_domain('m__message', $source_session['sourceid']),
                'invoicer_email' => website_setting(30882),

                'note' => $i['ideatext'],
                'currency_code' => $_POST['currency_code'],
                'min_payment' => ( $min_pay>0 && $_POST['total_price'] >= $min_pay ? $min_pay."" : "0" ),
                'due_date' => date('Y-m-d', ( $_POST['total_price']>0 && strtotime($invoice_due_dates[0]['chainvalue'])>time() ? strtotime($invoice_due_dates[0]['chainvalue']) : time() )),
                'total_amount' =>  $_POST['total_price'],
                'items' => $items,

                'recipient_name' => count($fetch_first_names) && strlen($fetch_first_names[0]['chainvalue']) ? $fetch_first_names[0]['chainvalue'] : $source_session['sourcetext'],
                'recipient_surname' => count($fetch_last_names) ? $fetch_last_names[0]['chainvalue'] : '',
                'recipient_address_line_1' => 'https://'.get_domain('m__message', $source_session['sourceid']).'/@'.$source_session['sourcehandle'],
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
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'chainidealeft' => $i['ideaid'],
            'chainsourcecreator' => $source_session['sourceid'],
        ), array(), 0) as $x_discovery){
            $this->Chains->delete($x_discovery['chainid'], $source_session['sourceid']);
        }

        //Delete Old Child Answers:
        foreach($this->Chains->read(array(
            'chainsourcetype' => 7712, //Input Choice
            'chainsourcecreator' => $source_session['sourceid'],
            'chainidealeft' => $i['ideaid'],
        ), array('chainidearight')) as $x_selection){

            //Remove Selection:
            $this->Chains->delete($x_selection['chainid'], $source_session['sourceid']);

            //Remove discovery if we can:
            if(!in_array($x_selection['ideatype'], $this->config->item('sourceids___42905'))){
                foreach($this->Chains->read(array(
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                    'chainidealeft' => $x_selection['ideaid'],
                    'chainsourcecreator' => $source_session['sourceid'],
                ), array(), 0) as $x_discovery){
                    $this->Chains->delete($x_discovery['chainid'], $source_session['sourceid']);
                }
            }
        }


        //Save New Invoice:
        $this->Chains->idea_discovered(44245, $source_session['sourceid'], $idea_target['ideaid'], $i);


        //Save New Child Answers:
        foreach ($_POST['invoice_items'] as $key => $value) {
            foreach($this->Ideas->read(array(
                'ideaid' => $_POST['invoice_items'][$key]['ideaid'], //ACTIVE
            )) as $this_i){

                //Complete this item:
                $this->Chains->idea_discovered(idea_type_discovery($this_i), $source_session['sourceid'], $idea_target['ideaid'], $this_i, array(), array(
                    'chainkey' => $_POST['invoice_items'][$key]['quantity'],
                ));

                //Save Answer:
                $this->Chains->create(array(
                    'chainsourcetype' => 7712, //Input Choice
                    'chainsourcecreator' => $source_session['sourceid'],
                    'chainidealeft' => $_POST['focus__id'],
                    'chainkey' => $_POST['invoice_items'][$key]['quantity'],
                    'chainidearight' => $_POST['invoice_items'][$key]['ideaid'],
                ));
            }
        }


        //Find Next:
        $idea_redirect_url = idea_redirect_url($i);
        if(!$idea_redirect_url){
            $idea_next = $this->Chains->idea_next($source_session['sourceid'], $_POST['target_ideahashtag'], $i);
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

