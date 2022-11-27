<?php

$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$e___4737 = $this->config->item('e___4737'); //Idea Types
$is_or_idea = in_array($i_focus['i__type'], $this->config->item('n___7712'));

//Any Hard Redirects?
foreach($this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
    'x__right' => $i_focus['i__id'],
    'x__up' => 30811, //Hard Redirect
)) as $redirect){
    if(filter_var($redirect['x__message'], FILTER_VALIDATE_URL)){
        js_redirect($redirect['x__message'], 55);
        break;
    }
}

//NEXT IDEAS
$is_next = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'x__left' => $i_focus['i__id'],
), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));

//Filter Next Ideas:
$first_child = 0;
foreach($is_next as $in_key => $in_value){
    if(!$first_child && in_array($in_value['i__type'], $this->config->item('n___12330'))){
        $first_child = $in_value['i__id'];
    }
    $i_is_available = i_is_available($in_value['i__id'], false);
    if(!$i_is_available['status']){
        //Remove this option:
        unset($is_next[$in_key]);
    }
}



$i_focus['i__title'] = str_replace('"','',$i_focus['i__title']);
$x__source = ( $member_e ? $member_e['e__id'] : 0 );
$top_i__id = ( $i_top && $this->X_model->ids($x__source, $i_top['i__id']) ? $i_top['i__id'] : 0 );
$one_child_hack = ($first_child>0 && count($is_next)==1 && !$top_i__id);
$x_completes = ( $top_i__id ? $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
    'x__source' => $x__source,
    'x__left' => $i_focus['i__id'],
)) : array() );
$in_my_discoveries = ( $top_i__id && $top_i__id==$i_focus['i__id'] );
$top_completed = false; //Assume main intent not yet completed, unless proven otherwise...
$i_type_meet_requirement = in_array($i_focus['i__type'], $this->config->item('n___7309'));
$i_stats = i_stats($i_focus['i__metadata']);
$can_skip = count($this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
    'x__right' => $i_focus['i__id'],
    'x__up' => 28239, //Can Skip
)));

$is_payment = in_array($i_focus['i__type'] , $this->config->item('n___30469'));
$min_allowed = 1;
$detected_x_type = 0;





//Featured Sources:
$relevant_sources = '';
foreach($this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
    'x__right' => $i_focus['i__id'],
    'x__up > 0' => null,
    'x__up !=' => get_domain_setting(0),
), array('x__up'), 0, 0, array('e__title' => 'DESC')) as $x){

    //See if this member also follows this featured source?
    $member_follows = array();
    if($x__source>0){
        $member_follows = $this->X_model->fetch(array(
            'x__up' => $x['e__id'],
            'x__down' => $x__source,
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ));
    }

    $is_featured = in_array($x['e__type'], $this->config->item('n___30977'));
    if(!$is_featured && !count($member_follows)){
        continue;
    }

    $messages = '';
    foreach($member_follows as $member_follow){
        if(strlen($member_follow['x__message'])){
            $messages .= '<h2 title="Posted ' . $member_follow['x__time'] . '" style="padding:13px 0 0 40px;">' . $member_follow['x__message'] . '</h2>';
        }
    }

    if(!$is_featured && !$messages){
        continue;
    }


    $relevant_sources .= '<div class="source-info">';
    $relevant_sources .= '<span class="icon-block">'.view_cover(12274,$x['e__cover'], true) . '</span>';
    $relevant_sources .= '<span>'.$x['e__title'] . ( strlen($messages) ? ':' : '' ) . '</span>';
    $relevant_sources .= $messages;

    $relevant_sources .= '<div style="padding-top: 10px; padding-left: 41px; font-size:1.2em; font-weight: bold; line-height:120%;">'. ( $x['e__id']==30976 /* Hack: Location loads with Google Maps */ ? '<a href="https://www.google.com/maps/search/'.urlencode($x['x__message']).'" target="_blank" style="text-decoration:underline;">'.$x['x__message'].'</a>' : nl2br($x['x__message']) ) . '</div>';
    $relevant_sources .= '</div>';

}


