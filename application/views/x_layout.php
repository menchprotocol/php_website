<?php

if(!in_array($i['i__access'], $this->config->item('n___31870')) && !e_of_i($i['i__id'])){

    echo '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> This idea is not public at this time.</div>';

} else {

$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$e___31127 = $this->config->item('e___31127'); //Action Buttons Pending
$e___4737 = $this->config->item('e___4737'); //Idea Types
$is_or_idea = in_array($i['i__type'], $this->config->item('n___7712'));
$is_single_click = in_array($i['i__type'], $this->config->item('n___40680'));

//NEXT IDEAS
$is_next = $this->X_model->fetch(array(
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'x__left' => $i['i__id'],
), array('x__right'), 0, 0, array('x__weight' => 'ASC'));

//Filter Next Ideas:
foreach($is_next as $in_key => $in_value){
    $i_is_available = i_is_available($in_value['i__id'], false);
    if(!$i_is_available['status']){
        //Remove this option:
        unset($is_next[$in_key]);
    }
}



$i['i__title'] = str_replace('"','',$i['i__title']);
$x__creator = ( $member_e ? $member_e['e__id'] : 0 );
$top_i__id = ( $x__creator>0 ? $top_i__id : 0 );
$x_completes = ( $top_i__id ? $this->X_model->fetch(array(
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
    'x__creator' => $x__creator,
    'x__left' => $i['i__id'],
)) : array() );
$top_completed = false; //Assume main intent not yet completed, unless proven otherwise...
$can_skip = count($this->X_model->fetch(array(
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
    'x__right' => $i['i__id'],
    'x__up' => 28239, //Can Skip
)));
$can_edit_response = !count($this->X_model->fetch(array(
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
    'x__right' => $i['i__id'],
    'x__up' => 33330, //No Editing
)));



//Open URL?
foreach($this->X_model->fetch(array(
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
    'x__right' => $i['i__id'],
    'x__up' => 34461, //Redirect URL
)) as $redirect_url){
    if(filter_var($redirect_url['x__message'], FILTER_VALIDATE_URL)){
        js_php_redirect($redirect_url['x__message'], 0);
        break;
    }
}








//Check for time limits?
if($x__creator && $top_i__id!=$i['i__id']){

    $find_previous = $this->X_model->find_previous($x__creator, $top_i__id, $i['i__id']);
    if(count($find_previous)){

        $nav_list = array();
        $main_branch = array(intval($i['i__id']));
        foreach($find_previous as $followings_i){
            //First add-up the main branch:
            array_push($main_branch, intval($followings_i['i__id']));
        }

        $breadcrum_content = null;
        $level = 0;
        foreach($find_previous as $followings_i){

            $level++;

            //Does this have a follower list?
            $query_subset = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'x__left' => $followings_i['i__id'],
            ), array('x__right'), 0, 0, array('x__weight' => 'ASC'));
            foreach($query_subset as $key=>$value){
                $i_is_available = i_is_available($value['i__id'], false);
                if(!$i_is_available['status'] || !count($this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'x__creator' => $x__creator,
                        'x__left' => $value['i__id'],
                    )))){
                    unset($query_subset[$key]);
                }
            }

            $messages = count($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 4231, //IDEA NOTES Messages
                'x__right' => $followings_i['i__id'],
            )));

            $breadcrum_content .= '<li class="breadcrumb-item">';
            $breadcrum_content .= '<a href="/'.$top_i__id.'/'.$followings_i['i__id'].'"><u>'.$followings_i['i__title'].'</u></a>';

            //Do we have more sub-items in this branch? Must have more than 1 to show, otherwise the 1 will be included in the main branch:
            if(count($query_subset) >= 2){
                //Show other branches:
                $breadcrum_content .= '<div class="dropdown inline-block">';
                $breadcrum_content .= '<button type="button" class="btn no-side-padding" id="dropdownMenuButton'.$followings_i['i__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $breadcrum_content .= '<span style="padding-left:5px;"><i class="fal fa-chevron-square-down"></i></span>';
                $breadcrum_content .= '</button>';
                $breadcrum_content .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$followings_i['i__id'].'">';
                foreach ($query_subset as $i_subset) {
                    $breadcrum_content .= '<a href="/'.$top_i__id.'/'.$i_subset['i__id'].'" class="dropdown-item main__title '.( in_array($i_subset['i__id'], $main_branch) ? ' active ' : '' ).'">'.$i_subset['i__title'].'</a>';
                }
                $breadcrum_content .= '</div>';
                $breadcrum_content .= '</div>';
            }

            $breadcrum_content .= '</li>';

        }

        if($breadcrum_content){
            echo '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
            echo $breadcrum_content;
            echo '</ol></nav>';
        }

    }

}



if($top_i__id){

    $is_this = $this->I_model->fetch(array(
        'i__id' => $top_i__id,
    ));
    $tree_progress = $this->X_model->tree_progress($x__creator, $is_this[0]);
    $top_completed = $tree_progress['fixed_completed_percentage'] >= 100;
    $go_next_url = '/x/x_next/' . $top_i__id . '/' . $i['i__id'];

    if($top_completed){
        echo '<div class="msg alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>100% Complete</div>';
    }

    if(isset($_GET['list'])){
        echo '<p style="padding:10px;">'.$tree_progress['fixed_discovered'].' of '.$tree_progress['fixed_total'].' Discovered:</p>';
        $counter = 0;
        foreach($tree_progress['list_total'] as $to_discover_id){
            $is = $this->I_model->fetch(array(
                'i__id' => $to_discover_id,
            ));
            $counter++;
            echo '<p style="padding:2px;">'.$counter.') <a href="/~'.$is[0]['i__id'].'">'.( in_array($is[0]['i__id'], $tree_progress['list_discovered']) ? '✅ ' : '' ).$is[0]['i__title'].'</p>';
        }
    }

} else {

    $go_next_url = '/x/x_start/'.$i['i__id'];

}

if($top_completed || $is_or_idea){
    $_GET['open'] = true;
}



//Audio Playback
$require_playback = count($this->X_model->fetch(array(
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
    'x__right' => $i['i__id'],
    'x__up' => 33253, //Auto Play Audio
)));
echo '<input type="hidden" id="requires_audio_play" value="'.( $require_playback ? 1 : 0 ).'">';
if($require_playback){
    //Try to auto play audio as it may work in certain cases with certain browsers
    echo '<script type="text/javascript"> $(document).ready(function () { $(\'audio\')[0].play(); }); </script>';
}


echo '<div class="light-bg large-frame">';

//Title:
echo '<h1 class="msg-frame" style="text-align: left; padding: 10px 0 !important; font-size:2.5em;">'.$i['i__title'].'</h1>';

//Messages:
$view_i__cache = view_i__cache($i);
if($view_i__cache){
    echo $view_i__cache;
} elseif(!count($x_completes) && $i['i__type']==6677 && $top_i__id && $member_e) {
    //Auto complete:
    echo '<script> $(document).ready(function () { go_next() }); </script>';
}



