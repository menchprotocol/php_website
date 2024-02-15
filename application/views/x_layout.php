<?php

$e___11035 = $this->config->item('e___11035'); //Summary
$e___4737 = $this->config->item('e___4737'); //Idea Types
$is_or_7712 = in_array($focus_i['i__type'], $this->config->item('n___7712'));


if(write_privacy_i($focus_i['i__hashtag']) && superpower_unlocked(10939)){
    echo '<div class="alert alert-default" role="alert"><span class="icon-block-xs">'.$e___11035[33286]['m__cover'].'</span>You can edit this idea in <a href="/~'.$focus_i['i__hashtag'].'"><b><u>'.$e___11035[33286]['m__title'].'</u></b></a></div>';
}

//NEXT IDEAS
$is_next = $this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Sequence Down
    'x__previous' => $focus_i['i__id'],
), array('x__next'), 0, 0, array('x__weight' => 'ASC'));

//Filter Next Ideas:
foreach($is_next as $in_key => $in_value){
    $i_is_discoverable = i_is_discoverable($in_value['i__id'], false);
    if(!$i_is_discoverable['status']){
        //Remove this option:
        unset($is_next[$in_key]);
    }
}

$x__creator = ( $member_e ? $member_e['e__id'] : 0 );
$focus_i['i__message'] = str_replace('"','',$focus_i['i__message']);
$pathways_count = 0;
$top_i__id = ( count($top_i) ? $top_i['i__id'] : 0 );
    $top_i__id = ( count($top_i) && $x__creator ? $top_i['i__id'] : 0 );
    $top_i__hashtag = ( count($top_i) && $x__creator ? $top_i['i__hashtag'] : null );
$top_completed = false; //Assume main intent not yet completed, unless proven otherwise
$can_skip = in_array($focus_i['i__type'], $this->config->item('n___42211')) || count($this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
    'x__next' => $focus_i['i__id'],
    'x__following' => 28239, //Can Skip
)));