if($is_payment){

    //Payment Settings:
    $total_dues = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
        'x__right' => $i_focus['i__id'],
        'x__up' => 26562, //Total Due
    ));

    if($x__source>0 && count($total_dues)){
        $detected_x_type = x_detect_type($total_dues[0]['x__message']);
        if ($detected_x_type['status'] && in_array($detected_x_type['x__type'], $this->config->item('n___26661'))){

            $digest_fees = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $i_focus['i__id'],
                'x__up' => 30589, //Digest Fees
            ));
            $cart_max = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $i_focus['i__id'],
                'x__up' => 29651, //Cart Max Quantity
            ));
            $cart_min = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $i_focus['i__id'],
                'x__up' => 31008, //Cart Min Quantity
            ));




            //Break down amount & currency
            $currency_parts = explode(' ',$total_dues[0]['x__message'],2);
            $unit_price = number_format($currency_parts[1], 2);
            $unit_fee = number_format($currency_parts[1] * ( count($digest_fees) ? 0 : (doubleval(get_domain_setting(30590, $x__source)) + doubleval(get_domain_setting(27017, $x__source)) + doubleval(get_domain_setting(30612, $x__source)))/100 ), 2);
            $unit_total = number_format($unit_fee+$currency_parts[1], 2);
            $max_allowed = ( count($cart_max) && is_numeric($cart_max[0]['x__message']) && $cart_max[0]['x__message']>1 ? intval($cart_max[0]['x__message']) : view_memory(6404,29651) );
            $spots_remaining = i_spots_remaining($i_focus['i__id']);
            $max_allowed = ( $spots_remaining>-1 && $spots_remaining<$max_allowed ? $spots_remaining : $max_allowed );
            $max_allowed = ( $max_allowed < 1 ? 1 : $max_allowed );
            $min_allowed = ( count($cart_min) && is_numeric($cart_min[0]['x__message']) && intval($cart_min[0]['x__message'])>0 ? intval($cart_min[0]['x__message']) : $min_allowed );
            $min_allowed = ( $min_allowed < 1 ? 1 : $min_allowed );

        } else {
            $is_payment = false;
        }
    } else {
        $is_payment = false;
    }
}

if($is_or_idea || count($x_completes)){
    $_GET['open'] = true;
}

//Check for time limits?
if($top_i__id && $x__source && $top_i__id!=$i_focus['i__id']){

    $find_previous = $this->X_model->find_previous($x__source, $top_i__id, $i_focus['i__id']);
    if(count($find_previous)){

        $nav_list = array();
        $main_branch = array(intval($i_focus['i__id']));
        foreach($find_previous as $parent_i){
            //First add-up the main branch:
            array_push($main_branch, intval($parent_i['i__id']));
        }

        echo '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
        foreach($find_previous as $parent_i){

            //Does this have a child list?
            $query_subset = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'x__left' => $parent_i['i__id'],
            ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));
            foreach($query_subset as $key=>$value){
                $i_is_available = i_is_available($value['i__id'], false);
                if(!$i_is_available['status'] || !count($this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'x__source' => $x__source,
                        'x__left' => $value['i__id'],
                    )))){
                    unset($query_subset[$key]);
                }
            }

            echo '<li class="breadcrumb-item">';
            echo '<a href="/'.$top_i__id.'/'.$parent_i['i__id'].'"><u>'.$parent_i['i__title'].'</u></a>';

            //Do we have more sub-items in this branch? Must have more than 1 to show, otherwise the 1 will be included in the main branch:
            if(count($query_subset) >= 2){
                //Show other branches:
                echo '<div class="dropdown inline-block">';
                echo '<button type="button" class="btn no-side-padding" id="dropdownMenuButton'.$parent_i['i__id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                echo '<span class="icon-block source_cover source_cover_mini"><i class="far fa-chevron-square-down"></i></span>';
                echo '</button>';
                echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$parent_i['i__id'].'">';
                foreach ($query_subset as $i_subset) {
                    echo '<a href="/'.$top_i__id.'/'.$i_subset['i__id'].'" class="dropdown-item css__title '.( in_array($i_subset['i__id'], $main_branch) ? ' active ' : '' ).'">'.$i_subset['i__title'].'</a>';
                }
                echo '</div>';
                echo '</div>';
            }

            echo '</li>';
        }
        echo '</ol></nav>';

    }

}