$x_selects = array();
if($top_i__id || 1) {

    if ($is_or_idea) {

        //Has no followers:
        if (!count($is_next)) {

            //Mark this as complete since there is no follower to choose from:
            if (!count($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__creator' => $x__creator,
                'x__left' => $i['i__id'],
            )))) {

                array_push($x_completes, $this->X_model->mark_complete($top_i__id, $i, array(
                    'x__type' => 4559, //Read Statement
                    'x__creator' => $x__creator,
                )));

            }

        } else {

            //First fetch answers based on correct order:
            foreach ($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'x__left' => $i['i__id'],
            ), array('x__right'), 0, 0, array('x__weight' => 'ASC')) as $x) {
                //See if this answer was selected:
                if (count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                    'x__left' => $i['i__id'],
                    'x__right' => $x['i__id'],
                    'x__creator' => $x__creator,
                )))) {
                    array_push($x_selects, $x);
                }
            }

            if (count($x_selects) > 0) {
                //MODIFY ANSWER
                echo '<div class="edit_toggle_answer">';
                echo view_i_list(13980, $top_i__id, $i, $x_selects, $member_e);
                echo '</div>';
            }


            //Open for list to be printed:
            $select_answer = '<div class="row list-answers" i__type="' . $i['i__type'] . '">';

            //List followers to choose from:
            foreach ($is_next as $key => $next_i) {

                //Has this been previously selected?
                $previously_selected = count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                    'x__left' => $i['i__id'],
                    'x__right' => $next_i['i__id'],
                    'x__creator' => $x__creator,
                )));

                $select_answer .= view_card_x_select($next_i, $x__creator, $previously_selected);

            }


            $select_answer .= '</div>';

            //HTML:
            echo '<div class="edit_toggle_answer ' . (count($x_selects) > 0 ? 'hidden' : '') . '">';
            echo view_headline($i['i__type'], null, $e___4737[$i['i__type']], $select_answer, true);
            echo '</div>';

        }

    } elseif ($i['i__type']==26560) {

        //TICKET
        $ticket_ui = '';

        if(isset($_GET['cancel_pay']) && !count($x_completes)){
            $ticket_ui .= '<div class="msg alert alert-danger" role="alert">You cancelled your payment.</div>';
        }


        if(isset($_GET['process_pay']) && !count($x_completes)){

            $ticket_ui .= '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Processing your payment, please wait...</div>';

            //Referesh soon so we can check if completed or not
            js_php_redirect('/'.$top_i__id.'/'.$i['i__id'].'?process_pay=1', 2584);

        } elseif(count($x_completes)){

            foreach($x_completes as $x_complete){
                $x__metadata = unserialize($x_complete['x__metadata']);
                $quantity = ( isset($x__metadata['quantity']) && $x__metadata['quantity']>1 ? $x__metadata['quantity'] : ( $x_complete['x__weight'] > 0 ? $x_complete['x__weight'] : 1 ) );

                if($x__metadata['mc_gross']!=0){
                    $ticket_ui .= '<div class="msg alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>'.( $x__metadata['mc_gross']>0 ? 'You paid ' : 'You got a refund of ' ).$x__metadata['mc_currency'].' '.str_replace('.00','',$x__metadata['mc_gross']).( $quantity>1 ? ' for '.$quantity.' tickets' : '' ).'</div>';
                }

                if($x__metadata['mc_gross']>=0){
                    $ticket_ui .= '<div class="msg"><span>Here is your QR Ticket that has also been emailed to you:</span></div>';
                    $ticket_ui .= '<div>'.qr_code('https://'.get_domain('m__message', ( isset($member_e['e__id']) ? $member_e['e__id'] : 0 )).'/-26560?x__id='.$x_complete['x__id'].'&x__creator='.$x_complete['x__creator'].'&checkin_32016=1').'</div>';
                }
            }


            $ticket_ui .= '<input type="hidden" id="paypal_handling" name="handling" value="'.$x__metadata['mc_gross'].'">';
            $ticket_ui .= '<input type="hidden" id="paypal_quantity" name="quantity" value="'.$x__metadata['quantity'].'">'; //Dynamic Variable that JS will update

            //Invite Your Friends (If 2 or more items):
            if($x__metadata['quantity']>1 && 0){

                //TODO Complete

                $ticket_ui .= '<h2>Invite Your Friends</h2>';
                $ticket_ui .= '<p>So they can get inside independantly. If not invited, they must check-in with you.</p>';

                for($f=2;$f<=$x__metadata['quantity'];$f++) {
                    $ticket_ui .= '<div class="row">';
                    $ticket_ui .= '<div class="col-6 col-md-4 col-lg-3">Ticket #'.$f.' Name:</div>';
                    $ticket_ui .= '<div class="col-6 col-md-4 col-lg-3"><input type="text" id="invite_name_'.$f.'" placeholder="Full Name" class="form-control white-border border maxout" /></div>';
                    $ticket_ui .= '</div>';
                    $ticket_ui .= '<div class="row">';
                    $ticket_ui .= '<div class="col-6 col-md-4 col-lg-3"><input type="email" id="invite_email_'.$f.'" placeholder="Email" class="form-control white-border border maxout" /></div>';
                    $ticket_ui .= '<div class="col-6 col-md-4 col-lg-3"><input type="number" id="invite_phone_'.$f.'" placeholder="Cell Phone" class="form-control white-border border maxout" /></div>';
                    $ticket_ui .= '</div>';
                    $ticket_ui .= '<br /><br />';
                }

                $ticket_ui .= '<h3>Custom Message</h3>';
                $ticket_ui .= '<textarea class="border i_content  x_input" placeholder="" id="invite_message"></textarea>';
                $ticket_ui .= '<script> $(document).ready(function () { set_autosize($(\'#invite_message\')); }); </script>';

            }



        } else {


            $valid_paid_ticket = false; //Unless proven otherwise
            $min_allowed = 1;
            $detect_data_type = 0;

            $paypal_email =  website_setting(30882);

            $total_dues = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $i['i__id'],
                'x__up' => 26562, //Total Due
            ));
            $cart_max = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $i['i__id'],
                'x__up' => 29651, //Cart Max Quantity
            ));
            $cart_min = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $i['i__id'],
                'x__up' => 31008, //Cart Min Quantity
            ));



            //Payments Must have Unit Price, otherwise they are NOT a payment until added...
            $info_append = '';
            $unit_currency = '';
            $unit_price = 0;
            $unit_fee = 0;
            $max_allowed = ( count($cart_max) && is_numeric($cart_max[0]['x__message']) && $cart_max[0]['x__message']>1 ? intval($cart_max[0]['x__message']) : view_memory(6404,29651) );
            $spots_remaining = i_spots_remaining($i['i__id']);
            $max_allowed = ( $spots_remaining>-1 && $spots_remaining<$max_allowed ? $spots_remaining : $max_allowed );
            $max_allowed = ( $max_allowed < 1 ? 1 : $max_allowed );
            $min_allowed = ( count($cart_min) && is_numeric($cart_min[0]['x__message']) && intval($cart_min[0]['x__message'])>0 ? intval($cart_min[0]['x__message']) : $min_allowed );
            $min_allowed = ( $min_allowed < 1 ? 1 : $min_allowed );


            if(count($total_dues) && filter_var($paypal_email, FILTER_VALIDATE_EMAIL)){
                $detect_data_type = detect_data_type($total_dues[0]['x__message']);
                if ($detect_data_type['status'] && $detect_data_type['x__type']==26661){

                    $valid_paid_ticket = true;

                    $digest_fees = $this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                        'x__right' => $i['i__id'],
                        'x__up' => 30589, //Digest Fees
                    ));
                    $allow_refunds = $this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                        'x__right' => $i['i__id'],
                        'x__up' => 30615, //Is Refundable
                    ));

                    //Break down amount & currency
                    $currency_parts = explode(' ',$total_dues[0]['x__message'],2);
                    $unit_currency = $currency_parts[0];
                    $unit_price = number_format($currency_parts[1], 2, ".", "");
                    $unit_fee = number_format($currency_parts[1] * ( count($digest_fees) ? 0 : (doubleval(website_setting(30590, $x__creator)) + doubleval(website_setting(27017, $x__creator)) + doubleval(website_setting(30612, $x__creator)))/100 ), 2, ".", "");

                    //Append information to cart:
                    $info_append .= '<div class="sub_note">';
                    if(!count($allow_refunds)){
                        $info_append .= 'Final sale. ';
                    }

                    $info_append .= 'You Do Not need to create a Paypal account: You can pay by only entering your credit card details & checkout as a guest. Once paid, click "<span style="color: #990000;">Return to Merchant</span>" to continue back here. By paying you agree to our <a href="/-14373" target="_blank"><u>Terms of Use</u></a>.';
                    $info_append .= '</div>';

                }
            }








            //Is multi selectable, allow show down for quantity:

            $ticket_ui .= '<div class="source-info ticket-notice">'
                . '<span class="icon-block">'. $e___11035[31837]['m__cover'] . '</span>'
                . '<span>'.$e___11035[31837]['m__title'] . '</span>'
                . '<div class="payment_box">'
                . ( strlen($e___11035[31837]['m__message']) ? '<div class="sub_note main__title">'.nl2br($e___11035[31837]['m__message']).'</div>' : '' );


            if($max_allowed > 1 || $min_allowed > 1){
                $ticket_ui .= '<div>';
                $ticket_ui .= '<a href="javascript:void(0);" onclick="sale_increment(-1)" class="adjust_counter"><i class="fas fa-minus-circle"></i></a>';
                $ticket_ui .= '<span id="current_sales" class="main__title" style="display: inline-block; min-width:34px; text-align: center;">'.$min_allowed.'</span>';
                $ticket_ui .= '<a href="javascript:void(0);" onclick="sale_increment(1)" class="adjust_counter"><i class="fas fa-plus-circle"></i></a>';
                $ticket_ui .= '</div>';
            } else {
                $ticket_ui .= '<span id="current_sales" style="display: none;">'.$min_allowed.'</span>';
            }


            if($unit_price > 0){
                $ticket_ui .= '<div style="padding: 8px 0 21px;" '.( $unit_fee > 0 ? ' title="Base Price of '.$unit_price.' + '.$unit_fee.' in Fees" data-toggle="tooltip" data-placement="top" ' : '' ).'><span id="total_ui" class="main__title">'.(($unit_fee+$unit_price)*$min_allowed).'</span> '.$unit_currency.'</div>';
            } else {
                $ticket_ui .= '<span id="total_ui" style="display: none;">0</span>';
            }

            $ticket_ui .= $info_append;

            $ticket_ui .= '</div>';
            $ticket_ui .= '</div>';


            if($valid_paid_ticket){

                //Load Paypal Pay button:
                $ticket_ui .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_form" target="_top">';

                $ticket_ui .= '<input type="hidden" id="paypal_handling" name="handling" value="'.$unit_fee.'">';
                $ticket_ui .= '<input type="hidden" id="paypal_quantity" name="quantity" value="'.$min_allowed.'">'; //Dynamic Variable that JS will update
                $ticket_ui .= '<input type="hidden" id="paypal_item_name" name="item_name" value="'.$i['i__title'].'">';
                $ticket_ui .= '<input type="hidden" id="paypal_item_number" name="item_number" value="'.$top_i__id.'-'.$i['i__id'].'-0-'.$x__creator.'">';

                $ticket_ui .= '<input type="hidden" name="amount" value="'.$unit_price.'">';
                $ticket_ui .= '<input type="hidden" name="currency_code" value="'.$unit_currency.'">';
                $ticket_ui .= '<input type="hidden" name="no_shipping" value="1">';
                $ticket_ui .= '<input type="hidden" name="notify_url" value="https://mench.com/-26595">';
                $ticket_ui .= '<input type="hidden" name="cancel_return" value="https://'.get_domain('m__message').'/'.$top_i__id.'/'.$i['i__id'].'?cancel_pay=1">';
                $ticket_ui .= '<input type="hidden" name="return" value="https://'.get_domain('m__message').'/'.$top_i__id.'/'.$i['i__id'].'?process_pay=1">';
                $ticket_ui .= '<input type="hidden" name="cmd" value="_xclick">';
                $ticket_ui .= '<input type="hidden" name="business" value="'.$paypal_email.'">';

                $ticket_ui .= '<input type="submit" class="adj-btn pay-btn main__title" name="pay_now" id="pay_now" value="Pay Now >" onclick="$(\'.process-btn\').html(\'Loading...\');$(\'#pay_now\').val(\'...\');">';

                $ticket_ui .= '</form>';

                //Hide Standard Next Button:
                $ticket_ui .= '<script type="text/javascript"> $(document).ready(function () { $("#next_div").hide(); }); </script>';

            } else {

                //FREE TICKET
                $ticket_ui .= '<input type="hidden" id="paypal_handling" name="handling" value="'.$unit_fee.'">';
                $ticket_ui .= '<input type="hidden" id="paypal_quantity" name="quantity" value="'.$min_allowed.'">'; //Dynamic Variable that JS will update

            }

            ?>

            <script type="text/javascript">
                var busy_processing = false;
                function sale_increment(increment){

                    var new_quantity = parseInt($('#current_sales').text()) + increment;
                    var max_allowed = <?= $max_allowed ?>;
                    var min_allowed = <?= $min_allowed ?>;
                    if(new_quantity<1){
                        //Invalid new quantity
                        return false;
                    } else if (new_quantity<min_allowed){
                        if(min_allowed>1){
                            alert('Error: Minimum Allowed is '+min_allowed);
                        }
                        return false;
                    } else if (new_quantity>max_allowed){
                        alert('Error: Maximum Allowed is '+max_allowed);
                        return false;
                    } else if(busy_processing){
                        return false;
                    }

                    busy_processing = true;
                    var unit_total = <?= ($unit_fee+$unit_price); ?>;
                    var unit_fee = <?= $unit_fee; ?>;
                    var handling_total = ( unit_fee * new_quantity );
                    var new_total = ( unit_total * new_quantity );

                    //Update UI:
                    $("#paypal_quantity").val(new_quantity);
                    $("#paypal_handling").val(handling_total);
                    $("#current_sales").text(new_quantity);
                    $("#total_ui").text(new_total.toFixed(2));

                    busy_processing = false;

                }
            </script>

            <?php


        }

        echo $ticket_ui;

    } elseif ($i['i__type'] == 40787) {

        //Sheet
        $e___6287 = $this->config->item('e___6287'); //APP
        $e___4737 = $this->config->item('e___4737'); //Idea Types
        $e___6177 = $this->config->item('e___6177'); //Source Status
        $e___31004 = $this->config->item('e___31004'); //Idea Status
        $e___40787 = $this->config->item('e___40787'); //Sheet Link Types

        $underdot_class = ( !isset($_GET['expand']) ? ' class="underdot" ' : '' );
        $column_sources = array();
        $column_ideas = array();
        $setting_links = array(); //To compile the settings of this sheet:
        $recursive_i_ids = array();
        $is_with_action_es = array();
        $es_added = array();

        //Compile key settings for this sheet:
        foreach($e___40787 as $x__type => $m) {
            $setting_links[intval($x__type)] = array(); //Assume no links for this type
        }
        //Now search for these settings across sources:
        foreach($this->X_model->fetch(array(
            'x__right' => $i['i__id'],
            'x__type IN (' . join(',', $this->config->item('n___40787')) . ')' => null, //Sheets
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__access IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
        ), array('x__up'), 0) as $setting_link){
            array_push($setting_links[intval($setting_link['x__type'])], intval($setting_link['x__up']));
        }
        //Now search for these settings across ideas:
        foreach($this->X_model->fetch(array(
            'x__left' => $i['i__id'],
            'x__type IN (' . join(',', $this->config->item('n___40787')) . ')' => null, //Sheets
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
        ), array('x__right'), 0) as $setting_link){
            array_push($setting_links[intval($setting_link['x__type'])], intval($setting_link['x__right']));
        }

        //Can only have one focus view, pick first:
        if(count($setting_links[34513])){
            foreach($setting_links[34513] as $first_frame){
                $setting_links[34513] = $first_frame;
                break;
            }
        } else {
            $setting_links[34513] = 0;
        }

        //Fetch Main Idea:
        if(!count($setting_links[40791])){
            //Set the current idea as default if not set...
            $setting_links[40791] = array($i['i__id']);
        }

        print_r($setting_links);

        if($setting_links[34513]){

            foreach($this->I_model->fetch(array(
                'i__id IN (' . join(',', $setting_links[40791]) . ')' => null, //SOURCE LINKS
            ), 0, 0, array('i__id' => 'ASC')) as $loaded_i) {
                echo '<h2><span class="icon-block" title="'.$e___40787[40791]['m__title'].'">'.$e___40787[40791]['m__cover'].'</span><a href="/~' . $loaded_i['i__id'] . '"><u>' . $loaded_i['i__title'] . '</u></a></h2>';
            }

            foreach($this->E_model->fetch(array(
                'e__id' => $setting_links[34513],
            )) as $grid){
                echo '<h3><a href="/@' . $grid['e__id'] . '"><span class="icon-block">'.view_cover($grid['e__cover'], true). '</span><u>' . $grid['e__title'] . '</u></a></h3>';
            }

        } else {

            foreach($this->I_model->fetch(array(
                'i__id IN (' . join(',', $setting_links[40791]) . ')' => null, //SOURCE LINKS
            ), 0, 0, array('i__id' => 'ASC')) as $loaded_i){

                $all_ids = $this->I_model->recursive_down_ids($loaded_i, 'ALL');
                $or_ids = $this->I_model->recursive_down_ids($loaded_i, 'OR');

                echo '<h2><a href="/~'.$loaded_i['i__id'].'">'.$loaded_i['i__title'].'</a> (<a href="javascript:void(0);" onclick="$(\'.idea_list\').toggleClass(\'hidden\');">'.count($all_ids).' IDEAS</a>)</h2>';
                $recursive_i_ids = array_merge($recursive_i_ids, $all_ids);

                echo '<div class="hidden idea_list">';
                echo '<div>'.count($all_ids).' Total Ideas:</div>';
                $count = 0;
                foreach($all_ids as $recursive_down_id){
                    foreach($this->I_model->fetch(array(
                        'i__id' => $recursive_down_id,
                    ), 0, 0, array('i__id' => 'ASC')) as $this_i){
                        $count++;
                        echo '<p>'.$count.') <a href="/~'.$this_i['i__id'].'">'.$this_i['i__title'].'</a></p>';

                        if(!$setting_links[34513]){
                            foreach($this->X_model->fetch(array(
                                'x__right' => $this_i['i__id'],
                                'x__type IN (' . join(',', $this->config->item('n___31023')) . ')' => null, //Idea Source Action Links
                                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                            ), array('x__up'), 0) as $this_e){
                                if(!in_array($this_e['e__id'], $es_added) && (!count($setting_links[27984]) || !in_array($this_e['e__id'], $setting_links[27984]))){
                                    array_push($column_sources, $this_e);
                                    array_push($es_added, $this_e['e__id']);
                                }
                                array_push($is_with_action_es, $this_i['i__id']);
                            }
                        }
                    }
                }

                echo '<div>'.count($or_ids).' OR Ideas (Responses vary per user)</div>';
                $count = 0;
                foreach($or_ids as $recursive_down_id){
                    foreach($this->I_model->fetch(array(
                        'i__id' => $recursive_down_id,
                    ), 0, 0, array('i__id' => 'ASC')) as $this_i){
                        $count++;
                        echo '<p>'.$count.') <a href="/~'.$this_i['i__id'].'">'.$this_i['i__title'].'</a></p>';
                        if(!$setting_links[34513] && !in_array($this_i['i__id'], $is_with_action_es) && isset($_GET['all_ideas'])){
                            array_push($column_ideas, $this_i);
                        }
                    }
                }
                echo '</div>';

            }
        }


        echo '<div style="padding: 10px;"><a href="/-26582?i__id='.join(',', $setting_links[40791]).'&include_e='.join(',', $setting_links[27984]).'&exclude_e='.join(',', $setting_links[26600]).'">'.$e___6287[26582]['m__cover'].' '.$e___6287[26582]['m__title'].'</a> | <a href="/-40355?i__id='.join(',', $setting_links[40791]).'&include_e='.join(',', $setting_links[27984]).'&exclude_e='.join(',', $setting_links[26600]).'&custom_grid='.$setting_links[34513].'">'.$e___6287[40355]['m__cover'].' '.$e___6287[40355]['m__title'].'</a></div>';



        if($setting_links[34513]){

            $column_sources = $this->X_model->fetch(array(
                'x__up' => $setting_links[34513], //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ), array('x__down'), 0, 0, array('x__weight' => 'ASC', 'e__title' => 'ASC'));

            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__up' => $setting_links[34513], //ACTIVE
                'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
            ), array('x__right'), 0, 0, array('x__weight' => 'ASC', 'i__title' => 'ASC')) as $link_i){
                array_push($column_ideas, $link_i);
            }

        }


        //Return UI:
        $body_content = '';
        $count_totals = array(
            'e' => array(),
            'i' => array(),
        );
        $unique_users_count = array();
        $count = 0;

        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__left IN (' . join(',', $setting_links[40791]) . ')' => null, //IDEA LINKS
        ), array('x__creator'), 0, 0, array('x__time' => 'DESC')) as $x){

            if(in_array($x['e__id'], $unique_users_count)){
                continue;
            }

            if(count($setting_links[27984])>0 && count($setting_links[27984])!=count($this->X_model->fetch(array(
                    'x__up IN (' . join(',', $setting_links[27984]) . ')' => null, //All of these
                    'x__down' => $x['e__id'],
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )))){
                //Must be included in ALL Sources, since not lets continue:
                continue;
            }
            if(count($setting_links[26600]) && count($this->X_model->fetch(array(
                    'x__up IN (' . join(',', $setting_links[26600]) . ')' => null, //All of these
                    'x__down' => $x['e__id'],
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )))){
                //Must follow NONE of these sources:
                continue;
            }

            if(count($setting_links[40793]) && count($this->X_model->fetch(array(
                    'x__left IN (' .join(',', $setting_links[40793]) . ')' => null, //All of these
                    'x__creator' => $x['e__id'],
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )))){
                //They have discovered at-least one, so skip this:
                continue;
            }


            array_push($unique_users_count, $x['e__id']);
            $body_content .= '<tr class="body_tr">';

            $this_top = $this->I_model->fetch(array(
                'i__id' => $x['x__left'],
            ));

            //Member
            $tree_progress = $this->X_model->tree_progress($x['e__id'], $this_top[0]);
            $perfect_point = str_pad($tree_progress['fixed_completed_percentage'], 3, '0', STR_PAD_LEFT);
            $perfect_point = ( $perfect_point>100 ? 100 : $perfect_point );




            //IDEAS
            $idea_content = '';
            $this_quantity = 1;
            $name = '';
            foreach($column_ideas as $i2){

                $discoveries = $this->X_model->fetch(array(
                    'x__left' => $i2['i__id'],
                    'x__creator' => $x['e__id'],
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array(), 1);

                if(count($discoveries)){

                    $x__metadata = unserialize($discoveries[0]['x__metadata']);
                    if(isset($x__metadata['quantity']) && $x__metadata['quantity'] >= 2){
                        $this_quantity = $x__metadata['quantity'];
                    }

                    if($this_quantity<2){
                        for($t=20;$t>=2;$t--){
                            if(substr_count(strtolower($i2['i__title']),$t.'x')==1){
                                $this_quantity = $t;
                                break;
                            }
                        }
                    }

                    if($i2['i__id']==15736){
                        $name = $discoveries[0]['x__message'];
                    }
                }

                $idea_content .= '<td>'.( count($discoveries) ? ( strlen($discoveries[0]['x__message']) > 0 ? ( isset($_GET['expand']) ? '<p title="'.$i2['i__title'].': '.$discoveries[0]['x__message'].'" data-placement="top" '.$underdot_class.'>'.convertURLs($discoveries[0]['x__message']).'</p>' : '<span title="'.$i2['i__title'].': '.$discoveries[0]['x__message'].' ['.$discoveries[0]['x__time'].']" '.$underdot_class.'>✔️</span>'  ) : '<span title="'.$i2['i__title'].' ['.$discoveries[0]['x__time'].']">✔️</span>' )  : '').'</td>';

                if(count($discoveries)){
                    if(!isset($count_totals['i'][$i2['i__id']])){
                        $count_totals['i'][$i2['i__id']] = 0;
                    }
                    $count_totals['i'][$i2['i__id']]++;
                }

            }

            $this_quantity = $this_quantity-1;






            $plus_info = ' '.( $this_quantity > 0 ? '+'.$this_quantity : '' );

            $body_content .= '<td style="padding-top: 2px;"><span class="icon-block-xxs">'.view_cover($x['e__cover'], true).'</span><a href="/@'.$x['e__id'].'" style="font-weight:bold;">'.$x['e__title'].'</a>'.$name.$plus_info.'</td>';



            //SOURCES
            foreach($column_sources as $e){

                $input_modal = count($this->X_model->fetch(array(
                    'x__up IN (' . join(',', $this->config->item('n___37707')) . ')' => null, //SOURCE LINKS
                    'x__down' => $e['e__id'],
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )));

                $fetch_data = $this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__down' => $x['e__id'],
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__up' => $e['e__id'],
                ));

                $message_clean = ( count($fetch_data) ? ( strlen($fetch_data[0]['x__message']) ? ( isset($_GET['expand']) || $input_modal || in_array($e['e__id'], $this->config->item('n___37694')) ? preview_x__message($fetch_data[0]['x__message'], $fetch_data[0]['x__type']) : '<span '.$underdot_class.' title="'.$fetch_data[0]['x__message'].'">'.view_cover($e['e__cover'], '✔️', ' ').'</span>' ) : '<span class="icon-block-xxs">'.view_cover($e['e__cover'], '✔️', ' ').'</span>' ) : '' );


                $body_content .= '<td class="'.( superpower_active(28714, true) && !in_array($e['e__id'], $this->config->item('n___37695')) ? 'editable x__creator_'.$e['e__id'].'_'.$x['e__id'] : '' ).'" i__id="0" e__id="'.$e['e__id'].'" x__creator="'.$x['e__id'].'" input_modal="'.( $input_modal ? 1 : 0 ).'" x__id="'.$x['x__id'].'"><div class="limit_height">'.$message_clean.'</div></td>';

                if(strlen($message_clean)>0){

                    if(!isset($count_totals['e'][$e['e__id']])){
                        $count_totals['e'][$e['e__id']] = 0;
                    }

                    $count_totals['e'][$e['e__id']] = $count_totals['e'][$e['e__id']] + ( count($this->X_model->fetch(array(
                            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__down' => $e['e__id'],
                            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'x__up IN (' . join(',', $this->config->item('n___39609')) . ')' => null, //ADDUP NUMBER
                        ))) ? doubleval(preg_replace('/[^0-9.-]+/', '', $fetch_data[0]['x__message'])) : 1 );
                }
            }


            $body_content .= $idea_content;



            $e_emails = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__down' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__up' => 3288, //Email
            ));

            $body_content .= '</tr>';
            $count++;

        }


        $table_sortable = array('#th_primary','#th_done');
        echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';

        echo '<tr style="font-weight:bold; vertical-align: baseline;">';
        echo '<th id="th_primary" style="width:200px;">'.$count.' Sources</th>';
        foreach($column_sources as $e){
            array_push($table_sortable, '#th_e_'.$e['e__id']);
            echo '<th id="th_e_'.$e['e__id'].'"><div><span class="icon-block-xxs">'.$e___6177[$e['e__access']]['m__cover'].'</span></div><a class="icon-block-xxs" href="/@'.$e['e__id'].'" target="_blank" title="Open in New Window">'.view_cover($e['e__cover'], '✔️', ' ').'</a><span class="vertical_col"><span class="col_stat">'.( isset($count_totals['e'][$e['e__id']]) ? str_replace('.00','',number_format($count_totals['e'][$e['e__id']], 2)) : '0' ).'</span><i class="fas fa-sort"></i>'.$e['e__title'].'</span></th>';
        }
        foreach($column_ideas as $i2){

            $has_limits = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $i2['i__id'],
                'x__up' => 26189,
            ), array(), 1);
            $current_x = ( isset($count_totals['i'][$i2['i__id']]) ? $count_totals['i'][$i2['i__id']] : 0 );
            $max_limit = (count($has_limits) && is_numeric($has_limits[0]['x__message']) && intval($has_limits[0]['x__message'])>0 ? intval($has_limits[0]['x__message']) : 0 );

            array_push($table_sortable, '#th_i_'.$i2['i__id']);

            echo '<th id="th_i_'.$i2['i__id'].'"><div><span class="icon-block-xxs">'.$e___31004[$i2['i__access']]['m__cover'].'</span></div><a class="icon-block-xxs" href="/~'.$i2['i__id'].'" target="_blank" title="Open in New Window">'.$e___4737[$i2['i__type']]['m__cover'].'</a><span class="vertical_col"><span class="col_stat '.( $max_limit ? ( $current_x>=$max_limit ? 'isgreen'  : ( ($current_x/$max_limit)>=0.5 ? 'isgold' : 'isred' ) ) : '' ).'">'.$current_x.( $max_limit ? '/'.$max_limit : '').'</span><i class="fas fa-sort"></i>'.( strlen($i2['x__message']) ? $i2['x__message'] : $i2['i__title'] ).'</span></th>';

        }
        echo '</tr>';
        echo $body_content;
        echo '</table>';

        ?>

        <style>

            <?php if(!isset($_GET['expand'])){ echo ' #sortable_table td{ max-width: 89px !important; max-height: 89px !important; overflow: scroll; } '; } else { echo ' #sortable_table td{ font-size:1em !important; } '; } ?>

            .container{
                margin-left: 8px;
                max-width: calc(100% - 16px) !important;
            }
            .mini-header,
            #sortable_table td>p{
                display: block;
                max-width: 144px !important;
                max-height: 179px !important;
                overflow: scroll;
            }

            td a {
                text-decoration: underline !important;
            }

            /* CSS Adjustments for Printing View */
            .fixed-top{
                background-color: transparent !important;
            }
            .top_nav{
                display:none !important;
            }
            #sortable_table .table-striped tr:nth-of-type(odd) td {
                background-color: #FFFFFF !important;
                -webkit-print-color-adjust:exact;
            }
            #sortable_table .table-striped td {
                border-bottom: 1px dotted #FFFFFF !important;
                font-size: 1.15em;
            }
            .fa-filter, .fa-sort{
                font-size: 1.01em !important;
                margin-bottom: 3px;
            }
            #sortable_table th{
                cursor: ns-resize !important;
            }
            #sortable_table th, #sortable_table td{
                border: 1px solid #000 !important;
            }

            #sortable_table th:hover, #sortable_table th:active{
                background-color: #FFF;
            }

            #sortable_table .body_tr:hover {
                background-color: #CCC;
            }
            #sortable_table .body_tr .editable:hover {
                background-color: #FFD961;
                cursor: pointer;
            }

            .vertical_col {
                writing-mode: tb-rl;
                white-space: nowrap;
                display: block;
                padding-bottom: 8px;
            }
            .col_stat{
                height:71px;
                display:inline-block;
                text-align: left;
                width: 8px;
            }


        </style>
        <script>

            $(document).ready(function () {

                $('.editable').click(function (e) {

                    var input_modal = parseInt($(this).attr('input_modal'));
                    var modal_value = '';
                    if(input_modal){
                        modal_value = prompt("Enter value:", $('.x__creator_' + $(this).attr('e__id') + '_' + $(this).attr('x__creator')).text());
                    }

                    var modify_data = {
                        i__id: $(this).attr('i__id'),
                        e__id: $(this).attr('e__id'),
                        x__creator: $(this).attr('x__creator'),
                        x__id: $(this).attr('x__id'),
                        input_modal: input_modal,
                        modal_value: modal_value,
                    };

                    $('.x__creator_' + modify_data['e__id'] + '_' + modify_data['x__creator']).html('<i class="far fa-yin-yang fa-spin"></i>');

                    //Check email and validate:
                    $.post("/e/e_toggle_e", modify_data, function (data) {

                        if (data.status) {

                            //Update source id IF existed previously:
                            $('.x__creator_' + modify_data['e__id'] + '_' + modify_data['x__creator']).html(data.message);

                        } else {
                            alert('ERROR:' + data.message);
                        }
                    });

                });

                var table = $('#sortable_table');
                $('<?= join(', ', $table_sortable) ?>')
                    .each(function(){

                        var th = $(this),
                            thIndex = th.index(),
                            inverse = false;

                        th.click(function(){

                            table.find('td').filter(function(){

                                return $(this).index() === thIndex;

                            }).sortElements(function(a, b){

                                return $.text([a]) < $.text([b]) ?
                                    inverse ? -1 : 1
                                    : inverse ? 1 : -1;

                            }, function(){

                                return this.parentNode;

                            });

                            inverse = !inverse;

                        });

                    });
            });
        </script>

        <?php

    } elseif (in_array($i['i__type'], $this->config->item('n___34849'))) {

        //Do we have a text response from before?
        $previous_response = '';
        if($x__creator){
            //Does this have any append sources?
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 7545, //Following Add
                'x__right' => $i['i__id'],
            )) as $append_source){
                //Does the user have this source with any values?
                foreach($this->X_model->fetch(array(
                    'x__up' => $append_source['x__up'],
                    'x__down' => $x__creator,
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                ), array(), 0, 0) as $up_appended) {
                    if(strlen($up_appended['x__message'])){
                        $previous_response = $up_appended['x__message'];
                        break;
                    }
                }
                if(strlen($previous_response)){
                    break;
                }
            }
        }

        $input_attributes = '';
        $previous_response = ( !strlen($previous_response) && count($x_completes) ? trim($x_completes[0]['x__message']) : $previous_response );

        if ($i['i__type']==6683) {

            //Text response
            $message_ui = '<textarea class="border i_content x_input greybg" placeholder="" id="x_write">' . $previous_response . '</textarea>';

        } elseif ($i['i__type']==32603) {

            $message_ui = view_sign($i, $previous_response);

        } else {

            //Determine type:
            if($i['i__type']==31794){

                //Number
                $input_type = 'number';

                //Steps
                foreach($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $i['i__id'],
                    'x__up' => 31813, //Steps
                )) as $num_steps){
                    if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                        $input_attributes .= ' step="'.$num_steps['x__message'].'" ';
                    }
                }

                //Min Value
                foreach($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $i['i__id'],
                    'x__up' => 31800, //Min Value
                )) as $num_steps){
                    if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                        $input_attributes .= ' min="'.$num_steps['x__message'].'" ';
                    }
                }

                //Max Value
                foreach($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $i['i__id'],
                    'x__up' => 31801, //Max Value
                )) as $num_steps){
                    if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                        $input_attributes .= ' max="'.$num_steps['x__message'].'" ';
                    }
                }

            } elseif($i['i__type']==30350){

                //Time, figure out which type:

                if(count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $i['i__id'],
                    'x__up' => 32442, //Select Day
                )))){

                    $input_type = 'date';

                } elseif(count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $i['i__id'],
                    'x__up' => 32446, //Select Week
                )))){

                    $input_type = 'week';

                } elseif(count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $i['i__id'],
                    'x__up' => 32447, //Select Month
                )))){

                    $input_type = 'month';

                } else {

                    //Time selector:
                    $input_type = 'datetime-local';

                }

            } elseif($i['i__type']==31794){

                //Number
                $input_type = 'number';

            } elseif($i['i__type']==31795){

                //URL
                $input_type = 'url';

            }


            $message_ui = '<input type="'.$input_type.'" '.$input_attributes.' class="border i_content greybg x_input" placeholder="" value="'.$previous_response.'" id="x_write" />';

        }

        $message_ui .= '<script> $(document).ready(function () { set_autosize($(\'#x_write\')); $(\'#x_write\').focus(); }); </script>';

        echo view_headline(13980, null, $e___11035[13980], $message_ui, true);

    } elseif ($i['i__type']==7637) {

        //FILE UPLOAD
        echo '<div class="userUploader">';
        echo '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

        echo '<input class="inputfile" type="file" name="file" id="fileType' . $i['i__type'] . '" />';

        if (count($x_completes)) {

            echo '<div class="file_saving_result greybg">';
            echo view_headline(13977, null, $e___11035[13977], $this->X_model->message_view($x_completes[0]['x__message'], true), true);
            echo '</div>';

        } else {

            //for when added:
            echo '<div class="file_saving_result center"></div>';

        }

        //UPLOAD BUTTON:
        echo '<div class="select-btns"><label class="btn btn-6255 inline-block" for="fileType' . $i['i__type'] . '" style="margin-left:5px;">' . $e___11035[13572]['m__cover'] . ' ' . $e___11035[13572]['m__title'] . '</label></div>';


        echo '<div class="doclear">&nbsp;</div>';
        echo '</form>';
        echo '</div>';

    } elseif ($i['i__type']==30874) {

        //Event

    }

}






