<?php

if(!in_array($focus_i['i__access'], $this->config->item('n___31871')) && !write_access_i($focus_i['i__hashtag'])){

    echo '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> This is not active.</div>';

} else {

$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$e___31127 = $this->config->item('e___31127'); //Action Buttons Pending
$e___4737 = $this->config->item('e___4737'); //Idea Types
$is_or_7712 = in_array($focus_i['i__type'], $this->config->item('n___7712'));
$is_single_click = in_array($focus_i['i__type'], $this->config->item('n___40680'));

//NEXT IDEAS
$is_next = $this->X_model->fetch(array(
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'x__left' => $focus_i['i__id'],
), array('x__right'), 0, 0, array('x__weight' => 'ASC'));

//Filter Next Ideas:
foreach($is_next as $in_key => $in_value){
    $i_is_available = i_is_available($in_value['i__id'], false);
    if(!$i_is_available['status']){
        //Remove this option:
        unset($is_next[$in_key]);
    }
}



$focus_i['i__message'] = str_replace('"','',$focus_i['i__message']);
$x__creator = ( $member_e ? $member_e['e__id'] : 0 );
$top_i__id = ( count($top_i) ? $top_i[0]['i__id'] : 0 );
$top_i__hashtag = ( count($top_i) ? $top_i[0]['i__hashtag'] : 0 );
$top_completed = false; //Assume main intent not yet completed, unless proven otherwise...
$can_skip = in_array($focus_i['i__type'], $this->config->item('n___42211')) || count($this->X_model->fetch(array(
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
    'x__right' => $focus_i['i__id'],
    'x__up' => 28239, //Can Skip
)));





if(isset($_GET['go1'])){

    //Sync Ideas & Sources
    $stats = array(
        'active_ideas' => 0,
        'old_links_removed' => 0,
        'old_links_kept' => 0,
        'new_links_added' => 0,
        'missing_creation' => 0,
    );
    foreach($this->I_model->fetch(array(
        'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    ), 1) as $i_fix){

        $stats['active_ideas']++;

        $view_sync_links = view_sync_links($i_fix['i__message'], true, $i_fix['i__id']);

        $stats['old_links_removed'] += $view_sync_links['sync_stats']['old_links_removed'];
        $stats['old_links_kept'] += $view_sync_links['sync_stats']['old_links_kept'];
        $stats['new_links_added'] += $view_sync_links['sync_stats']['new_links_added'];

        if(!count($this->X_model->fetch(array(
            'x__right' => $i_fix['i__id'],
            'x__type' => 4250, //New Idea Created
        )))){
            echo '<div> Idea #'.$i_fix['i__id'].' Missing creation link.</div>';
            $stats['missing_creation']++;
            if(0){
                $this->X_model->create(array(
                    'x__creator' => $x__creator,
                    'x__right' => $i_fix['i__id'],
                    'x__message' => $i_fix['i__message'],
                    'x__type' => 4250, //New Idea Created
                ));
            }
        }

    }
}

if(isset($_GET['go2'])) {

    $updated = 0;
    foreach ($this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
        'x__right' => $focus_i['i__id'],
        'x__up' => 26562, //Total Due
    )) as $ticket) {

        $parts = explode(' ', $ticket['x__message'], 2);
        $currency_id = ($parts[0] == 'CAD' ? 112233 : ($parts[0] == 'USD' ? 112233 : 0));
        if ($currency_id > 0 && doubleval($parts[1]) > 0) {

            $updated++;

            //All good, update:
            $this->X_model->update($ticket['x__id'], array(
                'x__message' => doubleval($parts[1]),
            ));

            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__type' => 4983, //IDEA SOURCES
                'x__up' => $currency_id,
                'x__right' => $focus_i['i__id'],
            ));

        } else {
            echo 'ERROR for x_id=' . $ticket['x__id'] . ' with value [' . $ticket['x__message'] . ']<br />';
        }

    }

    echo 'Currency updated for ' . $updated . ' ideas.';

} if(isset($_GET['go3'])){

    echo '<br /><br /><br /><br /><br /><br />';
    $fixed = array();
    $total_fixed = 0;
    foreach($this->E_model->fetch(array(
        'e__id > 0' => null, //ACTIVE
    ), 0) as $e_dup){

        if(in_array($e_dup['e__id'], $fixed)){
            continue;
        }
        array_push($fixed, $e_dup['e__id']);

        //See if duplicated:
        foreach($this->E_model->fetch(array(
            'e__id !=' => $e_dup['e__id'], //ACTIVE
            'LOWER(e__handle)' => strtolower($e_dup['e__handle']), //ACTIVE
        )) as $e_fix){
            //Update to something unique:
            array_push($fixed, $e_fix['e__id']);
            $this->E_model->update($e_dup['e__id'], array(
                'e__handle' => $e_dup['e__handle'].rand(1000,9999),
            ), false, $member_e['e__id']);
            $total_fixed++;
        }
    }
        echo '<br />'.$total_fixed.' Fixed<br /><br /><br /><br /><br />';

    }