if($top_i__id){

    $is_this = $this->I_model->fetch(array(
        'i__id' => $top_i__id,
    ));
    $i_completion_rate = $this->X_model->completion_progress($x__source, $is_this[0]);
    $top_completed = $i_completion_rate['completion_percentage'] >= 100;



    if($i_type_meet_requirement){

        //Reverse check answers to see if they have previously unlocked a path:
        $unlocked_connections = $this->X_model->fetch(array(
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVERY EXPANSIONS
            'x__right' => $i_focus['i__id'],
            'x__source' => $x__source,
        ), array('x__left'), 1);

        if(count($unlocked_connections) > 0){

            //They previously have unlocked a path here!

            //Determine DISCOVERY COIN type based on it's connection type's parents that will hold the appropriate read coin.
            $x_completion_type_id = 0;
            foreach($this->config->item('e___12327') /* DISCOVERY UNLOCKS */ as $e__id => $m2){
                if(in_array($unlocked_connections[0]['x__type'], $m2['m__profile'])){
                    $x_completion_type_id = $e__id;
                    break;
                }
            }

            //Could we determine the coin type?
            if($x_completion_type_id > 0){

                //Yes, Issue coin:
                array_push($x_completes, $this->X_model->mark_complete($top_i__id, $i_focus, array(
                    'x__type' => $x_completion_type_id,
                    'x__source' => $x__source,
                )));

            } else {

                //Oooops, we could not find it, report bug:
                $this->X_model->create(array(
                    'x__type' => 4246, //Platform Bug Reports
                    'x__source' => $x__source,
                    'x__message' => 'x_layout() found idea connector ['.$unlocked_connections[0]['x__type'].'] without a valid unlock method @12327',
                    'x__left' => $i_focus['i__id'],
                    'x__reference' => $unlocked_connections[0]['x__id'],
                ));

            }

        } else {

            //Try to find paths to unlock:
            $unlock_paths = $this->I_model->unlock_paths($i_focus);

            //Set completion method:
            if(!count($unlock_paths)){

                $this->X_model->create(array(
                    'x__type' => 4246, //Platform Bug Reports
                    'x__source' => $x__source,
                    'x__left' => $i_focus['i__id'],
                    'x__message' => 'unlock_paths() Failed to find a path',
                ));

            }
        }
    }

    $go_next_url = ( $top_completed ? '/x/x_completed_next/' : '/x/x_next/' ) . $top_i__id . '/' . $i_focus['i__id'];

} else {

    $go_next_url = '/x/x_start/'.$i_focus['i__id'];

}





echo '<div class="light-bg large-frame">';

//Show Progress:
if($top_completed){
    //echo '<script> $(document).ready(function () { $(".go-next-group").addClass(\'hidden\'); }); </script>';
    echo '<div class="msg alert alert-success" role="alert"><span class="icon-block">✅</span>100% Completed: You Are Now Reviewing Your Responses</div>';
}

//MESSAGES
$messages_string = false;
foreach($this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 4231, //IDEA NOTES Messages
    'x__right' => $i_focus['i__id'],
), array(), 0, 0, array('x__spectrum' => 'ASC')) as $message_x) {
    $messages_string .= $this->X_model->message_view(
        $message_x['x__message'],
        true,
        $member_e
    );
}








