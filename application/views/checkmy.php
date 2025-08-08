<?php

//We must have $handle_session['handleid']
echo '<h2>My '.$focus_e['handlename'].'</h2>';

echo '<table class="table table-striped" style="border: 1px solid #000;">';

//Check this users @handle:
$was_found = false;
foreach($this->Chains->read(array(
    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
    'chainhandleinput' => $focus_e['handleid'],
), array('chainhandleoutput'), 0, 0, array('chainkey' => 'ASC'), '*', null, false /* Limited to $handle_session['handleid'] */) as $handle_output){
    foreach($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        'chainhandleinput' => $handle_output['handleid'],
        'chainhandleoutput' => $handle_session['handleid'], //Since we are limiting the query to session user we could disable the $access_limit in the query before it
    ), array('chainhandleinput'), 0, 0, array('chainkey' => 'ASC'), '*', null, false /* Limited to $handle_session['handleid'] */) as $handle_data){
        $was_found = true;
        echo '<tr><td><span class="icon-block-sm">'.view_cover($handle_data['handlecover']).'</span>'.$handle_data['handlename'].':</td><td>'.$handle_data['chainvalue'].'</td></tr>';
    }
}
echo '</table>';


//Check this users @handle:
foreach($this->Chains->read(array(
    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
    'chainhandleinput' => 1727532, //@checkmy
    'chainhandleoutput' => $focus_e['handleid'],
    'LENGTH(chainvalue) > 0' => null,
)) as $handle_info){
    echo '<br /><div>'.preg_replace('/(http[s]{0,1}\:\/\/\S{4,})\s{0,}/ims', '<a href="$1" target="_blank" style="color:#0000FF">$1</a> ', nl2br($handle_info['chainvalue'])).'</div>';
}



if(!$was_found){

    echo '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$focus_e['handlename'].' Not Found for '.$handle_session['handlename'].'! Contact your admin to inquire further as you are not listed here.</div>';

    echo '<div>👤 USD $3,000/Member Camp Dues Includes $1000 Refundable Deposit
⛺ USD $2,000/Group of 1-2 Rent ShiftPod [SOLD OUT]
⛺ USD $2,500/Group of 1-2 Rent ShiftPod [NEW ORDERS]
🚐 USD $1,000/Group of 1-3 Van/Sprinter Park + Power
🚌 USD $2,000/Group of 3-6 RV Park + Power
🚌 USD $3,000/Group of 1-2 RV Park + Power
🍸 USD 200/Member for 3x 1.75L Hard Liquor Bottle
💦 USD 150/Pump Clean Water in Your RV
💩 USD 150/Dump Grey Water in Your RV (2 hose Max)
💩 USD 200/Dump Grey Water in Your RV (4 hose Max)</div>';

} else {

    foreach($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        'chainhandleinput' => $handle_output['handleid'],
        'chainhandleoutput' => $handle_session['handleid'], //Since we are limiting the query to session user we could disable the $access_limit in the query before it
    ), array('chainhandleinput'), 0, 0, array('chainkey' => 'ASC'), '*', null, false /* Limited to $handle_session['handleid'] */) as $handle_data){
        $was_found = true;
        echo '<tr><td><span class="icon-block-sm">'.view_cover($handle_data['handlecover']).'</span>'.$handle_data['handlename'].':</td><td>'.$handle_data['chainvalue'].'</td></tr>';
    }
    
    //Load Paypal Pay button:
    echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';

    echo '<input type="hidden" class="paypal_handling" name="handling" value="0">';
    echo '<input type="hidden" class="hashtagweight" name="quantity" value="1">'; //Dynamic Variable that JS will update
    echo '<input type="hidden" name="item_name" value="Discotique 2025 Camp Dues">';
    echo '<input type="hidden" name="item_number" value="' . ($target_hashtagterm ? $target_hashtagterm . ' #' : '') . $i['hashtagterm'] . ' @' . get_domain('m__handle') . ' @' . $handle_session['handleterm'] . '">';


    echo '<input type="hidden" name="amount" value="' . $unit_price . '">';
    echo '<input type="hidden" name="currency_code" value="' . $unit_currency . '">';
    echo '<input type="hidden" name="no_shipping" value="1">';
    echo '<input type="hidden" name="notify_url" value="https://' . $handles___14870[2738]['m__message'] . view_app_chain(26595) . '">';
    echo '<input type="hidden" name="cancel_return" value="https://' . get_domain('m__message') . view_memory(42903, 30795) . $target_hashtagterm . '/' . $i['hashtagterm'] . '?cancel_pay=1">';
    echo '<input type="hidden" name="return" value="https://' . get_domain('m__message') . view_memory(42903, 30795) . $target_hashtagterm . '/' . $i['hashtagterm'] . '?process_pay=1">';
    echo '<input type="hidden" name="cmd" value="_xclick">';
    echo '<input type="hidden" name="business" value="' . $paypal_email . '">';

    echo '<input type="submit" class="adj-btn pay-btn main__title" name="pay_now" id="pay_now" value="Pay Now >" onclick="$(\'.process-btn\').html(\'Loading\');$(\'#pay_now\').val(\'...\');">';

    echo '</form>';

    echo '<script> $(document).ready(function () { $(\'.hashtag_discovered_btn\').hide(); }); </script>';

    
}