//Breadcrump for logged in users
if($x__creator && count($top_i) && $top_i__hashtag!=$focus_i['i__hashtag']){

    $find_previous = $this->X_model->find_previous($x__creator, $top_i__hashtag, $focus_i['i__id']);
    if(count($find_previous)){

        $nav_list = array();
        $main_branch = array(intval($focus_i['i__id']));
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
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
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

            $breadcrum_content .= '<li class="breadcrumb-item">';
            $breadcrum_content .= '<a href="/'.$top_i__hashtag.'/'.$followings_i['i__hashtag'].'"><u>'.view_i_title($followings_i).'</u></a>';

            //Do we have more sub-items in this branch? Must have more than 1 to show, otherwise the 1 will be included in the main branch:
            if(count($query_subset) >= 2){
                //Show other branches:
                $breadcrum_content .= '<div class="dropdown inline-block">';
                $breadcrum_content .= '<button type="button" class="btn no-side-padding" id="dropdownMenuButton'.$followings_i['i__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $breadcrum_content .= '<span style="padding-left:5px;"><i class="fal fa-chevron-square-down"></i></span>';
                $breadcrum_content .= '</button>';
                $breadcrum_content .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$followings_i['i__id'].'">';
                foreach ($query_subset as $i_subset) {
                    $breadcrum_content .= '<a href="/'.$top_i__hashtag.'/'.$i_subset['i__hashtag'].'" class="dropdown-item main__title '.( in_array($i_subset['i__id'], $main_branch) ? ' active ' : '' ).'">'.view_i_title($i_subset).'</a>';
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



if($top_i__hashtag){

    echo '<div class="active_navigation">';

    $tree_progress = $this->X_model->tree_progress($x__creator, $top_i[0]);
    $top_completed = $tree_progress['fixed_completed_percentage'] >= 100;
    $go_next_url = '/x/x_next/' . $top_i__hashtag . '/' . $focus_i['i__hashtag'];

    if($top_completed){
        echo '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>100% Complete</div>';
    }

    if(isset($_GET['list'])){
        //Secret list for debugging
        echo '<p style="padding:10px;">'.$tree_progress['fixed_discovered'].' of '.$tree_progress['fixed_total'].' Discovered:</p>';
        $counter = 0;
        foreach($tree_progress['list_total'] as $to_discover_id){
            $is = $this->I_model->fetch(array(
                'i__id' => $to_discover_id,
            ));
            $counter++;
            echo '<p style="padding:2px;">'.$counter.') <a href="/~'.$is[0]['i__hashtag'].'">'.( in_array($is[0]['i__id'], $tree_progress['list_discovered']) ? '✅ ' : '' ).view_i_title($is[0]).'</p>';
        }
    }

} else {

    $go_next_url = '/x/x_start/'.$focus_i['i__hashtag'];

}

if($top_completed || $is_or_7712){
    $_GET['open'] = true;
}


echo '<div class="light-bg large-frame">';

//Idea message:
echo view_i_links($focus_i);


$x_selects = array();
if($top_i__hashtag) {

    if ($is_or_7712) {

        //Has no options to choose from?
        if (!count($is_next)) {

            //Mark this as skipped since there is nothing to choose from:
            if (!count($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__creator' => $x__creator,
                'x__left' => $focus_i['i__id'],
            )))) {
                //Skipped:
                $this->X_model->mark_complete(31022, $x__creator, $top_i__id, $focus_i);
            }

        } else {

            //First fetch answers based on correct order:
            foreach ($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'x__left' => $focus_i['i__id'],
            ), array('x__right'), 0, 0, array('x__weight' => 'ASC')) as $x) {
                //See if this answer was selected:
                if (count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                    'x__left' => $focus_i['i__id'],
                    'x__right' => $x['i__id'],
                    'x__creator' => $x__creator,
                )))) {
                    array_push($x_selects, $x);
                }
            }

            if (count($x_selects) > 0) {
                //MODIFY ANSWER
                echo '<div class="save_toggle_answer">';
                echo view_i_list(13980, $top_i__hashtag, $focus_i, $x_selects, $member_e);
                echo '</div>';
            }


            //Open for list to be printed:
            $select_answer = '<div class="row list-answers" i__type="' . $focus_i['i__type'] . '">';

            //List next ideas to choose from:
            foreach ($is_next as $key => $next_i) {

                //Has this been previously selected?
                $previously_selected = count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                    'x__left' => $focus_i['i__id'],
                    'x__right' => $next_i['i__id'],
                    'x__creator' => $x__creator,
                )));

                $select_answer .= view_card_x_select($next_i, $x__creator, $previously_selected);

            }


            $select_answer .= '</div>';

            //HTML:
            echo '<div class="save_toggle_answer ' . (count($x_selects) > 0 ? 'hidden' : '') . '">';
            echo view_headline($focus_i['i__type'], null, $e___4737[$focus_i['i__type']], $select_answer, true);
            echo '</div>';

        }

    } elseif ($focus_i['i__type']==26560) {

        //TICKET
        $ticket_ui = '';

        if(isset($_GET['cancel_pay']) && !count($x_completes)){
            $ticket_ui .= '<div class="alert alert-danger" role="alert">You cancelled your payment.</div>';
        }

        if(isset($_GET['process_pay']) && !count($x_completes)){

            $ticket_ui .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Processing your payment, please wait...</div>';

            //Referesh soon so we can check if completed or not
            js_php_redirect('/'.$top_i__hashtag.'/'.$focus_i['i__hashtag'].'?process_pay=1', 2584);

        } elseif(count($x_completes)){

            foreach($x_completes as $x_complete){

                $x__metadata = unserialize($x_complete['x__metadata']);
                $quantity = ( $x_complete['x__weight'] >= 2 ? $x_complete['x__weight'] : ( isset($x__metadata['quantity']) && $x__metadata['quantity']>=2 ? $x__metadata['quantity'] : 1 ) );

                if($x__metadata['mc_gross']!=0){
                    $ticket_ui .= '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>'.( $x__metadata['mc_gross']>0 ? 'You paid ' : 'You got a refund of ' ).$x__metadata['mc_currency'].' '.str_replace('.00','',$x__metadata['mc_gross']).( $quantity>1 ? ' for '.$quantity.' tickets' : '' ).'</div>';
                }

                if(in_array($x_complete['x__type'], $this->config->item('n___40986'))){
                    //Successful discovery... Show QR Code:
                    foreach($this->E_model->fetch(array(
                        'e__id' => $x_complete['x__creator'],
                    )) as $e){
                        $ticket_ui .= '<div>'.$quantity.' QR Ticket'.view__s($quantity).':</div>';
                        $ticket_ui .= '<div>'.qr_code('https://'.get_domain('m__message', $x__creator).'/'.$top_i__hashtag.'/'.$focus_i['i__hashtag'].'?e__handle='.$e['e__handle'].'&e__hash='.view_e__hash($e['e__handle'])).'</div>';
                    }
                }

            }

            $ticket_ui .= '<input type="hidden" id="paypal_handling" name="handling" value="'.$x__metadata['mc_gross'].'">';
            $ticket_ui .= '<input type="hidden" id="paypal_quantity" name="quantity" value="'.$x__metadata['quantity'].'">'; //Dynamic Variable that JS will update

        } else {

            $valid_currency = false; //Until we can find and verify from DB

            $paypal_email =  website_setting(30882);

            $currency_types = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $focus_i['i__id'],
                'x__up IN (' . join(',', $this->config->item('n___26661')) . ')' => null, //Currency
            ));
            $total_dues = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $focus_i['i__id'],
                'x__up' => 26562, //Total Due
            ));
            $cart_max = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $focus_i['i__id'],
                'x__up' => 29651, //Cart Max Quantity
            ));
            $cart_min = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $focus_i['i__id'],
                'x__up' => 31008, //Cart Min Quantity
            ));



            //Payments Must have Unit Price, otherwise they are NOT a payment until added...
            $info_append = '';
            $unit_currency = '';
            $unit_price = 0;
            $unit_fee = 0;
            $max_allowed = ( count($cart_max) && is_numeric($cart_max[0]['x__message']) && $cart_max[0]['x__message']>1 ? intval($cart_max[0]['x__message']) : view_memory(6404,29651) );
            $spots_remaining = i_spots_remaining($focus_i['i__id']);
            $max_allowed = ( $spots_remaining>-1 && $spots_remaining<$max_allowed ? $spots_remaining : $max_allowed );
            $max_allowed = ( $max_allowed < 1 ? 1 : $max_allowed );
            $min_allowed = ( count($cart_min) && is_numeric($cart_min[0]['x__message']) && intval($cart_min[0]['x__message'])>0 ? intval($cart_min[0]['x__message'])>0 : 1 );
            $min_allowed = ( $min_allowed < 1 ? 1 : $min_allowed );

            if(filter_var($paypal_email, FILTER_VALIDATE_EMAIL) && count($total_dues) && $total_dues[0]['x__message']>0 && count($currency_types)==1){

                $valid_currency = true;
                $e___26661 = $this->config->item('e___26661'); //Currency

                $digest_fees = count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $focus_i['i__id'],
                    'x__up' => 30589, //Digest Fees
                )));

                //Break down amount & currency
                $unit_currency = $e___26661[$currency_types[0]['x__up']]['m__message'];
                $unit_price = doubleval($total_dues[0]['x__message']);
                $unit_fee = number_format($unit_price * ( $digest_fees ? 0 : (doubleval(website_setting(30590, $x__creator)) + doubleval(website_setting(27017, $x__creator)) + doubleval(website_setting(30612, $x__creator)))/100 ), 2, ".", "");

                //Append information to cart:
                $info_append .= '<div class="sub_note">';
                if(!count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $focus_i['i__id'],
                    'x__up' => 30615, //Is Refundable
                )))){
                    $info_append .= 'Final sale. ';
                }

                $info_append .= 'No need to create a Paypal account: You can pay by only entering your credit card details to checkout as a guest. Once paid, click "<span style="color: #990000;">Return to Merchant</span>" to continue back here. By paying you agree to our <a href="'.view_app_link(14373).'" target="_blank"><u>Terms of Use</u></a>.';
                $info_append .= '</div>';

            }








            //Is multi selectable, allow show down for quantity:

            $ticket_ui .= '<div class="source-info ticket-notice">'
                . '<span class="icon-block">'. $e___11035[31837]['m__cover'] . '</span>'
                . '<span>'.$e___11035[31837]['m__title'] . '</span>'
                . '<div class="payment_box">'
                . ( strlen($e___11035[31837]['m__message']) ? '<div class="sub_note main__title">'.nl2br($e___11035[31837]['m__message']).'</div>' : '' );


            if($max_allowed > 1 || $min_allowed > 1){
                $ticket_ui .= '<div>';
                $ticket_ui .= '<a href="javascript:void(0);" onclick="sale_increment(-1)" class="sale_increment"><i class="fas fa-minus-circle"></i></a>';
                $ticket_ui .= '<span id="current_sales" class="main__title" style="display: inline-block; min-width:34px; text-align: center;">'.$min_allowed.'</span>';
                $ticket_ui .= '<a href="javascript:void(0);" onclick="sale_increment(1)" class="sale_increment"><i class="fas fa-plus-circle"></i></a>';
                $ticket_ui .= '</div>';
            } else {
                $ticket_ui .= '<span id="current_sales" style="display: none;">'.$min_allowed.'</span>';
            }


            $qr_link = 'https://'.get_domain('m__message', ( isset($member_e['e__id']) ? $member_e['e__id'] : 0 )).'/'.$focus_i['i__hashtag'].'?e__handle='.$x[0]['e__handle'].'&e__hash='.view_e__hash($x[0]['e__handle']);


            //Display UI:
            echo '<h2 style="text-align: center;">'.view_i_title($is_top[0]).'</h2>';
            echo '<h3 style="text-align: center;">'.view_i_title($is_discovery[0]).'</h3>';
            echo '<h3 style="text-align: center;"><i class="fas fa-user"></i> <a href="/@'.$x[0]['e__handle'].'"><u>'.$x[0]['e__title'].'</u></a>&nbsp;&nbsp;&nbsp;<i class="fas fa-ticket"></i> <b>'.$quantity.' Ticket'.view__s($quantity).'</b></h3>';


            //Is Ticket Scanner Admin?
            /*
            if(isset($_GET['e__handle']) && isset($_GET['e__hash']) && superpower_unlocked(31000)){

                $ticket_checked_in = $this->X_model->fetch(array(
                    'x__reference' => $x[0]['x__id'],
                    'x__type' => 32016,
                ), array('x__up'));

                //See how many tickets have already been checked-in for this user, and give option to check-in the remaining tickets that have not yet been checked-in:
                if(count($ticket_checked_in)){

                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Ticket already checked-in!</div>';

                    echo '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Ticket Already Checked-In by <a href="/@'.$ticket_checked_in[0]['e__handle'].'">'.$ticket_checked_in[0]['e__title'].'</a> about <span class="underdot" title="'.substr($ticket_checked_in[0]['x__time'], 0, 19).' PST">' . view_time_difference(strtotime($ticket_checked_in[0]['x__time'])) . ' Ago</span>.</div>';


                } else {

                    //All good to check-in:
                    $this->X_model->create(array(
                        'x__type' => 32016,
                        'x__creator' => $x[0]['e__id'], //Ticket Buyer
                        'x__up' => $member_e['e__id'], //Ticket Scanner
                        'x__weight' => $quantity, //Tickets Checked-in (They can check-in in multiple rounds)
                        'x__right' => $x[0]['x__right'],
                        'x__left' => $x[0]['x__left'],
                        'x__reference' => $x[0]['x__id'],
                    ));

                    echo '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successful checkin for '.$quantity.' Ticket'.view__s($quantity).'</div>';

                }

                if(isset($_GET['attempt_checkin'])){
                    //QR Code Scanned, auto check-in:



                } elseif(!count($ticket_checked_in)) {

                    //Give option for manual checkin:
                    echo '<div style="text-align: center;"><div class="nav-controller select-btns"><a class="btn btn-lrg btn-6255 go-next" href="'.$qr_link.'">'.$e___11035[32016]['m__title'].' '.$e___11035[32016]['m__cover'].'</a></div></div>';

                }

            }
            */

            if($unit_price > 0){
                $ticket_ui .= '<div style="padding: 8px 0 21px;" '.( $unit_fee > 0 ? ' title="Base Price of '.$unit_price.' + '.$unit_fee.' in Fees" data-toggle="tooltip" data-placement="top" ' : '' ).'><span id="total_ui" class="main__title">'.(($unit_fee+$unit_price)*$min_allowed).'</span> '.$unit_currency.'</div>';
            } else {
                $ticket_ui .= '<span id="total_ui" style="display: none;">0</span>';
            }

            $ticket_ui .= $info_append;

            $ticket_ui .= '</div>';
            $ticket_ui .= '</div>';


            if($valid_currency){

                //Load Paypal Pay button:
                $ticket_ui .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_form" target="_top">';

                $ticket_ui .= '<input type="hidden" id="paypal_handling" name="handling" value="'.$unit_fee.'">';
                $ticket_ui .= '<input type="hidden" id="paypal_quantity" name="quantity" value="'.$min_allowed.'">'; //Dynamic Variable that JS will update
                $ticket_ui .= '<input type="hidden" id="paypal_item_name" name="item_name" value="'.view_i_title($focus_i, true).'">';
                $ticket_ui .= '<input type="hidden" id="paypal_item_number" name="item_number" value="'.$top_i__id.'-'.$focus_i['i__id'].'-0-'.$x__creator.'">';

                $ticket_ui .= '<input type="hidden" name="amount" value="'.$unit_price.'">';
                $ticket_ui .= '<input type="hidden" name="currency_code" value="'.$unit_currency.'">';
                $ticket_ui .= '<input type="hidden" name="no_shipping" value="1">';
                $ticket_ui .= '<input type="hidden" name="notify_url" value="https://mench.com'.view_app_link(26595).'">';
                $ticket_ui .= '<input type="hidden" name="cancel_return" value="https://'.get_domain('m__message').'/'.$top_i__hashtag.'/'.$focus_i['i__hashtag'].'?cancel_pay=1">';
                $ticket_ui .= '<input type="hidden" name="return" value="https://'.get_domain('m__message').'/'.$top_i__hashtag.'/'.$focus_i['i__hashtag'].'?process_pay=1">';
                $ticket_ui .= '<input type="hidden" name="cmd" value="_xclick">';
                $ticket_ui .= '<input type="hidden" name="business" value="'.$paypal_email.'">';

                $ticket_ui .= '<input type="submit" class="adj-btn pay-btn main__title" name="pay_now" id="pay_now" value="Pay Now >" onclick="$(\'.process-btn\').html(\'Loading...\');$(\'#pay_now\').val(\'...\');">';

                $ticket_ui .= '</form>';

                //Hide Standard Next Button:
                $ticket_ui .= '<script> $(document).ready(function () { $("#next_div").hide(); }); </script>';

            } else {

                //FREE TICKET
                $ticket_ui .= '<input type="hidden" id="paypal_handling" name="handling" value="'.$unit_fee.'">';
                $ticket_ui .= '<input type="hidden" id="paypal_quantity" name="quantity" value="'.$min_allowed.'">'; //Dynamic Variable that JS will update

            }

            ?>

            <script>
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

    } elseif (in_array($focus_i['i__type'], $this->config->item('n___34849'))) {

        //Do we have a text response from before?
        $previous_response = '';
        if($x__creator){
            //Does this have any append sources?
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 7545, //Following Add
                'x__right' => $focus_i['i__id'],
            )) as $append_e){
                //Does the user have this source with any values?
                foreach($this->X_model->fetch(array(
                    'x__up' => $append_e['x__up'],
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

        if ($focus_i['i__type']==6683) {

            //Text response
            $message_ui = '<textarea class="border i_content x_input greybg" placeholder="" id="x_write">' . $previous_response . '</textarea>';

        } elseif ($focus_i['i__type']==32603) {

            $message_ui = view_sign($focus_i, $previous_response);

        } else {

            //Determine type:
            if($focus_i['i__type']==31794){

                //Number
                $input_type = 'number';

                //Steps
                foreach($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $focus_i['i__id'],
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
                    'x__right' => $focus_i['i__id'],
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
                    'x__right' => $focus_i['i__id'],
                    'x__up' => 31801, //Max Value
                )) as $num_steps){
                    if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                        $input_attributes .= ' max="'.$num_steps['x__message'].'" ';
                    }
                }

            } elseif($focus_i['i__type']==30350){

                $input_type = (count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $focus_i['i__id'],
                    'x__up' => 32442, //Select Time
                ))) ? 'datetime-local'  : 'date' );

            } elseif($focus_i['i__type']==31794){

                //Number
                $input_type = 'number';

            } elseif($focus_i['i__type']==31795){

                //URL
                $input_type = 'url';

            }


            $message_ui = '<input type="'.$input_type.'" '.$input_attributes.' class="border i_content greybg x_input" placeholder="" value="'.$previous_response.'" id="x_write" />';

        }

        $message_ui .= '<script> $(document).ready(function () { set_autosize($(\'#x_write\')); $(\'#x_write\').focus(); }); </script>';
        echo '<div>'.$message_ui.'</div>';

    } elseif ($focus_i['i__type']==7637) {

        //FILE UPLOAD
        echo '<div class="userUploader">';
        echo '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

        echo '<input class="inputfile" type="file" name="file" id="fileType' . $focus_i['i__type'] . '" />';

        if (count($x_completes)) {

            echo '<div class="file_save_result greybg">';
            echo view_headline(13977, null, $e___11035[13977], view_sync_links($x_completes[0]['x__message']), true);
            echo '</div>';

        } else {

            //for when added:
            echo '<div class="file_save_result center"></div>';

        }

        //UPLOAD BUTTON:
        echo '<div class="select-btns"><label class="btn btn-6255 inline-block" for="fileType' . $focus_i['i__type'] . '" style="margin-left:5px;">' . $e___11035[13572]['m__cover'] . ' ' . $e___11035[13572]['m__title'] . '</label></div>';


        echo '<div class="doclear">&nbsp;</div>';
        echo '</form>';
        echo '</div>';

    }

}


