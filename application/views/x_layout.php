<?php

$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$e___4737 = $this->config->item('e___4737'); //Idea Types

$has_cover = strlen(trim($i_focus['i__cover'])) > 0;

$show_bg = count($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4983, //References
        'x__right' => $i_focus['i__id'],
        'x__up' => 28621,
    ), array(), 1)) && filter_var($i_focus['i__cover'], FILTER_VALIDATE_URL);
if($show_bg){
    echo '<style>
    body { 
    background: url("' . $i_focus['i__cover'] . '") no-repeat center center fixed !important; 
    background-size: cover !important;
    width: 100% !important;
    -webkit-background-size: cover !important;
    -moz-background-size: cover !important;
    -o-background-size: cover !important;
    top:0 !important;
      left:0 !important;
    height: 100% !important;
    }
    </style>';
}



//NEXT IDEAS
$is_next = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'x__left' => $i_focus['i__id'],
), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));


$x__source = ( $member_e ? $member_e['e__id'] : 0 );
$top_i__id = ( $i_top && $this->X_model->ids($x__source, $i_top['i__id']) ? $i_top['i__id'] : 0 );
$x_completes = ( $top_i__id ? $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVERY COMPLETE
    'x__source' => $x__source,
    'x__left' => $i_focus['i__id'],
)) : array() );
$in_my_discoveries = ( $top_i__id && $top_i__id==$i_focus['i__id'] );
$top_completed = false; //Assume main intent not yet completed, unless proven otherwise...
$i_type_meet_requirement = in_array($i_focus['i__type'], $this->config->item('n___7309'));
$is_discovarable = true;
$i_stats = i_stats($i_focus['i__metadata']);


//Check for time limits?
if($top_i__id && !count($x_completes) && $x__source){

    //See if any OR parents are completed with an expiry time:
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__type IN (' . join(',', $this->config->item('n___7712')) . ')' => null, //Select Next
        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
        'x__right' => $i_focus['i__id'],
    ), array('x__left')) as $parent_ors){

        $does_expire = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 4983, //References
            'x__up' => 28199,
            'x__right' => $parent_ors['i__id'],
        ));

        if(count($does_expire) && intval($does_expire[0]['x__message'])>0){

            //Fetch parent completion time:
            $answered = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //Discoveries
                'x__left' => $parent_ors['i__id'],
                'x__source' => $x__source,
            ));

            if(count($answered)){

                //Display count down timer:
                echo '<script>
// Set the date were counting down to
var countDownDate = new Date('.( ( strtotime($answered[0]['x__time'] ) + $does_expire[0]['x__message'] ) * 1000 ).' );

// Update the count down every 1 second
var x = setInterval(function() {

  // Get todays date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="timexpirycount"
  document.getElementById("timexpirycount").innerHTML = ( days>0 ? days + "d " : "" ) + ( hours>0 ? hours + "h " : "")
  + ( minutes>0 ? minutes + "m " : "" ) + seconds + "s";

  // If the count down is finished, write some text
  if (distance <= 1) {
    clearInterval(x);
    //Redirect to delete the discovery:
    window.location = "/-28199?i__id='.$parent_ors['i__id'].'&top_i__id='.$top_i__id.'";
  }
  
}, 1000);
</script>';

                break; //Cannot have multiple countdowns

            } else {

                //Already expired:
                js_redirect('/'.$top_i__id.'/'.$parent_ors['i__id'], 0);

            }
        }
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

                //No path found:
                array_push($x_completes, $this->X_model->mark_complete($top_i__id, $i_focus, array(
                    'x__type' => 7492, //TERMINATE
                    'x__source' => $x__source,
                )));

            }
        }
    }

    $go_next_url = ( $top_completed ? '/x/x_completed_next/' : '/x/x_next/' ) . $top_i__id . '/' . $i_focus['i__id'];

} else {

    $go_next_url = '/x/x_start/'.$i_focus['i__id'];

}




echo '<div class="light-bg large-frame '.( $show_bg ? ' with-background ' : '' ).'">';