if(!$top_i__id){

    if(count($this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
        'x__right' => $i['i__id'],
        'x__up' => 4235,
    )))){

        //Get Started
        echo '<div class="nav-controller select-btns msg-frame"><a class="btn btn-lrg btn-6255 go-next" href="javascript:void(0);" onclick="go_next()">'.$e___11035[4235]['m__title'].' '.$e___11035[4235]['m__cover'].'</a></div>';
        echo '<div class="doclear">&nbsp;</div>';

    } else {

        $_GET['open'] = true;

    }

} else {

    $buttons_found = 0;
    $buttons_ui = '';

    foreach($this->config->item('e___13289') as $x__type => $m2) {

        $superpower_actives = array_intersect($this->config->item('n___10957'), $m2['m__following']);
        if(count($superpower_actives) && !superpower_unlocked(end($superpower_actives))){
            continue;
        }

        $control_btn = '';

        if($x__creator && in_array($x__type, $this->config->item('n___12274'))){

            //Sources
            if(is_array($this->config->item('n___'.$x__type))){
                foreach(array_intersect($this->config->item('n___'.$x__type), $this->config->item('n___31127')) as $pending_action_id) {

                    //Is this action already taken?
                    $action_xs = $this->X_model->fetch(array(
                        'x__up' => $x__creator,
                        'x__right' => $i['i__id'],
                        'x__type' => $x__type,
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    ));

                    $control_btn = '<a class="round-btn btn_control_'.$x__type.'" href="javascript:void(0);" onclick="x_link_toggle('.$x__type.', '.$i['i__id'].')" current_x_id="'.( count($action_xs) ? $action_xs[0]['x__id'] : '0' ).'"><span class="controller-nav btn_toggle_'.$x__type.' '.( count($action_xs) ? '' : 'hidden' ).'">'.$m2['m__cover'].'</span><span class="controller-nav btn_toggle_'.$x__type.' '.( count($action_xs) ? 'hidden' : '' ).'">'.$e___31127[$pending_action_id]['m__cover'].'</span></a><span class="nav-title main__title">'.$m2['m__title'].'</span>';

                    break;// Ignore if more than one...
                }
            }


        } elseif($x__type==12273 && !$is_or_idea && count($is_next)){

            //Ideas
            $control_btn = '<a class="controller-nav round-btn" href="javascript:void(0);" onclick="toggle_headline(12211)">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.count($is_next).' '.$m2['m__title'].'</span>';

        } elseif($x__type==12211 && !$top_completed && !($is_single_click && !count($x_completes))){

            //NEXT
            $control_btn = '<div style="padding-left: 8px;" id="next_div"><a class="controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="go_next()">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';

        } elseif($x__type==13495 && count($x_selects)){

            //Edit response:
            $control_btn = '<div style="padding-left: 8px;" class="edit_toggle_answer"><a class="controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="$(\'.edit_toggle_answer\').toggleClass(\'hidden\');">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';
            $control_btn .= '<div style="padding-left: 8px;" class="edit_toggle_answer hidden"><a class="controller-nav round-btn main-next" href="javascript:void(0);" onclick="$(\'.edit_toggle_answer\').toggleClass(\'hidden\');">'.$e___11035[40639]['m__cover'].'</a><span class="nav-title main__title">'.$e___11035[40639]['m__title'].'</span></div>';

        } elseif($x__type==31796 && $can_edit_response && $top_completed && in_array($i['i__type'], $this->config->item('n___34849'))){

            //Update Response
            $control_btn = '<div style="padding-left: 8px;"><a class="controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="go_next()">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';

        } elseif($x__type==28239 && $can_skip && !$top_completed){ // && !count($x_completes)

            //SKIP
            $control_btn = '<div style="padding-left: 13px;" class="edit_toggle_answer"><a class="controller-nav round-btn" href="javascript:void(0);" onclick="go_next()">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';


        }

        $buttons_ui .= ( $control_btn ? '<div>'.$control_btn.'</div>' : '' );

        if($control_btn){
            $buttons_found++;
        }

    }

    if($buttons_found > 0){
        echo '<div class="nav-controller">';
        echo $buttons_ui;
        echo '</div>';
        echo '<div class="doclear">&nbsp;</div>';
    }

}