//Show


$pathways_count = 0;
if(!$top_i__hashtag){

    if(count($this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
        'x__right' => $focus_i['i__id'],
        'x__up' => 4235,
    )))){

        //Get Started
        echo '<div class="nav-controller select-btns"><a class="btn btn-lrg btn-6255 go-next" href="javascript:void(0);" onclick="go_next()">'.$e___11035[4235]['m__title'].' '.$e___11035[4235]['m__cover'].'</a></div>';
        echo '<div class="doclear">&nbsp;</div>';

    } else {

        //Show Link to Top, if any:
        $pathways = '<div class="row list-pathways">';
        foreach($this->I_model->recursive_starting_points($focus_i['i__id']) as $top_id){
            foreach($this->I_model->fetch(array(
                'i__id' => $top_id,
            )) as $top_start){
                $pathways_count++;
                $pathways .= view_card_i(6255, 0, null, $top_start);
            }
        }
        $pathways .= '</div>';

        if(!$pathways_count){
            $_GET['open'] = true;
        }

    }

} else {

    $buttons_found = 0;
    $buttons_ui = '';

    foreach($this->config->item('e___13289') as $x__type => $m2) {

        $superpowers_required = array_intersect($this->config->item('n___10957'), $m2['m__following']);
        if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
            continue;
        }

        $control_btn = '';

        if($x__creator && in_array($x__type, $this->config->item('n___12274')) && 0){

            //TODO Remove later

            //Sources
            if(is_array($this->config->item('n___'.$x__type))){
                foreach(array_intersect($this->config->item('n___'.$x__type), $this->config->item('n___31127')) as $pending_action_id) {

                    //Is this action already taken?
                    $action_xs = $this->X_model->fetch(array(
                        'x__up' => $x__creator,
                        'x__right' => $focus_i['i__id'],
                        'x__type' => $x__type,
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    ));

                    $control_btn = '<a class="round-btn btn_control_'.$x__type.'" href="javascript:void(0);" onclick="x_link_toggle('.$x__type.', '.$focus_i['i__id'].')" current_x_id="'.( count($action_xs) ? $action_xs[0]['x__id'] : '0' ).'"><span class="controller-nav btn_toggle_'.$x__type.' '.( count($action_xs) ? '' : 'hidden' ).'">'.$m2['m__cover'].'</span><span class="controller-nav btn_toggle_'.$x__type.' '.( count($action_xs) ? 'hidden' : '' ).'">'.$e___31127[$pending_action_id]['m__cover'].'</span></a><span class="nav-title main__title">'.$m2['m__title'].'</span>';

                    break;// Ignore if more than one...
                }
            }

        } elseif($x__type==112233){

            //TODO imeplement IDs
            $control_btn = view_toggle_dropdown(112233, 112255, $focus_i['i__id']);

        } elseif($x__type==6255){

            //Count total
            $sub_counter = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__right' => $focus_i['i__id'],
            ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

            //Discoveries:
            $control_btn = '<a class="controller-nav round-btn" href="javascript:void(0);" onclick="alert(\'The total number of times this idea has been discovered.\')">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.view_number($sub_counter[0]['totals']).' '.$m2['m__title'].'</span>';

        } elseif($x__type==12273 && !$is_or_7712 && count($is_next)){

            //Ideas
            $control_btn = '<a class="controller-nav round-btn" href="javascript:void(0);" onclick="toggle_headline(12211)">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.count($is_next).' '.$m2['m__title'].'</span>';

        } elseif($x__type==12211 && !$top_completed){

            //NEXT
            $control_btn = '<div style="padding-left: 8px;" id="next_div"><a class="controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="go_next()">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';

        } elseif($x__type==13495 && count($x_selects)){

            //Edit response:
            $control_btn = '<div style="padding-left: 8px;" class="save_toggle_answer"><a class="controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="$(\'.save_toggle_answer\').toggleClass(\'hidden\');">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';

            $control_btn .= '<div style="padding-left: 8px;" class="save_toggle_answer hidden"><a class="controller-nav round-btn main-next" href="javascript:void(0);" onclick="$(\'.save_toggle_answer\').toggleClass(\'hidden\');">'.$e___11035[40639]['m__cover'].'</a><span class="nav-title main__title">'.$e___11035[40639]['m__title'].'</span></div>';

        } elseif($x__type==14422 && $top_completed && in_array($focus_i['i__type'], $this->config->item('n___34849'))){

            //Save Response
            $control_btn = '<div style="padding-left: 8px;"><a class="controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="go_next()">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';

        } elseif($x__type==42205 && $can_skip && !$top_completed && !count($x_completes)){

            //SKIP
            $control_btn = '<div style="padding-left: 13px;" class="save_toggle_answer"><a class="controller-nav round-btn" href="javascript:void(0);" onclick="x_skip()">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';

        }

        $buttons_ui .= ( $control_btn ? '<div class="navigate_item navigate_'.$x__type.'">'.$control_btn.'</div>' : '' );

        if($control_btn){
            $buttons_found++;
        }

    }

    if($buttons_found > 0){
        echo '<div class="nav-controller">';
        echo $buttons_ui;
        echo '</div>';
    }

}