if(isset($_GET['delete'])){

    boost_power();

    foreach($this->X_model->fetch(array(
        'x__following' => $_GET['delete'],
        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    ), array('x__follower'), 0) as $x) {

        if(count($this->X_model->fetch(array(
                'x__follower' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            ), array(), 0))<=2 && !count($this->X_model->fetch(array(
                'x__following' => $x['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            ), array(), 0))){
            //Good to delete:

            //Delete all transactions:
            $links_removed = $this->E_model->remove($x['e__id'], $member_e['e__id'], 0);
            $this->E_model->update($x['e__id'], array(
                'e__privacy' => 6178,
            ), true, $member_e['e__id']);

        } else {
            //Issues:
            echo '<div>Cannot delete <a href="/@'.$x['e__handle'].'">@'.$x['e__handle'].'</a>.</div>';
        }

    }

} elseif(isset($_GET['go1'])){

    boost_power();

    //Sync Ideas & Sources
    $stats = array(
        'cached_ideas' => 0,
        'active_ideas' => 0,
        'old_links_removed' => 0,
        'old_links_kept' => 0,
        'new_links_added' => 0,
        'missing_creation' => 0,
    );

    $edited = 0;
    $edited_sources = 0;
    foreach($this->I_model->fetch(array(
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    ), 0) as $i_fix){

        $view_sync_links = view_sync_links($i_fix['i__message'], true, $i_fix['i__id']);

        /*
        echo '<a href="/~'.$i_fix['i__hashtag'].'">#'.$i_fix['i__hashtag'].'</a><br />';
        echo nl2br(htmlentities($i_fix['i__message'])).'<br />';

        if(count($view_sync_links['replace_from'])){

            //Show all:
            $starting_message = $i_fix['i__message'];

            foreach($view_sync_links['replace_from'] as $index=>$val){
                $edited_sources++;
                if(substr_count($starting_message, $view_sync_links['replace_from'][$index].' ')){
                    $starting_message = str_replace($view_sync_links['replace_from'][$index].' ',$view_sync_links['replace_to'][$index].' ',$starting_message);
                    echo '<div>['.$view_sync_links['replace_from'][$index].' ] Replaced to ['.$view_sync_links['replace_to'][$index].' ]</div>';
                } else {
                    $starting_message = str_replace($view_sync_links['replace_from'][$index],$view_sync_links['replace_to'][$index],$starting_message);
                    echo '<div>['.$view_sync_links['replace_from'][$index].'] Replaced to ['.$view_sync_links['replace_to'][$index].']</div>';
                }
            }

            if($starting_message!=$i_fix['i__message']){
                //view_sync_links($starting_message, true, $i_fix['i__id']);
                $edited++;
            }

        }
        $stats['old_links_removed'] += $view_sync_links['sync_stats']['old_links_removed'];
        $stats['old_links_kept'] += $view_sync_links['sync_stats']['old_links_kept'];
        $stats['new_links_added'] += $view_sync_links['sync_stats']['new_links_added'];

        */
    }

    echo '<hr />Edited ['.$edited.']['.$edited_sources.']<br />';

} elseif(isset($_GET['go3'])) {
    foreach($this->I_model->fetch(array(
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        'i__id' => 20895, //ACTIVE
    ), 0) as $i_fix){
        $view_sync_links = view_sync_links($i_fix['i__message'], true, $i_fix['i__id']);
    }
    echo 'donedone';

} elseif(isset($_GET['go5'])) {

    $stats = array(
        'scanned' => 0,
        'updated' => 0,
    );
    foreach($this->E_model->fetch(array(
        'e__id NOT IN (' . join(',', $this->config->item('n___42125')) . ')' => null, //Handle Lock
    ), 0) as $e){
        $stats['scanned']++;
        continue;
        $validate_handle = validate_handle(random_string(13), null, $e['e__id']);
        if($validate_handle['status']){
            $stats['updated']++;
        }
    }
    echo print_r($stats, true);

} elseif(isset($_GET['go2'])) {

    boost_power();
    foreach ($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
        'x__following IN (' . join(',', $this->config->item('n___4318')) . ')' => null,
    ), array('x__following'), 0) as $target) {

        $target['x__message'] = strtolower($target['x__message']);

        foreach(array('mon','tue','wed','thu','fri','sat','sun') as $day){
            $target['x__message'] = str_replace($day.'.','',$target['x__message']);
            $target['x__message'] = str_replace($day.' ','',$target['x__message']);
            $target['x__message'] = str_replace($day,'',$target['x__message']);
        }
        $target['x__message'] = trim($target['x__message']);

        if(strlen($target['x__message']) && !substr_count($target['x__message'], '2023')){
            $target['x__message'] = $target['x__message'].' 2023';
        }
        $target['x__message'] = str_replace('p ','pm ',$target['x__message']);
        $target['x__message'] = str_replace('a ','am ',$target['x__message']);

        if(strtotime($target['x__message'])>0){
            echo '<div>@'.$target['e__handle'].' | '.$target['x__message'].' => '.date('Y-m-d\TH:i:sP', strtotime($target['x__message'])).'</div>';
        } else {
            echo '<div>@'.$target['e__handle'].' ERROR: '.$target['x__message'].'</div>';

        }
    }
    echo '<br /><br /><br /><br />';

}



//Breadcrump for logged in users
$breadcrum_content = null;

if($x__creator && count($top_i) && $top_i__hashtag!=$focus_i['i__hashtag']){

    $find_previous = $this->X_model->find_previous($x__creator, $top_i__hashtag, $focus_i['i__id']);
    if(count($find_previous)){

        $nav_list = array();
        $main_branch = array(intval($focus_i['i__id']));
        foreach($find_previous as $followings_i){
            //First add-up the main branch:
            array_push($main_branch, intval($followings_i['i__id']));
        }

        $level = 0;
        foreach($find_previous as $followings_i){

            $level++;

            //Does this have a follower list?
            $query_subset = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Sequence Down
                'x__previous' => $followings_i['i__id'],
            ), array('x__next'), 0, 0, array('x__weight' => 'ASC'));
            foreach($query_subset as $key=>$value){
                $i_is_discoverable = i_is_discoverable($value['i__id'], false);
                if(!$i_is_discoverable['status'] || !count($this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'x__creator' => $x__creator,
                        'x__previous' => $value['i__id'],
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
                $breadcrum_content .= '<button type="button" class="btn no-side-padding" id="dropdown_instant_'.$followings_i['i__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $breadcrum_content .= '<span style="padding-left:5px;"><i class="fal fa-chevron-square-down"></i></span>';
                $breadcrum_content .= '</button>';
                $breadcrum_content .= '<div class="dropdown-menu" aria-labelledby="dropdown_instant_'.$followings_i['i__id'].'">';
                foreach ($query_subset as $i_subset) {
                    $breadcrum_content .= '<a href="/'.$top_i__hashtag.'/'.$i_subset['i__hashtag'].'" class="dropdown-item main__title '.( in_array($i_subset['i__id'], $main_branch) ? ' active ' : '' ).'">'.view_i_title($i_subset).'</a>';
                }
                $breadcrum_content .= '</div>';
                $breadcrum_content .= '</div>';
            }

            $breadcrum_content .= '</li>';
        }
    }
}

if(!$breadcrum_content){
    //See if any visible top ideas:
    foreach($this->X_model->fetch(array(
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___42268')) . ')' => null, //Active Sequence Up
        'x__next' => $focus_i['i__id'],
    ), array('x__previous')) as $prev_i){
        $breadcrum_content .= '<li class="breadcrumb-item breadcrumb-flat">';
        $breadcrum_content .= '<a href="/'.$prev_i['i__hashtag'].'"><u>'.view_i_title($prev_i).'</u></a>';
        $breadcrum_content .= '</li>';
    }
}

if($breadcrum_content){

    //Add blank item to get final arrow:
    $breadcrum_content .= '<li class="breadcrumb-item">&nbsp;</li>';

    echo '<nav aria-label="breadcrumb" style="background-color: #FFFFFF;"><ol class="breadcrumb">';
    echo $breadcrum_content;
    echo '</ol></nav>';
}



if($top_i__hashtag){

    echo '<div class="active_navigation">';

    $tree_progress = $this->X_model->tree_progress($x__creator, $top_i);
    $top_completed = $tree_progress['fixed_completed_percentage'] >= 100;
    $go_next_url = '/ajax/x_next/' . $top_i__hashtag . '/' . $focus_i['i__hashtag'];

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

    $go_next_url = '/ajax/x_start/'.$focus_i['i__hashtag'];

}


if($top_completed || $is_or_7712){
    $_GET['open'] = true;
}



//Focus Idea:
echo '<div class="main_item row justify-content">';
echo view_card_i(42288, $focus_i);
echo '</div>';


$x_selects = array();
if($top_i__hashtag) {

    if ($is_or_7712) {

        //Has no options to choose from?
        if (!count($is_next)) {

            //Mark this as skipped since there is nothing to choose from:
            if (!count($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__creator' => $x__creator,
                'x__previous' => $focus_i['i__id'],
            )))) {
                //Skipped:
                $this->X_model->mark_complete(31022, $x__creator, $top_i__id, $focus_i);
            }

        } else {

            //First fetch answers based on correct order:
            foreach ($this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Sequence Down
                'x__previous' => $focus_i['i__id'],
            ), array('x__next'), 0, 0, array('x__weight' => 'ASC')) as $x) {
                //See if this answer was selected:
                if (count($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                    'x__previous' => $focus_i['i__id'],
                    'x__next' => $x['i__id'],
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
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                    'x__previous' => $focus_i['i__id'],
                    'x__next' => $next_i['i__id'],
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

            $ticket_ui .= '<div class="alert alert-warning" role="alert"><span class="icon-block-xx"><i class="far fa-yin-yang fa-spin"></i></span>Processing your payment, please wait</div>';

            //Referesh soon so we can check if completed or not
            js_php_redirect('/'.$top_i__hashtag.'/'.$focus_i['i__hashtag'].'?process_pay=1', 987);

        } elseif(count($x_completes)){

            foreach($x_completes as $x_complete){

                $x__metadata = unserialize($x_complete['x__metadata']);
                $quantity = ( $x_complete['x__weight'] >= 2 ? $x_complete['x__weight'] : ( isset($x__metadata['quantity']) && $x__metadata['quantity']>=2 ? $x__metadata['quantity'] : 1 ) );

                if($x__metadata['mc_gross']!=0){
                    $ticket_ui .= '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>'.( $x__metadata['mc_gross']>0 ? 'You paid ' : 'You got a refund of ' ).$x__metadata['mc_currency'].' '.str_replace('.00','',$x__metadata['mc_gross']).( $quantity>1 ? ' for '.$quantity.' tickets' : '' ).'</div>';
                }

                if(in_array($x_complete['x__type'], $this->config->item('n___40986'))){
                    //Successful discovery Show QR Code:
                    foreach($this->E_model->fetch(array(
                        'e__id' => $x_complete['x__creator'],
                    )) as $e){
                        $ticket_ui .= '<div>'.$quantity.' QR Ticket'.view__s($quantity).':</div>';
                        $ticket_ui .= '<div>'.qr_code('https://'.get_domain('m__message', $x__creator).'/'.$top_i__hashtag.'/'.$focus_i['i__hashtag'].'?e__handle='.$e['e__handle'].'&e__time='.time().'&e__hash='.view__hash(time().$e['e__handle'])).'</div>';
                    }
                }

            }

            $ticket_ui .= '<input type="hidden" id="paypal_handling" name="handling" value="'.$x__metadata['mc_gross'].'">';
            $ticket_ui .= '<input type="hidden" id="paypal_quantity" name="quantity" value="'.$x__metadata['quantity'].'">'; //Dynamic Variable that JS will update

        } else {

            $valid_currency = false; //Until we can find and verify from DB

            $paypal_email =  website_setting(30882);

            $currency_types = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                'x__next' => $focus_i['i__id'],
                'x__following IN (' . join(',', $this->config->item('n___26661')) . ')' => null, //Currency
            ));
            $total_dues = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                'x__next' => $focus_i['i__id'],
                'x__following' => 26562, //Total Due
            ));
            $cart_max = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                'x__next' => $focus_i['i__id'],
                'x__following' => 29651, //Cart Max Quantity
            ));
            $cart_min = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                'x__next' => $focus_i['i__id'],
                'x__following' => 31008, //Cart Min Quantity
            ));



            //Payments Must have Unit Price, otherwise they are NOT a payment until added
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
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                    'x__next' => $focus_i['i__id'],
                    'x__following' => 30589, //Digest Fees
                )));

                //Break down amount & currency
                $unit_currency = $e___26661[$currency_types[0]['x__following']]['m__message'];
                $unit_price = doubleval($total_dues[0]['x__message']);
                $unit_fee = number_format($unit_price * ( $digest_fees ? 0 : (doubleval(website_setting(30590, $x__creator)) + doubleval(website_setting(27017, $x__creator)))/100 ), 2, ".", "");

                //Append information to cart:
                $info_append .= '<div class="sub_note">';
                if(!count($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                    'x__next' => $focus_i['i__id'],
                    'x__following' => 30615, //Is Refundable
                )))){
                    $info_append .= 'Final sale. ';
                }

                $info_append .= 'No need to create a Paypal account: You can pay by only entering your credit card details to checkout as a guest. Once paid, click "<span style="color: #990000;">Return to Merchant</span>" to continue back here. By paying you agree to our <a href="'.view_app_link(14373).'" target="_blank"><u>Terms of Use</u></a>.';
                $info_append .= '</div>';

            }








            //Is multi selectable, allow show down for quantity:

            $ticket_ui .= '<div class="source-info ticket-notice">'
                . '<span class="icon-block">'. $e___11035[31076]['m__cover'] . '</span>'
                . '<span>'.$e___11035[31076]['m__title'] . '</span>'
                . '<div class="source_info_box">'
                . ( strlen($e___11035[31076]['m__message']) ? '<div class="sub_note main__title">'.nl2br($e___11035[31076]['m__message']).'</div>' : '' );


            if($max_allowed > 1 || $min_allowed > 1){
                $ticket_ui .= '<div>';
                $ticket_ui .= '<a href="javascript:void(0);" onclick="sale_increment(-1)" class="sale_increment"><i class="fas fa-minus-circle"></i></a>';
                $ticket_ui .= '<span id="current_sales" class="main__title" style="display: inline-block; min-width:34px; text-align: center;">'.$min_allowed.'</span>';
                $ticket_ui .= '<a href="javascript:void(0);" onclick="sale_increment(1)" class="sale_increment"><i class="fas fa-plus-circle"></i></a>';
                $ticket_ui .= '</div>';
            } else {
                $ticket_ui .= '<span id="current_sales" style="display: none;">'.$min_allowed.'</span>';
            }


            /*
                            //Display UI:
                            echo '<h2 style="text-align: center;">'.view_i_title($is_top[0]).'</h2>';
                            echo '<h3 style="text-align: center;">'.view_i_title($is_discovery[0]).'</h3>';
                            echo '<h3 style="text-align: center;"><i class="fas fa-user"></i> <a href="/@'.$x[0]['e__handle'].'"><u>'.$x[0]['e__title'].'</u></a>&nbsp;&nbsp;&nbsp;<i class="fas fa-ticket"></i> <b>'.$quantity.' Ticket'.view__s($quantity).'</b></h3>';


                            //Is Ticket Scanner Admin?

                            if(isset($_GET['e__handle']) && isset($_GET['e__hash']) && superpower_unlocked(31000)){

                                $ticket_checked_in = $this->X_model->fetch(array(
                                    'x__reference' => $x[0]['x__id'],
                                    'x__type' => 32016,
                                ), array('x__following'));

                                //See how many tickets have already been checked-in for this user, and give option to check-in the remaining tickets that have not yet been checked-in:
                                if(count($ticket_checked_in)){

                                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Ticket already checked-in!</div>';

                                    echo '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Ticket Already Checked-In by <a href="/@'.$ticket_checked_in[0]['e__handle'].'">'.$ticket_checked_in[0]['e__title'].'</a> about <span class="underdot" title="'.substr($ticket_checked_in[0]['x__time'], 0, 19).' PST">' . view_time_difference($ticket_checked_in[0]['x__time']) . ' Ago</span>.</div>';


                                } else {

                                    //All good to check-in:
                                    $this->X_model->create(array(
                                        'x__type' => 32016,
                                        'x__creator' => $x[0]['e__id'], //Ticket Buyer
                                        'x__following' => $member_e['e__id'], //Ticket Scanner
                                        'x__weight' => $quantity, //Tickets Checked-in (They can check-in in multiple rounds)
                                        'x__next' => $x[0]['x__next'],
                                        'x__previous' => $x[0]['x__previous'],
                                        'x__reference' => $x[0]['x__id'],
                                    ));

                                    echo '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successful checkin for '.$quantity.' Ticket'.view__s($quantity).'</div>';

                                }

                                if(isset($_GET['attempt_checkin'])){
                                    //QR Code Scanned, auto check-in:



                                } elseif(!count($ticket_checked_in)) {

                                            $qr_link = 'https://'.get_domain('m__message', ( isset($member_e['e__id']) ? $member_e['e__id'] : 0 )).'/'.$focus_i['i__hashtag'].'?e__handle='.$x[0]['e__handle'].'&e__time='.time().'&e__hash='.view__hash(time().$x[0]['e__handle']);


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

                $ticket_ui .= '<input type="submit" class="adj-btn pay-btn main__title" name="pay_now" id="pay_now" value="Pay Now >" onclick="$(\'.process-btn\').html(\'Loading\');$(\'#pay_now\').val(\'...\');">';

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
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 7545, //Following Add
                'x__next' => $focus_i['i__id'],
            )) as $append_e){
                //Does the user have this source with any values?
                foreach($this->X_model->fetch(array(
                    'x__following' => $append_e['x__following'],
                    'x__follower' => $x__creator,
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
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
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                    'x__next' => $focus_i['i__id'],
                    'x__following' => 31813, //Steps
                )) as $num_steps){
                    if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                        $input_attributes .= ' step="'.$num_steps['x__message'].'" ';
                    }
                }

                //Min Value
                foreach($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                    'x__next' => $focus_i['i__id'],
                    'x__following' => 31800, //Min Value
                )) as $num_steps){
                    if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                        $input_attributes .= ' min="'.$num_steps['x__message'].'" ';
                    }
                }

                //Max Value
                foreach($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                    'x__next' => $focus_i['i__id'],
                    'x__following' => 31801, //Max Value
                )) as $num_steps){
                    if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                        $input_attributes .= ' max="'.$num_steps['x__message'].'" ';
                    }
                }

            } elseif($focus_i['i__type']==30350){

                $input_type = (count($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
                    'x__next' => $focus_i['i__id'],
                    'x__following' => 32442, //Select Time
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
        if (count($x_completes)) {
            echo '<div class="file_save_result greybg">';
            echo view_sync_links($x_completes[0]['x__message']);
            echo '</div>';
        } else {
            //for when added:
            echo '<div class="file_save_result center"></div>';
        }

        //UPLOAD BUTTON:
        echo '<div class="select-btns"><label class="btn btn-6255 inline-block" style="margin-left:5px;">' . $e___11035[13572]['m__cover'] . ' ' . $e___11035[13572]['m__title'] . '</label></div>';

    }

    $buttons_found = 0;
    $buttons_ui = '';
    $control_btn = '';

    foreach($this->config->item('e___13289') as $x__type => $m2) {

        if($x__type==13495 && count($x_selects)){

            //Edit response:
            $control_btn = '<div style="padding-left: 8px;" class="save_toggle_answer"><a class="controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="$(\'.save_toggle_answer\').toggleClass(\'hidden\');">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';

            $control_btn .= '<div style="padding-left: 8px;" class="save_toggle_answer hidden"><a class="controller-nav round-btn main-next" href="javascript:void(0);" onclick="$(\'.save_toggle_answer\').toggleClass(\'hidden\');">'.$e___11035[40639]['m__cover'].'</a><span class="nav-title main__title">'.$e___11035[40639]['m__title'].'</span></div>';

        } elseif($x__type==14422 && $top_completed && in_array($focus_i['i__type'], $this->config->item('n___34849'))){

            //Save Response
            $control_btn = '<div style="padding-left: 8px;"><a class="controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="go_next()">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';

        } elseif($x__type==31022 && $can_skip && !$top_completed && !count($x_completes)){

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
//Append add new idea as a comment button at the end:
//$body_prepend = '<a href="javascript:void(0);" onclick="i_editor_load(0,0,30901,'.$focus_i['i__id'].')" style="margin-left: 0;">'.$e___11035[31772]['m__cover'].' '.$e___11035[31772]['m__title'].'</a></td>'; //TODO fix icon reference
$body_prepend = '';
if(!($top_i__hashtag && $is_or_7712)){
    echo view_i_list(6255, $top_i__hashtag, $focus_i, $is_next, $member_e, $body_prepend);
}



if($top_i__hashtag && !$top_completed) {
    echo '<div style="padding: 0 5px;"><div class="progress">
<div class="progress-bar bg6255" role="progressbar" data-toggle="tooltip" data-placement="top" title="'.$tree_progress['fixed_discovered'].'/'.$tree_progress['fixed_total'].' Ideas Discovered '.$tree_progress['fixed_completed_percentage'].'%" style="width: '.$tree_progress['fixed_completed_percentage'].'%" aria-valuenow="'.$tree_progress['fixed_completed_percentage'].'" aria-valuemin="0" aria-valuemax="100"></div>
</div></div>';
} elseif(!$top_i__hashtag && $pathways_count > 0){
    echo view_headline(40798, $pathways_count, $e___11035[40798], $pathways, true);
}

if($top_i__hashtag){
    echo '</div>';
}


?>

<script>
    var focus_i__type = <?= $focus_i['i__type'] ?>;
    var can_skip = <?= intval($can_skip) ?>;
</script>
<input type="hidden" id="focus_card" value="12273" />
<input type="hidden" id="focus_id" value="<?= $focus_i['i__id'] ?>" />
<input type="hidden" id="focus_handle" value="<?= $focus_i['i__hashtag'] ?>" />
<input type="hidden" id="top_i__id" value="<?= $top_i__id ?>" />
<input type="hidden" id="top_i__hashtag" value="<?= $top_i__hashtag ?>" />
<input type="hidden" id="go_next_url" value="<?= $go_next_url ?>" />


<script>

    $(document).ready(function () {

        show_more(<?= $focus_i['i__id'] ?>);

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
                return x_read_only_complete();

            } else {

                //Go Next:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin"></i>');
                js_redirect(go_next_url);

            }
        }
    }

    function x_upload() {

        $.post("/ajax/x_upload", {
            i__id:         fetch_int_val('#focus_id'),
            top_i__id:     $('#top_i__id').val()
        }, function (data) {
            if(data.status){

            }
        });
        $('.file_save_result').html('<span class="icon-block-xx"><i class="far fa-yin-yang fa-spin"></i></span><span class="main__title">UPLOADING</span>');

    }


    function x_write(){
        $.post("/ajax/x_write", {
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




    function x_read_only_complete(){
        $.post("/ajax/x_read_only_complete", {
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
            alert('You cannot skip this');
            return false;
        }

        $.post("/ajax/x_skip", {
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
        $.post("/ajax/x_free_ticket", {
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
        $.post("/ajax/x_select", {
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