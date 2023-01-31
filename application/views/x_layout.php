<?php

if(!in_array($i['i__privacy'], $this->config->item('n___31870')) && !e_of_i($i['i__id'])){

    echo '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> This idea is not public at this time.</div>';

} else {

$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$e___31127 = $this->config->item('e___31127'); //Action Buttons Pending
$e___4737 = $this->config->item('e___4737'); //Idea Types
$is_or_idea = in_array($i['i__type'], $this->config->item('n___7712'));

//Any Hard Redirects?
foreach($this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
    'x__right' => $i['i__id'],
    'x__up' => 30811, //Hard Redirect
)) as $redirect){
    if(filter_var($redirect['x__message'], FILTER_VALIDATE_URL)){
        //js_php_redirect($redirect['x__message'], 13);
        break;
    }
}

//NEXT IDEAS
$is_next = $this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'x__left' => $i['i__id'],
), array('x__right'), 0, 0, array('x__weight' => 'ASC'));

//Filter Next Ideas:
$first_down = array();
foreach($is_next as $in_key => $in_value){
    if(!$first_down && in_array($in_value['i__type'], $this->config->item('n___12330'))){
        $first_down = $in_value;
    }
    $i_is_available = i_is_available($in_value['i__id'], false);
    if(!$i_is_available['status']){
        //Remove this option:
        unset($is_next[$in_key]);
    }
}



$i['i__title'] = str_replace('"','',$i['i__title']);
$x__creator = ( $member_e ? $member_e['e__id'] : 0 );
$one_down_hack = (count($first_down) && count($is_next)==1 && !$top_i__id);
$x_completes = ( $top_i__id ? $this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
    'x__creator' => $x__creator,
    'x__left' => $i['i__id'],
)) : array() );
$in_my_discoveries = ( $top_i__id && $top_i__id==$i['i__id'] );
$top_completed = false; //Assume main intent not yet completed, unless proven otherwise...
$can_skip = count($this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
    'x__right' => $i['i__id'],
    'x__up' => 28239, //Can Skip
)));




$is_payment = in_array($i['i__type'] , $this->config->item('n___30469'));
$min_allowed = 1;
$detected_x_type = 0;

if($is_payment){

    //Payments Must have Unit Price, otherwise they are NOT a payment until added...
    $total_dues = $this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
        'x__right' => $i['i__id'],
        'x__up' => 26562, //Total Due
    ));

    if($x__creator>0 && count($total_dues)){
        $detected_x_type = x_detect_type($total_dues[0]['x__message']);
        if ($detected_x_type['status'] && $detected_x_type['x__type']==26661){

            $digest_fees = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $i['i__id'],
                'x__up' => 30589, //Digest Fees
            ));
            $cart_max = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $i['i__id'],
                'x__up' => 29651, //Cart Max Quantity
            ));
            $cart_min = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $i['i__id'],
                'x__up' => 31008, //Cart Min Quantity
            ));


            //Break down amount & currency
            $currency_parts = explode(' ',$total_dues[0]['x__message'],2);
            $unit_price = number_format($currency_parts[1], 2);
            $unit_fee = number_format($currency_parts[1] * ( count($digest_fees) ? 0 : (doubleval(website_setting(30590, $x__creator)) + doubleval(website_setting(27017, $x__creator)) + doubleval(website_setting(30612, $x__creator)))/100 ), 2);
            $max_allowed = ( count($cart_max) && is_numeric($cart_max[0]['x__message']) && $cart_max[0]['x__message']>1 ? intval($cart_max[0]['x__message']) : view_memory(6404,29651) );
            $spots_remaining = i_spots_remaining($i['i__id']);
            $max_allowed = ( $spots_remaining>-1 && $spots_remaining<$max_allowed ? $spots_remaining : $max_allowed );
            $max_allowed = ( $max_allowed < 1 ? 1 : $max_allowed );
            $min_allowed = ( count($cart_min) && is_numeric($cart_min[0]['x__message']) && intval($cart_min[0]['x__message'])>0 ? intval($cart_min[0]['x__message']) : $min_allowed );
            $min_allowed = ( $min_allowed < 1 ? 1 : $min_allowed );
            $unit_total = number_format($unit_fee+$currency_parts[1], 2);

        } else {
            $is_payment = false;
        }
    } else {
        $is_payment = false;
    }
}