//$one_child_hack Get the message for the single child, if any:
if($one_child_hack){
    echo '<h3 class="msg-frame" style="text-align: left; padding: 10px 0 0 !important;">'.$i_focus['i__title'].'</h3>';
    $messages_string .= '<h1 class="msg-frame" style="text-align: left; padding: 0 0 10px !important; font-size:2.5em;">'.$is_next[0]['i__title'].'</h1>';
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $first_child,
    ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $message_x) {
        $messages_string .= $this->X_model->message_view(
            $message_x['x__message'],
            true,
            $member_e
        );
    }
} else {
    echo '<h1 class="msg-frame" style="text-align: left; padding: 10px 0 !important; font-size:2.5em;">'.$i_focus['i__title'].'</h1>';
}


if($messages_string){
    echo $messages_string;
} elseif(!count($x_completes) && in_array($i_focus['i__type'], $this->config->item('n___12330'))) {
    //Auto complete:
    echo '<script> $(document).ready(function () { go_next() }); </script>';
}


if(strlen($relevant_sources)){
    echo '<div class="source-featured">';
    echo $relevant_sources;
    echo '</div>';
}


if($top_i__id) {
    //LOCKED
    if ($i_type_meet_requirement) {

        //Requirement lock
        if (!count($x_completes) && !count($unlocked_connections) && count($unlock_paths)) {

            //List Unlock paths:
            echo view_i_list(13979, $top_i__id, $i_focus, $unlock_paths, $member_e);

        }

        //List Children if any:
        echo view_i_list(12211, $top_i__id, $i_focus, $is_next, $member_e);

    } elseif ($is_or_idea) {

        //SELECT ANSWER

        //Has no children:
        if (!count($is_next)) {

            //Mark this as complete since there is no child to choose from:
            if (!count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__source' => $x__source,
                'x__left' => $i_focus['i__id'],
            )))) {

                array_push($x_completes, $this->X_model->mark_complete($top_i__id, $i_focus, array(
                    'x__type' => 4559, //READ STATEMENT
                    'x__source' => $x__source,
                )));

            }

        } else {

            //First fetch answers based on correct order:
            $x_selects = array();
            foreach ($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'x__left' => $i_focus['i__id'],
            ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $x) {
                //See if this answer was seleted:
                if (count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVERY IDEA LINK
                    'x__left' => $i_focus['i__id'],
                    'x__right' => $x['i__id'],
                    'x__source' => $x__source,
                )))) {
                    array_push($x_selects, $x);
                }
            }

            if (count($x_selects) > 0) {
                //MODIFY ANSWER
                echo '<div class="edit_toggle_answer">';

                //Edit response:
                echo '<div class="select-btns"><a class="btn btn-6255" href="javascript:void(0);" onclick="$(\'.edit_toggle_answer\').toggleClass(\'hidden\');$(\'.go-next-group\').removeClass(\'hidden\');">' . $e___11035[13495]['m__cover'] . ' ' . $e___11035[13495]['m__title'] . '</a></div>';

                echo view_i_list(13980, $top_i__id, $i_focus, $x_selects, $member_e);
                echo '</div>';

            }



            //Open for list to be printed:
            $select_answer = '<div class="row list-answers" i__type="' . $i_focus['i__type'] . '">';

            //List children to choose from:
            foreach ($is_next as $key => $next_i) {

                //Make sure it meets the conditions:
               // $i_is_available = i_is_available($next_i['i__id'], false);
               // if(!$i_is_available['status']){
                    //This option is not available:
                    //continue;
                //}


                //Has this been previously selected?
                $previously_selected = count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVERY EXPANSIONS
                    'x__left' => $i_focus['i__id'],
                    'x__right' => $next_i['i__id'],
                    'x__source' => $x__source,
                )));

                $select_answer .= view_i_select($next_i, $x__source, $previously_selected);

            }


            $select_answer .= '</div>';


            if (count($x_selects) > 0) {

                //Save Answers:
                $select_answer .= '<div class="select-btns"><a class="btn btn-6255" href="javascript:void(0);" onclick="$(\'.edit_toggle_answer\').toggleClass(\'hidden\');" title="' . $e___11035[13502]['m__title'] . '">' . $e___11035[13502]['m__cover'] . '</a>&nbsp;&nbsp;<a class="btn btn-6255" href="javascript:void(0);" onclick="x_select(\'/x/x_next/' . $top_i__id . '/' . $i_focus['i__id'] . '\')">' . $e___11035[13524]['m__title'] . ' ' . $e___11035[13524]['m__cover'] . '</a></div>';

            }

            //HTML:
            echo '<div class="edit_toggle_answer ' . (count($x_selects) > 0 ? 'hidden' : '') . '">';
            echo view_headline($i_focus['i__type'], null, $e___4737[$i_focus['i__type']], $select_answer, true);
            echo '</div>';

        }

    } elseif ($i_focus['i__type']==30350) {

        //Set Date

    } elseif ($i_focus['i__type']==30874) {

        //Event

    } elseif ($is_payment) {

        if(isset($_GET['cancel_pay']) && !count($x_completes)){
            echo '<div class="msg alert alert-danger" role="alert">You cancelled your payment.</div>';
        }

        if(isset($_GET['process_pay']) && !count($x_completes)){

            echo '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Processing your payment, please wait...</div>';

            //Referesh soon so we can check if completed or not
            js_redirect('/'.$top_i__id.'/'.$i_focus['i__id'].'?process_pay=1', 2584);

        } elseif(count($x_completes)){

            $x__metadata = unserialize($x_completes[0]['x__metadata']);
            echo '<div class="msg alert alert-success" role="alert">Paypal receipt email sent for your payment of '.$x__metadata['mc_currency'].' '.$x__metadata['mc_gross'].( $x__metadata['quantity']>1 ? ' for '.$x__metadata['quantity'].' items' : '' ).' on '.$x__metadata['payment_date'].'. You are now ready to go next.</div>';

            //Invite Your Friends (If 2 or more items):
            if($x__metadata['quantity']>1 && is_new()){

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
                echo '<textarea class="border i_content padded x_input" placeholder="" id="invite_message"></textarea>';
                echo '<script> $(document).ready(function () { set_autosize($(\'#invite_message\')); }); </script>';

            }

        } else {

            //Is multi selectable, allow show down for quantity:
            echo '<div class="msg alert alert-warning table_checkout" role="alert">';
            echo '<table class="table table-condensed">';


            if($unit_fee > 0){
                echo '<tr>';
                echo '<td class="table-btn first_btn" style="text-align: right;">Price:&nbsp;&nbsp;</td>';
                echo '<td class="table-btn first_btn">'.$unit_price.' '.$currency_parts[0].'</td>';
                echo '</tr>';

                echo '<tr>';
                echo '<td class="table-btn first_btn" style="text-align: right;">Fee:&nbsp;&nbsp;</td>';
                echo '<td class="table-btn first_btn" title="'.(doubleval(get_domain_setting(30590, $x__source)) .' / '. doubleval(get_domain_setting(27017, $x__source)) .' / '. doubleval(get_domain_setting(30612, $x__source))).'">'.$unit_fee.' '.$currency_parts[0].'</td>';
                echo '</tr>';
            }


            if($max_allowed > 1){

                echo '<tr>';
                echo '<td class="table-btn first_btn" style="text-align: right;">Quantity:&nbsp;&nbsp;</td>';
                echo '<td class="table-btn first_btn sale_price_ui">';
                echo '<a href="javascript:void(0);" onclick="sale_increment(-1)"><i class="fas fa-minus-circle"></i></a>';
                echo '<span id="current_sales" class="css__title" style="display: inline-block; min-width:34px; text-align: center;">'.$min_allowed.'</span>';
                echo '<a href="javascript:void(0);" onclick="sale_increment(1)"><i class="fas fa-plus-circle"></i></a>';
                echo '</td>';
                echo '</tr>';

            }

            echo '<tr>';
            echo '<td class="table-btn first_btn" style="text-align: right;  width:34% !important;">Total:&nbsp;&nbsp;</td>';
            echo '<td class="table-btn first_btn" style="width:66% !important;"><span class="total_ui css__title">'.$unit_total.'</span> '.$currency_parts[0].'</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<td class="table-btn first_btn" style="text-align: right;">Delivery:&nbsp;&nbsp;</td>';
            echo '<td class="table-btn first_btn"><span data-toggle="tooltip" data-placement="top" title="Your tickets will be waiting for you at the venus pick-up location on the day of the event. Please bring photo ID and the card used for purchase. We *do not* email PDF Tickets or bar codes." style="border-bottom: 1px dotted #999;">Will Call (Free) <i class="fas fa-info-circle" style="font-size: 0.8em !important;"></i></span></td>';
            echo '</tr>';

            echo '</table>';


            echo '<div class="sub_note css__title">Note:</div>';
            if(!count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $i_focus['i__id'],
                'x__up' => 30615, //Is Refundable
            )))){
                echo '<div class="sub_note">* Final Sale: No Refunds or Transfers</div>';
            }

            echo '<div class="sub_note">* By purchasing a ticket you agree to our <a href="/-14373" target="_blank"><u>Terms of Use</u></a></div>';
            echo '<div class="sub_note">* No need to create a Paypal account: You can checkout as a guest</div>';
            echo '<div class="sub_note">* Once you paid, click on "Return to Merchant" to continue back here</div>';

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

    } elseif ($i_focus['i__type'] == 6683) {

        //Do we have a response?
        $previous_response = (count($x_completes) ? trim($x_completes[0]['x__message']) : false );
        if(!$previous_response && $x__source){
            //Does this have any append sources?
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 7545, //Profile Add
                'x__right' => $i_focus['i__id'],
            )) as $append_source){
                //Does the user have this source with any values?
                foreach($this->X_model->fetch(array(
                    'x__up' => $append_source['x__up'],
                    'x__down' => $x__source,
                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                ), array(), 0, 0) as $profile_appended) {
                    if(strlen($profile_appended['x__message'])){
                        $previous_response = $profile_appended['x__message'];
                    }
                    if(strlen($previous_response)){
                        break;
                    }
                }
                if(strlen($previous_response)){
                    break;
                }
            }
        }


        $message_ui = '<textarea class="border i_content padded x_input" placeholder="" id="x_reply">' . $previous_response . '</textarea>';

        if (count($x_completes)) {
            //Next Ideas:
            $message_ui .= view_i_list(12211, $top_i__id, $i_focus, $is_next, $member_e);
        }

        $message_ui .= '<script> $(document).ready(function () { set_autosize($(\'#x_reply\')); $(\'#x_reply\').focus(); $(window).scrollTop(0); }); </script>';

        echo view_headline(13980, null, $e___11035[13980], $message_ui, true);

    } elseif ($i_focus['i__type'] == 7637) {

        //FILE UPLOAD
        echo '<div class="userUploader">';
        echo '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

        echo '<input class="inputfile" type="file" name="file" id="fileType' . $i_focus['i__type'] . '" />';

        if (count($x_completes)) {

            echo '<div class="file_saving_result">';
            echo view_headline(13977, null, $e___11035[13977], $this->X_model->message_view($x_completes[0]['x__message'], true), true);
            echo '</div>';

            //Any child ideas?
            echo view_i_list(12211, $top_i__id, $i_focus, $is_next, $member_e);

        } else {

            //for when added:
            echo '<div class="file_saving_result center"></div>';

        }

        //UPLOAD BUTTON:
        echo '<div class="select-btns"><label class="btn btn-6255 inline-block" for="fileType' . $i_focus['i__type'] . '" style="margin-left:5px;">' . $e___11035[13572]['m__cover'] . ' ' . $e___11035[13572]['m__title'] . '</label></div>';


        echo '<div class="doclear">&nbsp;</div>';
        echo '</form>';
        echo '</div>';

    } else {

        //echo '<div class="msg alert alert-danger" role="alert">Error: Missing core variables.</div>';

    }

}