//NEXT IDEAS:
echo '<div class="nav-body">';

//Append add new idea as a comment button at the end:
$body_append = '<a href="javascript:void(0);" onclick="save_load_i(0,0,'.$focus_i['i__id'].')" style="margin-left: 0;">'.$e___11035[31772]['m__cover'].' '.$e___11035[31772]['m__title'].'</a></td>'; //TODO fix icon reference

if(!($is_or_7712 && $top_i__hashtag)){
    echo view_i_list(12211, $top_i__hashtag, $focus_i, $is_next, $member_e, $body_append);
} else {
    //Options have already been presented in the form of selections...
}
echo '</div>';



if($top_i__hashtag && !$top_completed) {
    echo '<div style="padding: 0 5px;"><div class="progress">
<div class="progress-bar bg6255" role="progressbar" data-toggle="tooltip" data-placement="top" title="'.$tree_progress['fixed_discovered'].'/'.$tree_progress['fixed_total'].' Ideas Discovered '.$tree_progress['fixed_completed_percentage'].'%" style="width: '.$tree_progress['fixed_completed_percentage'].'%" aria-valuenow="'.$tree_progress['fixed_completed_percentage'].'" aria-valuemin="0" aria-valuemax="100"></div>
</div></div>';
} elseif(!$top_i__hashtag && $pathways_count > 0){
    echo view_headline(40798, $pathways_count, $e___11035[40798], $pathways, true);
}