//Featured Sources:
$relevant_sources = '';
foreach($this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
    'x__right' => $i['i__id'],
    'x__up !=' => website_setting(0),
), array('x__up'), 0, 0, array('e__title' => 'DESC')) as $x){
    $relevant_sources .= view_featured_source($x__creator, $x);
}


//Idea Setting Source Types:
foreach($this->E_model->scissor_e(31826,$i['i__type']) as $e_item) {
    $relevant_sources .= view_featured_source($x__creator, $e_item);
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
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'x__left' => $followings_i['i__id'],
            ), array('x__right'), 0, 0, array('x__weight' => 'ASC'));
            foreach($query_subset as $key=>$value){
                $i_is_available = i_is_available($value['i__id'], false);
                if(!$i_is_available['status'] || !count($this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'x__creator' => $x__creator,
                        'x__left' => $value['i__id'],
                    )))){
                    unset($query_subset[$key]);
                }
            }

            $messages = count($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 4231, //IDEA NOTES Messages
                'x__right' => $followings_i['i__id'],
            )));

            if(!$messages && count($query_subset)==1 && $level==1){
                //Top referral, hide:
                continue;
            }

            $breadcrum_content .= '<li class="breadcrumb-item">';
            $breadcrum_content .= '<a href="/'.$top_i__id.'/'.$followings_i['i__id'].'"><u>'.$followings_i['i__title'].'</u></a>';

            //Do we have more sub-items in this branch? Must have more than 1 to show, otherwise the 1 will be included in the main branch:
            if(count($query_subset) >= 2){
                //Show other branches:
                $breadcrum_content .= '<div class="dropdown inline-block">';
                $breadcrum_content .= '<button type="button" class="btn no-side-padding" id="dropdownMenuButton'.$followings_i['i__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $breadcrum_content .= '<span style="padding-left:5px;"><i class="far fa-chevron-square-down"></i></span>';
                $breadcrum_content .= '</button>';
                $breadcrum_content .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$followings_i['i__id'].'">';
                foreach ($query_subset as $i_subset) {
                    $breadcrum_content .= '<a href="/'.$top_i__id.'/'.$i_subset['i__id'].'" class="dropdown-item css__title '.( in_array($i_subset['i__id'], $main_branch) ? ' active ' : '' ).'">'.$i_subset['i__title'].'</a>';
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
    $go_next_url = ( $top_completed ? '/x/x_completed_next/' : '/x/x_next/' ) . $top_i__id . '/' . $i['i__id'];

    if($tree_progress['fixed_completed_percentage']>0){
        echo '<div style="padding: 0 5px;"><div class="progress" style="height: 8px; margin: 0 0 21px; background-color: #CCCCCC;">
<div class="progress-bar bg6255" role="progressbar" data-toggle="tooltip" data-placement="top" title="'.$tree_progress['fixed_discovered'].' of '.$tree_progress['fixed_total'].' Ideas Discovered, '.$tree_progress['fixed_completed_percentage'].'% Completed" style="width: '.$tree_progress['fixed_completed_percentage'].'%" aria-valuenow="'.$tree_progress['fixed_completed_percentage'].'" aria-valuemin="0" aria-valuemax="100"></div>
</div></div>';
    }

} else {

    $go_next_url = '/x/x_start/'.$i['i__id'];

}

if($top_completed || $is_or_idea){
    $_GET['open'] = true;
}



echo '<div class="light-bg large-frame">';

//Show Progress:
if($top_completed){
    echo '<div class="msg alert alert-success" role="alert"><span class="icon-block">✅</span>100% Completed: You Are Now Reviewing Your Responses</div>';
}

//MESSAGES
$messages_string = false;
foreach($this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 4231, //IDEA NOTES Messages
    'x__right' => $i['i__id'],
), array(), 0, 0, array('x__weight' => 'ASC')) as $message_x) {
    $messages_string .= $this->X_model->message_view(
        $message_x['x__message'],
        true,
        $member_e
    );
}








