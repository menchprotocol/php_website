<?php

$user_session = user_session(null, 0, $this->user_session);
if(!$user_session){
    return view_json(array(
        'status' => 0,
        'message' => blocked_reasoning(),
    ));
} elseif (!isset($_POST['target_posthashtag']) || !isset($_POST['target_postid']) || !isset($_POST['invoice_items']) || !isset($_POST['do_skip'])) {
    return view_json(array(
        'status' => 0,
        'message' => 'Missing Core Data',
    ));
} elseif(!(filter_var(website_setting(30882), FILTER_VALIDATE_EMAIL) && strlen(website_setting(44355))>10 && strlen(website_setting(44354))>10)) {
    return view_json(array(
        'status' => 0,
        'message' => 'Paypal Invoicing is Not Active on This Domain. Contact Webmaster.',
    ));
}


$items = [];
foreach ($_POST['invoice_items'] as $key => $value) {

    foreach($this->Posts->read(array(
        'postid' => $_POST['invoice_items'][$key]['postid'], //ACTIVE
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
    'chainuserinput' => 3288, //Email
    'chainuseroutput' => $user_session['userid'],
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
));
$fetch_phones = $this->Chains->read(array(
    'chainuserinput' => 4783, //Phone
    'chainuseroutput' => $user_session['userid'],
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
));
$fetch_first_names = $this->Chains->read(array(
    'chainuserinput' => 42584, //First Name
    'chainuseroutput' => $user_session['userid'],
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
));
$fetch_last_names = $this->Chains->read(array(
    'chainuserinput' => 30198, //Last Name
    'chainuseroutput' => $user_session['userid'],
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
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
        'chainuseroutput' => $user_session['userid'],
        'chainusercreator' => $user_session['userid'],
        'chainpostoutput' => $_POST['focus__id'],
    )));
}



foreach($this->Posts->read(array(
    'postid' => $_POST['target_postid'], //ACTIVE
)) as $post_target){

    foreach($this->Posts->read(array(
        'postid' => $_POST['focus__id'], //ACTIVE
    )) as $i){

        $website_logo = one_two_explode('img src="','"',get_domain('m__cover'));
        $invoice_due_dates = $this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput' => 44378, //Invoice Due Date
        ));
        $invoice_min_payments = $this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput' => 44379, //Invoice Min Payment
        ));
        $min_pay = ( count($invoice_min_payments) && floatval($invoice_min_payments[0]['chainvalue'])>0 ? floatval($invoice_min_payments[0]['chainvalue']) : 0 );

        // Usage example
        try {
            // Sample invoice data
            $invoiceData = [
                'invoicer_logo_url' => $website_logo,
                'invoicer_given_name' => view_post_title($post_target, true),
                'invoicer_address_line_1' => '', //Atlas Foundation; Non-Profit #774760508BC0001
                'invoicer_address_line_2' => '', //1122 W 41st Ave, Vancouver, BC, V6M 1W8, Canada
                'invoicer_website' => 'https://'.get_domain('m__message', $user_session['userid']),
                'invoicer_email' => website_setting(30882),

                'note' => $i['posttext'],
                'currency_code' => $_POST['currency_code'],
                'min_payment' => ( $min_pay>0 && $_POST['total_price'] >= $min_pay ? $min_pay."" : "0" ),
                'due_date' => date('Y-m-d', ( $_POST['total_price']>0 && strtotime($invoice_due_dates[0]['chainvalue'])>time() ? strtotime($invoice_due_dates[0]['chainvalue']) : time() )),
                'total_amount' =>  $_POST['total_price'],
                'items' => $items,

                'recipient_name' => count($fetch_first_names) && strlen($fetch_first_names[0]['chainvalue']) ? $fetch_first_names[0]['chainvalue'] : $user_session['username'],
                'recipient_surname' => count($fetch_last_names) ? $fetch_last_names[0]['chainvalue'] : '',
                'recipient_address_line_1' => 'https://'.get_domain('m__message', $user_session['userid']).'/@'.$user_session['userhandle'],
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
            'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            'chainpostinput' => $i['postid'],
            'chainusercreator' => $user_session['userid'],
        ), array(), 0) as $x_discovery){
            $this->Chains->delete($x_discovery['chainid'], $user_session['userid']);
        }

        //Delete Old Child Answers:
        foreach($this->Chains->read(array(
            'chainusertype' => 7712, //Input Choice
            'chainusercreator' => $user_session['userid'],
            'chainpostinput' => $i['postid'],
        ), array('chainpostoutput')) as $x_selection){

            //Remove Selection:
            $this->Chains->delete($x_selection['chainid'], $user_session['userid']);

            //Remove discovery:
            foreach($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                'chainpostinput' => $x_selection['postid'],
                'chainusercreator' => $user_session['userid'],
            ), array(), 0) as $x_discovery){
                $this->Chains->delete($x_discovery['chainid'], $user_session['userid']);
            }
        }


        //Save New Invoice:
        $this->Chains->post_discovered(4559, $user_session['userid'], $post_target['postid'], $i);


        //Save New Child Answers:
        foreach ($_POST['invoice_items'] as $key => $value) {
            foreach($this->Posts->read(array(
                'postid' => $_POST['invoice_items'][$key]['postid'], //ACTIVE
            )) as $this_i){

                //Complete this item:
                $this->Chains->post_discovered(4559, $user_session['userid'], $post_target['postid'], $this_i, array(), array(
                    'chainkey' => $_POST['invoice_items'][$key]['quantity'],
                ));

                //Save Answer:
                $this->Chains->create(array(
                    'chainusertype' => 7712, //Input Choice
                    'chainusercreator' => $user_session['userid'],
                    'chainpostinput' => $_POST['focus__id'],
                    'chainkey' => $_POST['invoice_items'][$key]['quantity'],
                    'chainpostoutput' => $_POST['invoice_items'][$key]['postid'],
                ));
            }
        }


        //Find Next:
        $post_redirect_url = post_redirect_url($i);
        if(!$post_redirect_url){
            $post_next = $this->Chains->next_posts($user_session['userid'], $_POST['target_posthashtag']);
        }


        //Return Data:
        return view_json(array(
            'status' => 1,
            'next__url' => ( $post_redirect_url ? $post_redirect_url : ( $post_next ? $post_next : 'start' ) ),
            'message' => ( $_POST['total_price']>0 ? 'Success: Paypal invoice emailed to '.$set_email.' which you should receive in 1-2 minutes' : 'You have a Zero Balance invoice, so you are all set!' ),
            'invoiceData' => $invoiceData,
        ));


    }
}