if(!$top_i__id){

    //Get Started
    echo '<div class="nav-controller select-btns msg-frame"><a class="btn btn-lrg btn-6255 go-next" href="javascript:void(0);" onclick="go_next()">'.$e___11035[4235]['m__title'].' '.$e___11035[4235]['m__cover'].'</a></div>';
    echo '<div class="doclear">&nbsp;</div>';

} else {

    $buttons_found = 0;
    $buttons_ui = '';

    foreach($this->config->item('e___13289') as $e__id => $m2) {

        $superpower_actives = array_intersect($this->config->item('n___10957'), $m2['m__profile']);
        if(count($superpower_actives) && !superpower_unlocked(end($superpower_actives))){
            continue;
        }

        $control_btn = '';

        if($e__id==13877 && $top_i__id && !$in_my_discoveries && !$is_payment){

            //Is Saved already by this member?
            $is_saves = $this->X_model->fetch(array(
                'x__up' => $x__source,
                'x__right' => $i_focus['i__id'],
                'x__type' => 12896, //SAVED
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ));

            $control_btn = '<a class="round-btn save_controller" href="javascript:void(0);" onclick="x_save('.$i_focus['i__id'].')" current_x_id="'.( count($is_saves) ? $is_saves[0]['x__id'] : '0' ).'"><span class="controller-nav toggle_saved '.( count($is_saves) ? '' : 'hidden' ).'">'.$e___11035[12896]['m__cover'].'</span><span class="controller-nav toggle_saved '.( count($is_saves) ? 'hidden' : '' ).'">'.$e___11035[13877]['m__cover'].'</span></a><span class="nav-title css__title">'.$m2['m__title'].'</span>';

        } elseif($e__id==12211){

            $control_btn = null;

            $paypal_email =  get_domain_setting(30882);
            if($is_payment && !count($x_completes) && filter_var($paypal_email, FILTER_VALIDATE_EMAIL)){

                $control_btn = '';

                //Load Paypal Pay button:
                $control_btn .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_form" target="_top">';

                $control_btn .= '<input type="hidden" id="paypal_handling" name="handling" value="'.$unit_fee.'">';
                $control_btn .= '<input type="hidden" id="paypal_quantity" name="quantity" value="'.$min_allowed.'">'; //Dynamic Variable
                $control_btn .= '<input type="hidden" id="paypal_item_name" name="item_name" value="'.$i_focus['i__title'].'">';
                $control_btn .= '<input type="hidden" id="paypal_item_number" name="item_number" value="'.$top_i__id.'-'.$i_focus['i__id'].'-'.$detected_x_type['x__type'].'-'.$x__source.'">';

                $control_btn .= '<input type="hidden" name="amount" value="'.$unit_price.'">';
                $control_btn .= '<input type="hidden" name="currency_code" value="'.$currency_parts[0].'">';
                $control_btn .= '<input type="hidden" name="no_shipping" value="1">';
                $control_btn .= '<input type="hidden" name="notify_url" value="https://mench.com/-26595">';
                $control_btn .= '<input type="hidden" name="cancel_return" value="https://'.get_domain('m__message').'/'.$top_i__id.'/'.$i_focus['i__id'].'?cancel_pay=1">';
                $control_btn .= '<input type="hidden" name="return" value="https://'.get_domain('m__message').'/'.$top_i__id.'/'.$i_focus['i__id'].'?process_pay=1">';
                $control_btn .= '<input type="hidden" name="cmd" value="_xclick">';
                $control_btn .= '<input type="hidden" name="business" value="'.$paypal_email.'">';

                $control_btn .= '<input type="submit" class="round-btn adj-btn go-next-group" name="pay_now" id="pay_now" value=">" onclick="$(\'.process-btn\').html(\'Loading...\');$(\'#pay_now\').val(\'...\');">';
                $control_btn .= '<span class="nav-title css__title process-btn">Pay Now</span>';

                //$control_btn .= '<a class="controller-nav round-btn go-next" href="javascript:void(0);" onclick="document.getElementById(\'paypal_form\').submit();">'.$e___4737[$i_focus['i__type']]['m__cover'].'</a><span class="nav-title css__title">'.$e___4737[$i_focus['i__type']]['m__title'].'</span>';

                $control_btn .= '</form>';

            } else {

                //NEXT
                $control_btn = '<a class="go-next-group controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="go_next()">'.$m2['m__cover'].'</a>';
                $control_btn .= '<span class="go-next-group nav-title css__title">'.( count($x_completes) ? 'Go Next' : $m2['m__title'] ).'<div class="extra_progress hideIfEmpty"></div></span>';

            }

        } elseif($e__id==26280){

            //PREVIOUS
            $control_btn = '<a class="controller-nav round-btn go-next-group" href="javascript:void(0);" onclick="history.back()">'.$m2['m__cover'].'</a><span class="nav-title css__title">'.$m2['m__title'].'</span>';

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







//IDAS
if($top_i__id) {

    //PREVIOUSLY UNLOCKED:
    $unlocked_x = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        'x__type' => 6140, //DISCOVERY UNLOCK LINK
        'x__source' => $x__source,
        'x__left' => $i_focus['i__id'],
    ), array('x__right'), 0);

    //Did we have any steps unlocked?
    if (count($unlocked_x) > 0) {
        echo view_i_list(13978, $top_i__id, $i_focus, $unlocked_x, $member_e);
    }


    /*
     *
     * IDEA TYPE INPUT CONTROLLER
     * Now let's show the appropriate
     * inputs that correspond to the
     * idea type that enable the member
     * to move forward.
     *
     * */

    if (in_array($i_focus['i__type'], $this->config->item('n___30646'))) {
        //DISCOVERY ONLY
        echo view_i_list(12211, $top_i__id, $i_focus, $is_next, $member_e);
    }

} elseif(!$one_child_hack) {

    //NEXT IDEAS
    echo view_i_list(12211, $top_i__id, $i_focus, $is_next, $member_e);

}


if($top_i__id > 0 && !$top_completed){
    echo '<p style="padding:10px;">'.$i_completion_rate['completion_percentage'].'% Completed</p>';
}



echo '</div>';



?>

<script>
    var focus_i__type = <?= $i_focus['i__type'] ?>;
    var can_skip = <?= intval($can_skip) ?>;
</script>

<input type="hidden" id="focus__type" value="12273" />
<input type="hidden" id="focus__id" value="<?= $i_focus['i__id'] ?>" />
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

        i_note_activate();
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
            if(i__type == 7231 || i__type == 14861){
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
        if(is_logged_in && focus_i__type==6683) {

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
            $('.go-next').html(( is_logged_in ? '<i class="fas fa-check-circle"></i>' : '<i class="far fa-yin-yang fa-spin"></i>' ));
            window.location = go_next_url;

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
            ajaxData.append('i__id', current_id());
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
                    lazy_load();
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


    function x_reply_save(go_next_url){
        $.post("/x/x_reply", {
            top_i__id:$('#top_i__id').val(),
            i__id:current_id(),
            x_reply:$('#x_reply').val(),
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="fas fa-check-circle"></i>');
                window.location = go_next_url;
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }

    function x_reply(go_next_url){
        x_reply_save(go_next_url);
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
            focus_i__type:focus_i__type,
            focus__id:current_id(),
            top_i__id:$('#top_i__id').val(),
            selection_i__id:selection_i__id,
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="fas fa-check-circle"></i>');
                window.location = go_next_url;
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }


</script>