//$one_down_hack Get the message for the single follower, if any:
if($one_down_hack){
    echo '<h3 class="msg-frame" style="text-align: left; padding: 13px 0 0 !important;">'.$i['i__title'].'</h3>';
    $messages_string .= '<h1 class="msg-frame" style="text-align: left; padding: 13px 0 !important; font-size:2.5em;">'.$first_down['i__title'].'</h1>';
    foreach($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $first_down['i__id'],
    ), array(), 0, 0, array('x__weight' => 'ASC')) as $message_x) {
        $messages_string .= $this->X_model->message_view(
            $message_x['x__message'],
            true,
            $member_e
        );
    }
} else {
    echo '<h1 class="msg-frame" style="text-align: left; padding: 10px 0 !important; font-size:2.5em;">'.$i['i__title'].'</h1>';
}


if($messages_string){
    echo $messages_string;
} elseif(!count($x_completes) && in_array($i['i__type'], $this->config->item('n___12330')) && $top_i__id && $member_e) {
    //Auto complete:
    echo '<script> $(document).ready(function () { go_next() }); </script>';
}


if(strlen($relevant_sources)){
    echo '<div class="source-featured">';
    echo $relevant_sources;
    echo '</div>';
}


if($top_i__id) {

    if ($is_or_idea) {

        //Has no followers:
        if (!count($is_next)) {

            //Mark this as complete since there is no follower to choose from:
            if (!count($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__creator' => $x__creator,
                'x__left' => $i['i__id'],
            )))) {

                array_push($x_completes, $this->X_model->mark_complete($top_i__id, $i, array(
                    'x__type' => 4559, //READ STATEMENT
                    'x__creator' => $x__creator,
                )));

            }

        } else {

            //First fetch answers based on correct order:
            $x_selects = array();
            foreach ($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'x__left' => $i['i__id'],
            ), array('x__right'), 0, 0, array('x__weight' => 'ASC')) as $x) {
                //See if this answer was selected:
                if (count($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //DISCOVERY IDEA LINK
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

                //Edit response:
                echo '<div class="select-btns"><a class="btn btn-6255" href="javascript:void(0);" onclick="$(\'.edit_toggle_answer\').toggleClass(\'hidden\');">' . $e___11035[13495]['m__cover'] . ' ' . $e___11035[13495]['m__title'] . '</a></div>';

                echo '</div>';

            }



            //Open for list to be printed:
            $select_answer = '<div class="row list-answers" i__type="' . $i['i__type'] . '">';

            //List followers to choose from:
            foreach ($is_next as $key => $next_i) {

                //Has this been previously selected?
                $previously_selected = count($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //DISCOVERY EXPANSIONS
                    'x__left' => $i['i__id'],
                    'x__right' => $next_i['i__id'],
                    'x__creator' => $x__creator,
                )));

                $select_answer .= view_card_x_select($next_i, $x__creator, $previously_selected);

            }


            $select_answer .= '</div>';


            if (count($x_selects) > 0) {

                //Cancel Saving...
                $select_answer .= '<div class="select-btns"><a class="btn btn-6255" href="javascript:void(0);" onclick="$(\'.edit_toggle_answer\').toggleClass(\'hidden\');">' . $e___11035[13502]['m__cover'] . ' ' . $e___11035[13502]['m__title'] . '</a></div>'; //&nbsp;&nbsp;<a class="btn btn-6255" href="javascript:void(0);" onclick="x_select(\'/x/x_next/' . $top_i__id . '/' . $i['i__id'] . '\')">' . $e___11035[13524]['m__title'] . ' ' . $e___11035[13524]['m__cover'] . '</a>

            }

            //HTML:
            echo '<div class="edit_toggle_answer ' . (count($x_selects) > 0 ? 'hidden' : '') . '">';
            echo view_headline($i['i__type'], null, $e___4737[$i['i__type']], $select_answer, true);
            echo '</div>';

        }

    } elseif ($is_payment) {

        if(isset($_GET['cancel_pay']) && !count($x_completes)){
            echo '<div class="msg alert alert-danger" role="alert">You cancelled your payment.</div>';
        }

        if(isset($_GET['process_pay']) && !count($x_completes)){

            echo '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Processing your payment, please wait...</div>';

            //Referesh soon so we can check if completed or not
            js_php_redirect('/'.$top_i__id.'/'.$i['i__id'].'?process_pay=1', 2584);

        } elseif(count($x_completes)){

            foreach($x_completes as $x_complete){
                $x__metadata = unserialize($x_complete['x__metadata']);
                echo '<div class="msg alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>'.( $x__metadata['mc_gross']>0 ? 'Received your payment of ' : 'Refunded you a total of ' ).$x__metadata['mc_currency'].' '.$x__metadata['mc_gross'].( isset($x__metadata['quantity']) && $x__metadata['quantity']>1 ? ' for '.$x__metadata['quantity'].' tickets' : '' ).'</div>';
            }

            //Invite Your Friends (If 2 or more items):
            if($x__metadata['quantity']>1 && 0){

                //TODO Complete

                echo '<h2>Invite Your Friends</h2>';
                echo '<p>So they can get inside independantly. If not invited, they must check-in with you.</p>';
                echo '<input type="hidden" id="paypal_quantity" value="'.$x__metadata['quantity'].'" />';

                for($f=2;$f<=$x__metadata['quantity'];$f++) {
                    echo '<div class="row">';
                    echo '<div class="col-6 col-md-4 col-lg-3">Ticket #'.$f.' Name:</div>';
                    echo '<div class="col-6 col-md-4 col-lg-3"><input type="text" id="invite_name_'.$f.'" placeholder="Full Name" class="form-control white-border border maxout" /></div>';
                    echo '</div>';
                    echo '<div class="row">';
                    echo '<div class="col-6 col-md-4 col-lg-3"><input type="email" id="invite_email_'.$f.'" placeholder="Email" class="form-control white-border border maxout" /></div>';
                    echo '<div class="col-6 col-md-4 col-lg-3"><input type="number" id="invite_phone_'.$f.'" placeholder="Cell Phone" class="form-control white-border border maxout" /></div>';
                    echo '</div>';
                    echo '<br /><br />';
                }

                echo '<h3>Custom Message</h3>';
                echo '<textarea class="border i_content  x_input" placeholder="" id="invite_message"></textarea>';
                echo '<script> $(document).ready(function () { set_autosize($(\'#invite_message\')); }); </script>';

            }

        } else {

            //Is multi selectable, allow show down for quantity:

            echo '<div class="source-info ticket-notice">'
                . '<span class="icon-block">'. $e___11035[31837]['m__cover'] . '</span>'
                . '<span>'.$e___11035[31837]['m__title'] . '</span>'
                . '<div class="payment_box">'
                . ( strlen($e___11035[31837]['m__message']) ? '<div class="sub_note css__title">'.nl2br($e___11035[31837]['m__message']).'</div>' : '' );



            if($max_allowed > 1 || $min_allowed > 1){
                echo '<div>';
                echo '<a href="javascript:void(0);" onclick="sale_increment(-1)" class="adjust_counter"><i class="fas fa-minus-circle"></i></a>';
                echo '<span id="current_sales" class="css__title" style="display: inline-block; min-width:34px; text-align: center;">'.$min_allowed.'</span>';
                echo '<a href="javascript:void(0);" onclick="sale_increment(1)" class="adjust_counter"><i class="fas fa-plus-circle"></i></a>';
                echo '</div>';
            }

            echo '<div  style="padding: 8px 0 21px;" '.( $unit_fee > 0 ? ' title="Base Price of '.$unit_price.' '.$currency_parts[0].' + '.$unit_fee.' '.$currency_parts[0].' in Fees & Taxes" data-toggle="tooltip" data-placement="top" ' : '' ).'><span class="total_ui css__title">'.($unit_total*$min_allowed).'</span> '.$currency_parts[0].'</div>';

            echo '<div class="sub_note css__title">';
            if(!count($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $i['i__id'],
                'x__up' => 30615, //Is Refundable
            )))){
                echo 'Final sale: no refunds. ';
            }

            echo 'You Do Not need to create a Paypal account: You can pay by only entering your credit card details & checkout as a guest. Once paid, click "<span style="color: #990000;">Return to Merchant</span>" to continue back here. By paying you agree to our <a href="/-14373" target="_blank"><u>Terms of Use</u></a>.';

            echo '</div>';

            echo '</div>';
            echo '</div>';

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
                    var unit_total = <?= $unit_total; ?>;
                    var unit_fee = <?= $unit_fee; ?>;
                    var handling_total = ( unit_fee * new_quantity );
                    var new_total = ( unit_total * new_quantity );

                    //Update UI:
                    $("#paypal_quantity").val(new_quantity);
                    $("#current_sales").text(new_quantity);
                    $(".total_ui").text(new_total.toFixed(2));
                    $("#paypal_handling").val(handling_total);

                    busy_processing = false;

                }
            </script>

            <?php


        }

    } elseif (in_array($i['i__type'], $this->config->item('n___31796'))) {

        //Do we have a text response?
        $previous_response = '';
        if($x__creator){
            //Does this have any append sources?
            foreach($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 7545, //Following Add
                'x__right' => $i['i__id'],
            )) as $append_source){
                //Does the user have this source with any values?
                foreach($this->X_model->fetch(array(
                    'x__up' => $append_source['x__up'],
                    'x__down' => $x__creator,
                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
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

        $previous_response = ( !strlen($previous_response) && count($x_completes) ? trim($x_completes[0]['x__message']) : $previous_response );
        if (in_array($i['i__type'], $this->config->item('n___31812'))) {
            //Open text response
            $message_ui = '<textarea class="border i_content  x_input" placeholder="" id="x_reply">' . $previous_response . '</textarea>';
        } else {
            //Determine type:
            if($i['i__type']==31794){

                //Number
                $input_type = 'number';

                //Steps
                foreach($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                    'x__right' => $i['i__id'],
                    'x__up' => 31813, //Steps
                )) as $num_steps){
                    if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                        $input_attributes .= ' step="'.$num_steps['x__message'].'" ';
                    }
                }

                //Min Value
                foreach($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                    'x__right' => $i['i__id'],
                    'x__up' => 31800, //Min Value
                )) as $num_steps){
                    if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                        $input_attributes .= ' min="'.$num_steps['x__message'].'" ';
                    }
                }

                //Max Value
                foreach($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                    'x__right' => $i['i__id'],
                    'x__up' => 31801, //Max Value
                )) as $num_steps){
                    if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                        $input_attributes .= ' max="'.$num_steps['x__message'].'" ';
                    }
                }

            } elseif($i['i__type']==31794){

                //Date
                $input_type = 'number';


            } elseif($i['i__type']==31794){

                //Date & Time
                $input_type = 'number';

            }


            $message_ui = '<input type="'.$input_type.'" '.$input_attributes.' class="border i_content  x_input" placeholder="" value="'.$previous_response.'" id="x_reply" />';

        }
        $message_ui .= '<script> $(document).ready(function () { set_autosize($(\'#x_reply\')); $(\'#x_reply\').focus(); }); </script>';

        echo view_headline(13980, null, $e___11035[13980], $message_ui, true);

    } elseif ($i['i__type'] == 7637) {

        //FILE UPLOAD
        echo '<div class="userUploader">';
        echo '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

        echo '<input class="inputfile" type="file" name="file" id="fileType' . $i['i__type'] . '" />';

        if (count($x_completes)) {

            echo '<div class="file_saving_result">';
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

    } elseif ($i['i__type']==30350) {

        //Set Date

    } elseif ($i['i__type']==30874) {

        //Event

    } else {

        //echo '<div class="msg alert alert-danger" role="alert">Error: Missing core variables.</div>';

    }

}