//Show Progress:
if($top_i__id > 0 && $i_completion_rate['completion_percentage']>=100){
    echo '<div class="msg alert alert-success" role="alert"><span class="icon-block">✅</span>100% Completed: You Are Now Reviewing Your Responses</div>';
}

echo '<h1 class="msg-frame" style="text-align: left; padding: 10px 0 !important; font-size:2.1em;">'.( !$show_bg && strlen($i_focus['i__cover'])  ? '<div style="padding:8px 0;"><span class="icon-block-img hideIfEmpty">'.view_cover(12273,$i_focus['i__cover']).'</span></div>' : '' ).$i_focus['i__title'].'</h1>';

//echo view_i(20417, $top_i__id, null, $i_focus);

//MESSAGES
foreach($this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 4231, //IDEA NOTES Messages
    'x__right' => $i_focus['i__id'],
), array(), 0, 0, array('x__spectrum' => 'ASC')) as $message_x) {
    echo $this->X_model->message_view(
        $message_x['x__message'],
        true,
        $member_e
    );
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

    } elseif (in_array($i_focus['i__type'], $this->config->item('n___7712'))) {

        //SELECT ANSWER

        //Has no children:
        if (!count($is_next)) {

            //Mark this as complete since there is no child to choose from:
            if (!count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVERY COMPLETE
                'x__source' => $x__source,
                'x__left' => $i_focus['i__id'],
            )))) {

                array_push($x_completes, $this->X_model->mark_complete($top_i__id, $i_focus, array(
                    'x__type' => 4559, //DISCOVERY MESSAGES
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
                echo view_i_list(13980, $top_i__id, $i_focus, $x_selects, $member_e);
                echo '</div>';
            }



            //Open for list to be printed:
            $select_answer = '<div class="row list-answers" i__type="' . $i_focus['i__type'] . '">';

            //List children to choose from:
            foreach ($is_next as $key => $next_i) {

                //Make sure it meets the conditions:
                $i_is_available = i_is_available($next_i['i__id'], false);
                if(!$i_is_available['status']){
                    //This option is not available:
                    continue;
                }


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

    } elseif ($i_focus['i__type'] == 26560) {

        //Fetch Value
        $total_dues = $this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__up' => 26562, //Total Due
            'x__right' => $i_focus['i__id'],
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ));

        $valid_payment = false;
        if($x__source>0 && count($total_dues)){
            $detected_x_type = x_detect_type($total_dues[0]['x__message']);
            if ($detected_x_type['status'] && in_array($detected_x_type['x__type'], $this->config->item('n___26661'))){
                $valid_payment = true;
            }
        }

        //Break down amount & currency
        $currency_parts = explode(' ',$total_dues[0]['x__message'],2);


        if(isset($_GET['cancel_pay']) && !count($x_completes)){
            echo '<div class="msg alert alert-danger" role="alert">You cancelled your payment.</div>';
        }

        if(!$valid_payment){

            echo '<div class="msg alert alert-danger" role="alert">Error: Idea missing valid payment amount.</div>';

        } elseif(isset($_GET['process_pay']) && !count($x_completes)){

            echo '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Processing your payment, please wait...</div>';

            //Referesh soon so we can check if completed or not
            js_redirect('/'.$top_i__id.'/'.$i_focus['i__id'].'?process_pay=1', 2584);

        } elseif(count($x_completes)){

            $x__metadata = unserialize($x_completes[0]['x__metadata']);
            echo '<div class="msg alert alert-success" role="alert">A Paypal email confirmation has been sent to you which your proof of purchase. You paid '.$x__metadata['mc_currency'].' '.$x__metadata['mc_gross'].( $x__metadata['mc_currency']==$currency_parts[0] && $x__metadata['mc_gross'] < $currency_parts[1] ? ' (Saved '.number_format(($currency_parts[1] - $x__metadata['mc_gross']), 0).'!)' : '' ).' on '.$x__metadata['payment_date'].'. You can view your <a href="https://www.paypal.com/activity/payment/'.$x__metadata['txn_id'].'" target="_blank">Paypal Transaction</a>. You are ready to go next.</div>';

        } else {

            $e___26661 = $this->config->item('e___26661');
            echo '<div class="msg alert alert-warning" role="alert">';
                echo '<h2>How to Pay:</h2>';
                echo '<ul style="list-style: none;">';
                    echo '<li>1. After completing your payment click on "<b style="color: #FF0000;">Return to Merchant</b>" to continue back here.</li>';
            echo '<li>2. You can checkout as a guest, You do not need to create a Paypal account. You can pay with a credit or visa debit card.</li>';
            echo '<li>3. Complete payment within <span id="timexpirycount" class="hideIfEmpty"></span> to secure this spot.</li>';
                echo '</ul>';

                $items_available = 0;

                if(isset($_GET['quantity'])){
                    //Is multi selectable, allow show down for quantity:
                    echo '<div style="padding: 10px;">';
                    echo '<select id="quantity_selector" class="form-control border">';
                    foreach(array(1,2,3,4,5,6,7,8,9,10) as $quantity){
                        echo '<option value="'.$quantity.'" '.( isset($_GET['quantity']) ? $_GET['quantity']==$quantity : $quantity==1 ).'>'.$quantity.' Ticket'.( $quantity > 1 ? 's' : '' ).'</option>';
                    }
                    echo '</select>';
                    echo '</div>';
                }

            echo '</div>';
//Show expiry time if any:
            echo '';
        }

    } elseif ($i_focus['i__type'] == 6683) {

        //Write `skip` if you prefer not to answer...
        $message_ui = '<textarea class="border i_content padded x_input '.( count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 7545, //Profile Add
                'x__right' => $i_focus['i__id'],
                'x__up' => 4783, //Phone
            ))) ? ' phone_verify_4783 ' : '' ).'" placeholder="" id="x_reply">' . (count($x_completes) ? trim($x_completes[0]['x__message']) : '') . '</textarea>';

        if (count($x_completes)) {
            //Next Ideas:
            $message_ui .= view_i_list(12211, $top_i__id, $i_focus, $is_next, $member_e);
        }

        $message_ui .= '<script> $(document).ready(function () { set_autosize($(\'#x_reply\')); }); </script>';

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

    }

}