//NEXT IDEAS:
if(!($is_or_idea && $top_i__id)){
    echo view_i_list(12211, $top_i__id, $i, $is_next, $member_e);
}


if($top_i__id && !$top_completed) {
    echo '<div style="padding: 0 5px;"><div class="progress" style="height: 8px; margin: 0 0 21px; background-color: #CCCCCC;">
<div class="progress-bar bg6255" role="progressbar" data-toggle="tooltip" data-placement="top" title="'.$tree_progress['fixed_discovered'].'/'.$tree_progress['fixed_total'].' Ideas Discovered '.$tree_progress['fixed_completed_percentage'].'%" style="width: '.$tree_progress['fixed_completed_percentage'].'%" aria-valuenow="'.$tree_progress['fixed_completed_percentage'].'" aria-valuemin="0" aria-valuemax="100"></div>
</div></div>';
}



echo '</div>';



?>

<style> .headline_12211, .headline_13980 { display: none !important; } </style>
<script>
    var focus_i__type = <?= $i['i__type'] ?>;
    var can_skip = <?= intval($can_skip) ?>;
</script>

<input type="hidden" id="focus_card" value="12273" />
<input type="hidden" id="focus_id" value="<?= $i['i__id'] ?>" />
<input type="hidden" id="top_i__id" value="<?= $top_i__id ?>" />
<input type="hidden" id="go_next_url" value="<?= $go_next_url ?>" />