echo '</div>';

if($top_i__hashtag){
    echo '</div>';
}

?>

<style> .headline_12211, .headline_13980 { display: none !important; } </style>
<script>
    var focus_i__type = <?= $focus_i['i__type'] ?>;
    var can_skip = <?= intval($can_skip) ?>;
</script>

<input type="hidden" id="focus_card" value="12273" />
    <input type="hidden" id="focus_id" value="<?= $focus_i['i__id'] ?>" />
    <input type="hidden" id="focus_handle" value="<?= $focus_i['i__hashtag'] ?>" />
<input type="hidden" id="top_i__id" value="<?= $top_i__hashtag ?>" />
<input type="hidden" id="go_next_url" value="<?= $go_next_url ?>" />

<script>

    var audio_played = false;
    $(document).ready(function () {

        //Auto next a single answer:
        if(!can_skip && js_n___7712.includes(parseInt($('.list-answers').attr('i__type')))){
            //It is, see if it has only 1 option:
            var single_id = 0;
            var answer_count = 0;
            $(".answer-item").each(function () {
                single_id = parseInt($(this).attr('selection_i__id'));
                answer_count++;
            });
            if(answer_count==1){
                //Only 1 option, select and go next only if the user cannot skip:
                select_answer(single_id);
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
    function select_answer(i__id){

        if(is_toggling){
            return false;
        }
        is_toggling = true;

        //Allow answer to be saved/updated:
        var i__type = parseInt($('.list-answers').attr('i__type'));

        //Clear all if single selection:
        var is_single_selection = (i__type==6684);
        if(is_single_selection){
            //Single Selection, clear all previously selected answers, if any:
            $('.answer-item').removeClass('isSelected');
        }

        //Is selected?
        if($('.x_select_'+i__id).hasClass('isSelected')){

            //Previously Selected, delete Multi-selection:
            if(!is_single_selection){
                //Multi Selection
                $('.x_select_'+i__id).removeClass('isSelected');
            }

            is_toggling = false;

        } else {

            //Not selected, select now:
            $('.x_select_'+i__id).addClass('isSelected');

            if(is_single_selection){
                //Auto submit answer since they selected one:
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
        if (is_logged_in && js_n___7712.includes(focus_i__type) && $('.list-answers .answer-item').length){

            //SELECT ONE/SOME
            return x_select();

        } else if(is_logged_in && js_n___34849.includes(focus_i__type)) {

            if(focus_i__type==32603 && !$("#DigitalSignAgreement").is(':checked')){
                if(can_skip){
                    x_skip();
                } else {
                    //Must upload file first:
                    alert('Please agree to terms of service before going next.');
                }
            } else {
                //SUBMIT TEXT RESPONSE:
                return x_write();
            }

        } else if (is_logged_in && focus_i__type==7637 && !$('.file_save_result').html().length ) {

            if(!can_skip){
                //Must upload file first:
                alert('Please upload a file before going next.');
            } else {
                x_skip();
            }

        } else if (is_logged_in && focus_i__type==26560 ) {

            return x_free_ticket();

        } else if(go_next_url && go_next_url.length > 0) {

            if (is_logged_in && js_n___34826.includes(focus_i__type) && parseInt($('#top_i__id').val()) > 0) {

                //READ:
                return read_only_complete();

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

        $('.file_save_result').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span><span class="main__title">UPLOADING...</span>');

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
                    $('.file_save_result').html(data.message);
                    $('.go_next_upload').removeClass('hidden');

                },
                error: function (data) {
                    //Show Error:
                    $('.file_save_result').html(data.responseText);
                }
            });
        } else {
            // ajax for legacy browsers
        }

    }


    function x_write(){
        $.post("/x/x_write", {
            top_i__id:$('#top_i__id').val(),
            i__id:fetch_int_val('#focus_id'),
            x_write:$('#x_write').val(),
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin zq6255"></i>');
                js_redirect($('#go_next_url').val());
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }




    function read_only_complete(){
        $.post("/x/read_only_complete", {
            top_i__id:$('#top_i__id').val(),
            i__id:fetch_int_val('#focus_id'),
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin zq6255"></i>');
                js_redirect($('#go_next_url').val());
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }


    function x_skip(){

        if(!can_skip){
            alert('You cannot skip this...');
            return false;
        }

        $.post("/x/x_skip", {
            top_i__id:$('#top_i__id').val(),
            i__id:fetch_int_val('#focus_id'),
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin zq6255"></i>');
                js_redirect($('#go_next_url').val());
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }


    function x_free_ticket(){
        $.post("/x/x_free_ticket", {
            top_i__id:$('#top_i__id').val(),
            i__id:fetch_int_val('#focus_id'),
            paypal_quantity:$('#paypal_quantity').val(),
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin zq6255"></i>');
                js_redirect($('#go_next_url').val());
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }

    function x_select(){

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
                js_redirect($('#go_next_url').val());
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