if(!$top_i__id){

    if(i_is_startable($i_focus)){
        $discovery_e = ( $is_discovarable ? 4235 : 14022 );

        //Get Started
        echo '<div class="nav-controller select-btns msg-frame"><a class="btn btn-lrg btn-6255 go-next" href="javascript:void(0);" onclick="go_next()">'.$e___11035[$discovery_e]['m__title'].' '.$e___11035[$discovery_e]['m__cover'].'</a></div>';
        echo '<div class="doclear">&nbsp;</div>';
    }

} else {

    $buttons_found = 0;
    $buttons_ui = '';

    foreach($this->config->item('e___13289') as $e__id => $m2) {


        $superpower_actives = array_intersect($this->config->item('n___10957'), $m2['m__profile']);
        if(count($superpower_actives) && !superpower_unlocked(end($superpower_actives))){
            continue;
        }

        $control_btn = '';

        if($e__id==13877 && $top_i__id && !$in_my_discoveries){

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

            if($i_focus['i__type'] == 26560 && !count($x_completes) && $valid_payment){

                $quantity = ( isset($_GET['quantity']) ? $_GET['quantity'] : 1 );

                //Break down amount & currency
                $currency_parts = explode(' ',$total_dues[0]['x__message'],2);

                //Load Paypal Pay button:
                $control_btn = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_form" target="_top">';
                $control_btn .= '<input type="hidden" name="business" value="'.view_memory(6404,26595).'">';
                $control_btn .= '<input type="hidden" name="item_name" value="'.$i_focus['i__title'].'">';
                $control_btn .= '<input type="hidden" name="item_number" value="'.$top_i__id.'-'.$i_focus['i__id'].'-'.$detected_x_type['x__type'].'-'.$x__source.'">';
                $control_btn .= '<input type="hidden" name="quantity" id="quantity" value="'.$quantity.'">';
                $control_btn .= '<input type="hidden" name="currency_code" value="'.$currency_parts[0].'">';
                $control_btn .= '<input type="hidden" name="amount" value="'.$currency_parts[1].'">';
                $control_btn .= '<input type="hidden" name="no_shipping" value="1">';
                $control_btn .= '<input type="hidden" name="notify_url" value="https://mench.com/-26595">';
                $control_btn .= '<input type="hidden" name="cancel_return" value="https://'.get_domain('m__message').'/'.$top_i__id.'/'.$i_focus['i__id'].'?cancel_pay=1">';
                $control_btn .= '<input type="hidden" name="return" value="https://'.get_domain('m__message').'/'.$top_i__id.'/'.$i_focus['i__id'].'?process_pay=1">';
                $control_btn .= '<input type="hidden" name="cmd" value="_xclick">';

                $control_btn .= '<input type="submit" class="round-btn adj-btn" name="pay_now" id="pay_now" value="$"><span class="nav-title css__title">Pay '.$total_dues[0]['x__message'].'</span>';
                //$control_btn .= '<a class="controller-nav round-btn go-next" href="javascript:void(0);" onclick="document.getElementById(\'paypal_form\').submit();">'.$e___4737[$i_focus['i__type']]['m__cover'].'</a><span class="nav-title css__title">'.$e___4737[$i_focus['i__type']]['m__title'].'</span>';

                $control_btn .= '</form>';

            } else {

                //NEXT
                $control_btn = '<a class="controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="go_next()">'.$m2['m__cover'].'</a>';
                $control_btn .= '<span class="nav-title css__title">'.( count($x_completes) ? 'Go Next' : $m2['m__title'] ).'<div class="extra_progress hideIfEmpty"></div></span>';

            }

        } elseif($e__id==26280){

            //PREVIOUS
            $control_btn = '<a class="controller-nav round-btn" href="javascript:void(0);" onclick="history.back()">'.$m2['m__cover'].'</a><span class="nav-title css__title">'.$m2['m__title'].'</span>';

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

    if (in_array($i_focus['i__type'], $this->config->item('n___4559'))) {
        //DISCOVERY ONLY
        echo view_i_list(12211, $top_i__id, $i_focus, $is_next, $member_e);
    }

    //DISCUSSIONS:
    /*
    $comments = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'x__type' => 12419,
        'x__right' => $i_focus['i__id'],
    ), array('x__source'), view_memory(6404,11064), 0, array('x__spectrum' => 'ASC'));
    $headline_ui = view_i_note_list(12419, true, $i_focus, $comments, true);
    echo view_headline(12419, count($comments), $e___11035[12419], $headline_ui, false);
    */

} else {

    //NEXT IDEAS
    echo view_i_list(12211, $top_i__id, $i_focus, $is_next, $member_e);

}


if($top_i__id > 0 && $i_completion_rate['completion_percentage']<100){
    echo '<p style="padding:10px;">'.$i_completion_rate['completion_percentage'].'% Completed</p>';
}

echo '</div>';






?>

<script>
    var focus_i__type = <?= $i_focus['i__type'] ?>;
</script>

<input type="hidden" id="focus__type" value="12273" />
<input type="hidden" id="focus__id" value="<?= $i_focus['i__id'] ?>" />
<input type="hidden" id="top_i__id" value="<?= $top_i__id ?>" />
<input type="hidden" id="click_count" value="0" />
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
        if(js_n___28015.includes(parseInt($('.list-answers').attr('i__type')))){
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

        //Keep track of message link clicks:
        $('.should-click').click(function(e) {
            $('#click_count').val(parseInt($('#click_count').val())+1);
        });

        set_autosize($('#x_reply'));

        //Watchout for file uplods:
        $('.boxUpload').find('input[type="file"]').change(function () {
            x_upload(droppedFiles, 'file');
        });

        $('#quantity_selector').change(function() {
            var new_quantity = $(this).val();
            console.log('Update quantity to '+new_quantity);
            $("#quantity").val(new_quantity);
            //TODO Update total cost
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
</script>