<script type="text/javascript">

    var audio_played = false;
    $(document).ready(function () {

        $("audio").on({
            play:function(){ // the audio is playing!
                audio_played = true;
            }
        });

        //Auto next a single answer:
        if(js_n___7712.includes(parseInt($('.list-answers').attr('i__type')))){
            //It is, see if it has only 1 option:
            var single_id = 0;
            var answer_count = 0;
            $(".answer-item").each(function () {
                single_id = parseInt($(this).attr('selection_i__id'));
                answer_count++;
            });
            if(answer_count==1){
                //Only 1 option, select and go next:
                toggle_answer(single_id);
            }
        }

        set_autosize($('#x_write'));

        //Watchout for file uplods:
        $('.boxUpload .inputfile').on('change', function () {
            console.log('file uploading');
            console.log($(this));
            console.log($(this).prop('files'));
            x_upload($(this).prop('files'), 'file');
        });

        //Should we auto start?
        if (isAdvancedUpload) {

            var droppedFiles = false;

            $('.boxUpload').on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
            })
                .on('dragover dragenter', function () {
                    $('.userUploader').addClass('dynamic_saving');
                })
                .on('dragleave dragend drop', function () {
                    $('.userUploader').removeClass('dynamic_saving');
                })
                .on('drop', function (e) {
                    droppedFiles = e.originalEvent.dataTransfer.files;
                    e.preventDefault();
                    x_upload(droppedFiles, 'drop');
                });
        }

    });


    var is_toggling = false;
    function toggle_answer(i__id){

        if(is_toggling){
            return false;
        }
        is_toggling = true;

        //Allow answer to be saved/updated:
        var i__type = parseInt($('.list-answers').attr('i__type'));

        //Clear all if single selection:
        var is_single_selection = (i__type==6684);
        if(is_single_selection){
            //Single Selection, clear all:
            $('.answer-item').removeClass('isSelected');
        }

        //Is setected?
        if($('.x_select_'+i__id).hasClass('isSelected')){

            //Previously Selected, delete selection:
            if(i__type==7231){
                //Multi Selection
                $('.x_select_'+i__id).removeClass('isSelected');
            }

            is_toggling = false;

        } else {

            //Not selected, select now:
            $('.x_select_'+i__id).addClass('isSelected');

            if(is_single_selection){
                //Auto submit answer:
                go_next();
            } else {
                //Flash call to action:
                $(".main-next").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                is_toggling = false;
            }
        }

    }



    function go_next(){

        var go_next_url = $('#go_next_url').val();
        var is_logged_in = (js_pl_id > 0);
        var require_playback = <?= ( $require_playback ? 1 : 0 ) ?>;

        if(require_playback && !audio_played){
            alert('Please listen to the audio message before going next.');
            return false;
        }

        //Attempts to go next if no submissions:
        if (is_logged_in && js_n___7712.includes(focus_i__type) && $('.list-answers .answer-item').length){

            //SELECT ONE/SOME
            return x_select(go_next_url);

        } else if(is_logged_in && js_n___34849.includes(focus_i__type)) {

            if(focus_i__type==32603 && !$("#DigitalSignAgreement").is(':checked')){
                if(can_skip){
                    x_skip(go_next_url);
                } else {
                    //Must upload file first:
                    alert('Please agree to our terms of service before going next.');
                }
            } else {
                //SUBMIT TEXT RESPONSE:
                return x_write(go_next_url);
            }

        } else if (is_logged_in && focus_i__type==7637 && !$('.file_saving_result').html().length ) {

            if(!can_skip){
                //Must upload file first:
                alert('Please upload a file before going next.');
            } else {
                x_skip(go_next_url);
            }

        } else if (is_logged_in && focus_i__type==26560 ) {

            //Must upload file first:
            return x_free_ticket(go_next_url);

        } else if(go_next_url && go_next_url.length > 0) {

            if (is_logged_in && focus_i__type==6677 && parseInt($('#top_i__id').val()) > 0) {

                //READ:
                return x_read(go_next_url);

            } else {

                //Go Next:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin"></i>');
                js_redirect(go_next_url);

            }

        }
    }

    function x_upload(droppedFiles, uploadType) {

        //Prevent multiple concurrent uploads:
        if ($('.boxUpload').hasClass('dynamic_saving')) {
            return false;
        }

        $('.file_saving_result').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="main__title">UPLOADING...</span>');

        if (isAdvancedUpload) {

            var ajaxData = new FormData($('.boxUpload').get(0));
            if (droppedFiles) {
                $.each(droppedFiles, function (i, file) {
                    var thename = $('.boxUpload').find('input[type="file"]').attr('name');
                    if (typeof thename==typeof undefined || thename==false) {
                        var thename = 'drop';
                    }
                    ajaxData.append(uploadType, file);
                });
            }

            ajaxData.append('upload_type', uploadType);
            ajaxData.append('i__id', fetch_int_val('#focus_id'));
            ajaxData.append('top_i__id', $('#top_i__id').val());

            $.ajax({
                url: '/x/x_upload',
                type: $('.boxUpload').attr('method'),
                data: ajaxData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                complete: function () {
                    $('.boxUpload').removeClass('dynamic_saving');
                },
                success: function (data) {
                    //Render new file:
                    $('.file_saving_result').html(data.message);
                    $('.go_next_upload').removeClass('hidden');

                },
                error: function (data) {
                    //Show Error:
                    $('.file_saving_result').html(data.responseText);
                }
            });
        } else {
            // ajax for legacy browsers
        }

    }


    function x_write(go_next_url){
        $.post("/x/x_write", {
            top_i__id:$('#top_i__id').val(),
            i__id:fetch_int_val('#focus_id'),
            x_write:$('#x_write').val(),
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin zq6255"></i>');
                js_redirect(go_next_url);
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }




    function x_read(go_next_url){
        $.post("/x/x_read", {
            top_i__id:$('#top_i__id').val(),
            i__id:fetch_int_val('#focus_id'),
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin zq6255"></i>');
                js_redirect(go_next_url);
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }


    function x_skip(go_next_url){
        $.post("/x/x_skip", {
            top_i__id:$('#top_i__id').val(),
            i__id:fetch_int_val('#focus_id'),
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin zq6255"></i>');
                js_redirect(go_next_url);
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }


    function x_free_ticket(go_next_url){
        $.post("/x/x_free_ticket", {
            top_i__id:$('#top_i__id').val(),
            i__id:fetch_int_val('#focus_id'),
            paypal_quantity:$('#paypal_quantity').val(),
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin zq6255"></i>');
                js_redirect(go_next_url);
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }

    function x_select(go_next_url){

        //Check
        var selection_i__id = [];
        $(".answer-item").each(function () {
            var selection_i__id_this = parseInt($(this).attr('selection_i__id'));
            if ($('.x_select_'+selection_i__id_this).hasClass('isSelected')) {
                selection_i__id.push(selection_i__id_this);
            }
        });


        //Show Loading:
        $.post("/x/x_select", {
            focus_id:fetch_int_val('#focus_id'),
            top_i__id:$('#top_i__id').val(),
            selection_i__id:selection_i__id,
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin zq6255"></i>');
                js_redirect(go_next_url);
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }


</script>

<?php
}
?>