if(!$top_i__id){

    //Get Started
    echo '<div class="nav-controller select-btns msg-frame"><a class="btn btn-lrg btn-6255 go-next" href="javascript:void(0);" onclick="go_next()">'.$e___11035[31793]['m__title'].' '.$e___11035[31793]['m__cover'].'</a></div>';
    echo '<div class="doclear">&nbsp;</div>';

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
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    ));

                    $control_btn = '<a class="round-btn btn_control_'.$x__type.'" href="javascript:void(0);" onclick="x_link_toggle('.$x__type.', '.$i['i__id'].')" current_x_id="'.( count($action_xs) ? $action_xs[0]['x__id'] : '0' ).'"><span class="controller-nav btn_toggle_'.$x__type.' '.( count($action_xs) ? '' : 'hidden' ).'">'.$m2['m__cover'].'</span><span class="controller-nav btn_toggle_'.$x__type.' '.( count($action_xs) ? 'hidden' : '' ).'">'.$e___31127[$pending_action_id]['m__cover'].'</span></a><span class="nav-title css__title">'.$m2['m__title'].'</span>';

                    break;// Ignore if more than one...
                }
            }


        } elseif($x__type==12273 && !$is_or_idea && count($is_next) && !$is_payment){

            //Ideas
            $control_btn = '<a class="controller-nav round-btn" href="javascript:void(0);" onclick="toggle_headline(12211)">'.$m2['m__cover'].'</a>'; //<span class="nav-counter css__title"></span>
            $control_btn .= '<span class="nav-title css__title">'.count($is_next).' '.$m2['m__title'].'</span>';

        } elseif($x__type==12211){

            $control_btn = null;

            $paypal_email =  website_setting(30882);
            if($is_payment && !count($x_completes) && filter_var($paypal_email, FILTER_VALIDATE_EMAIL)){

                $control_btn = '';

                //Load Paypal Pay button:
                $control_btn .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_form" target="_top">';

                $control_btn .= '<input type="hidden" id="paypal_handling" name="handling" value="'.$unit_fee.'">';
                $control_btn .= '<input type="hidden" id="paypal_quantity" name="quantity" value="'.$min_allowed.'">'; //Dynamic Variable
                $control_btn .= '<input type="hidden" id="paypal_item_name" name="item_name" value="'.$i['i__title'].'">';
                $control_btn .= '<input type="hidden" id="paypal_item_number" name="item_number" value="'.$top_i__id.'-'.$i['i__id'].'-'.$detected_x_type['x__type'].'-'.$x__creator.'">';

                $control_btn .= '<input type="hidden" name="amount" value="'.$unit_price.'">';
                $control_btn .= '<input type="hidden" name="currency_code" value="'.$currency_parts[0].'">';
                $control_btn .= '<input type="hidden" name="no_shipping" value="1">';
                $control_btn .= '<input type="hidden" name="notify_url" value="https://mench.com/-26595">';
                $control_btn .= '<input type="hidden" name="cancel_return" value="https://'.get_domain('m__message').'/'.$top_i__id.'/'.$i['i__id'].'?cancel_pay=1">';
                $control_btn .= '<input type="hidden" name="return" value="https://'.get_domain('m__message').'/'.$top_i__id.'/'.$i['i__id'].'?process_pay=1">';
                $control_btn .= '<input type="hidden" name="cmd" value="_xclick">';
                $control_btn .= '<input type="hidden" name="business" value="'.$paypal_email.'">';

                $control_btn .= '<input type="submit" class="round-btn adj-btn" name="pay_now" id="pay_now" value="$" onclick="$(\'.process-btn\').html(\'Loading...\');$(\'#pay_now\').val(\'...\');" style="padding-top: 0 !important;">';
                $control_btn .= '<span class="nav-title css__title process-btn">Pay Now</span>';

                $control_btn .= '</form>';

            } else {

                //NEXT
                $control_btn = '<div style="padding-left: 8px;"><a class="controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="go_next()">'.$m2['m__cover'].'</a>';
                $control_btn .= '<span class="nav-title css__title">'.$m2['m__title'].'<div class="extra_progress hideIfEmpty"></div></span></div>';

            }

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
if(!$is_or_idea){
    echo view_i_list(12211, $top_i__id, $i, $is_next, $member_e);
}


echo '</div>';



?>

<style> .headline_12211, .headline_13980 { display: none !important; } </style>
<script>
    var focus_i__type = <?= $i['i__type'] ?>;
    var can_skip = <?= intval($can_skip) ?>;
</script>

<input type="hidden" id="focus_cover" value="12273" />
<input type="hidden" id="focus_id" value="<?= $i['i__id'] ?>" />
<input type="hidden" id="top_i__id" value="<?= $top_i__id ?>" />
<input type="hidden" id="go_next_url" value="<?= $go_next_url ?>" />

<script type="text/javascript">

    $(document).ready(function () {

        //Make progress more visible if possible:
        var top_id = parseInt($('#top_i__id').val());
        var top_progress = parseInt($('.list_26000 div:first-child .progress-done').attr('prograte'));
        if(top_id>0 && top_progress>0){
            //Display this progress:
            $('.extra_progress').text(top_progress+'% Complete');
        }

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

        set_autosize($('#x_reply'));

        //Watchout for file uplods:
        $('.boxUpload').find('input[type="file"]').change(function () {
            x_upload(droppedFiles, 'file');
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
        var is_single_selection = (i__type == 6684);
        if(is_single_selection){
            //Single Selection, clear all:
            $('.answer-item').removeClass('isSelected');
        }

        //Is setected?
        if($('.x_select_'+i__id).hasClass('isSelected')){

            //Previously Selected, delete selection:
            if(i__type == 7231){
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

        //Attempts to go next if no submissions:
        if(is_logged_in && js_n___31796.includes(focus_i__type)) {

            //TEXT RESPONSE:
            return x_reply(go_next_url);

        } else if (is_logged_in && js_n___7712.includes(focus_i__type) && $('.list-answers .answer-item').length){

            //SELECT ONE/SOME
            return x_select(go_next_url);

        } else if (is_logged_in && focus_i__type==7637 && (!can_skip && !($('.file_saving_result').html().length)) ) {

            //Must upload file first:
            alert('You must upload file before going next.');

        } else if(go_next_url && go_next_url.length > 0){

            //Go Next:
            $('.go-next').html(( is_logged_in ? '<i class="far fa-yin-yang fa-spin zq6255"></i>' : '<i class="far fa-yin-yang fa-spin"></i>' ));
            js_redirect(go_next_url);

        }
    }

    function x_upload(droppedFiles, uploadType) {

        //Prevent multiple concurrent uploads:
        if ($('.boxUpload').hasClass('dynamic_saving')) {
            return false;
        }

        $('.file_saving_result').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="css__title">UPLOADING...</span>');

        if (isAdvancedUpload) {

            var ajaxData = new FormData($('.boxUpload').get(0));
            if (droppedFiles) {
                $.each(droppedFiles, function (i, file) {
                    var thename = $('.boxUpload').find('input[type="file"]').attr('name');
                    if (typeof thename == typeof undefined || thename == false) {
                        var thename = 'drop';
                    }
                    ajaxData.append(uploadType, file);
                });
            }

            ajaxData.append('upload_type', uploadType);
            ajaxData.append('i__id', fetch_val('#focus_id'));
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


    function x_reply(go_next_url){
        $.post("/x/x_reply", {
            top_i__id:$('#top_i__id').val(),
            i__id:fetch_val('#focus_id'),
            x_reply:$('#x_reply').val(),
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
            focus_id:fetch_val('#focus_id'),
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