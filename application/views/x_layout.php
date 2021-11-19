<?php

$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$e___4737 = $this->config->item('e___4737'); //Idea Types



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

?>
    <script>
        var focus_i__type = <?= $i_focus['i__type'] ?>;
    </script>

    <input type="hidden" id="focus__type" value="12273" />
    <input type="hidden" id="focus__id" value="<?= $i_focus['i__id'] ?>" />
    <input type="hidden" id="top_i__id" value="<?= $top_i__id ?>" />
    <input type="hidden" id="click_count" value="0" />
    <input type="hidden" id="go_next_url" value="<?= $go_next_url ?>" />
    <input type="hidden" id="must_click" value="<?= ( count($x_completes) ? 0 : count($this->X_model->fetch(array(
        'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
        'x__up' => 27178, //Require Link Click
        'x__right' => $i_focus['i__id'],
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    )))) ?>" />

    <script src="/application/views/x_layout.js?v=<?= view_memory(6404,11060) ?>" type="text/javascript"></script>

<?php

//PREVIOUS DISCOVERIES
if($top_i__id){

    $previous_x = $this->X_model->find_previous($member_e['e__id'], $top_i__id, $i_focus['i__id']);
    if(count($previous_x)){

        $body = '<div class="row justify-content-center list_26000">';
        foreach($previous_x as $count => $sitemap_i){
            $body .= view_i(14450, $top_i__id, null, $sitemap_i);
        }
        $body .= '</div>';

        echo view_headline(26000, null, $e___11035[26000], $body, false);

    }
}



echo '<div class="row justify-content-center">';
echo view_i(20417, $top_i__id, null, $i_focus);
echo '</div>';



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
            $select_answer = '<div class="row justify-content-center list-answers" i__type="' . $i_focus['i__type'] . '">';

            //List children to choose from:
            foreach ($is_next as $key => $next_i) {

                //Any Inclusion Any Requirements?
                $fetch_13865 = $this->X_model->fetch(array(
                    'x__right' => $next_i['i__id'],
                    'x__type' => 13865, //Must Include Any
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
                ), array('x__up'), 0);
                if(count($fetch_13865)){
                    //Let's see if they meet any of these PREREQUISITES:
                    $meets_inc1_prereq = false;
                    if($x__source > 0){
                        foreach($fetch_13865 as $e_pre){
                            if(( $member_e && $member_e['e__id']==$e_pre['x__up'] ) || count($this->X_model->fetch(array(
                                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                    'x__up' => $e_pre['x__up'],
                                    'x__down' => $x__source,
                                )))){
                                $meets_inc1_prereq = true;
                                break;
                            }
                        }
                    }
                    if(!$meets_inc1_prereq){
                        continue;
                    }
                }

                //Any Inclusion All Requirements?
                $fetch_27984 = $this->X_model->fetch(array(
                    'x__right' => $next_i['i__id'],
                    'x__type' => 27984, //Must Include All
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
                ), array('x__up'), 0);
                if(count($fetch_27984)){
                    //Let's see if they meet all of these PREREQUISITES:
                    $meets_inc2_prereq = 0;
                    if($x__source > 0){
                        foreach($fetch_27984 as $e_pre){
                            if(( $member_e && $member_e['e__id']==$e_pre['x__up'] ) || count($this->X_model->fetch(array(
                                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                    'x__up' => $e_pre['x__up'],
                                    'x__down' => $x__source,
                                )))){
                                $meets_inc2_prereq++;
                            }
                        }
                    }
                    if($meets_inc2_prereq < count($fetch_27984)){
                        //Did not meet all requirements:
                        continue;
                    }
                }

                //Any Exclusion All Requirements?
                $fetch_26600 = $this->X_model->fetch(array(
                    'x__right' => $next_i['i__id'],
                    'x__type' => 26600, //Must Exclude All
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
                ), array('x__up'), 0);
                if(count($fetch_26600)){
                    //Let's see if they meet any of these PREREQUISITES:
                    $excludes_all = false;
                    if($x__source > 0){
                        foreach($fetch_26600 as $e_pre){
                            if(( $member_e && $member_e['e__id']==$e_pre['x__up'] ) || count($this->X_model->fetch(array(
                                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                'x__up' => $e_pre['x__up'],
                                'x__down' => $x__source,
                            )))){
                                //Found an exclusion, so skip this:
                                $excludes_all = false;
                                break;
                            } else {
                                $excludes_all = true;
                            }
                        }
                    }

                    if(!$excludes_all){
                        continue;
                    }
                }


                //Any Limits on Selection?
                $spots_remaining = -1; //No limits
                $has_limits = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type' => 4983, //References
                    'x__right' => $next_i['i__id'],
                    'x__up' => 26189,
                ), array(), 1);
                if(count($has_limits) && is_numeric($has_limits[0]['x__message']) && intval($has_limits[0]['x__message'])>0){
                    //We have a limit! See if we've met it already:
                    $spots_remaining = intval($has_limits[0]['x__message'])-view_coins_i(6255,  $next_i, 0, false);
                    if($spots_remaining < 0){
                        $spots_remaining = 0;
                    }
                }


                //Has this been previously selected?
                $previously_selected = count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVERY EXPANSIONS
                    'x__left' => $i_focus['i__id'],
                    'x__right' => $next_i['i__id'],
                    'x__source' => $x__source,
                )));

                $select_answer .= view_i_select($next_i, $x__source, $previously_selected, $spots_remaining);

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

            $e___26661 = $this->config->item('e___26661');
            echo '<div class="msg alert alert-success" role="alert">We have received your payment of '.$e___26661[$x_completes[0]['x__up']]['m__title'].' '.$x_completes[0]['x__message'].'. You are ready to go next.</div>';

        } else {

            $e___26661 = $this->config->item('e___26661');
            echo '<div class="msg alert alert-warning" role="alert">';
                echo '<h2 style="color: #FF0000;">⚠️ How to Pay:</h2>';
                echo '<ol style="text-align: left;">';
                    echo '<li>Click next to Pay '.$total_dues[0]['x__message'].' via Paypal</li>';
                    echo '<li>You can checkout as a guest, No Paypal account needed</li>';
                    echo '<li>Once you completed your payment, you are <span style="color: #FF0000;">Not Done!</span> ⚠️ You should then click "<b>Return to Merchant</b>" to continue back here to finish your registation.</li>';
                echo '</ol>';
            echo '</div>';

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

        $message_ui .= '<script> $(document).ready(function () { set_autosize($(\'#x_reply\')); $(\'#x_reply\').focus(); }); </script>';

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
        echo '<div class="nav-controller select-btns"><a class="controller-nav btn btn-lrg btn-6255 go-next" href="javascript:void(0);" onclick="go_next()">'.$e___11035[$discovery_e]['m__title'].' '.$e___11035[$discovery_e]['m__cover'].'</a></div>';
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

                //Break down amount & currency
                $currency_parts = explode(' ',$total_dues[0]['x__message'],2);

                //Load Paypal Pay button:
                $control_btn = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_form" target="_top">';
                $control_btn .= '<input type="hidden" name="business" value="'.view_memory(6404,26595).'">';
                $control_btn .= '<input type="hidden" name="item_name" value="'.$i_focus['i__title'].'">';
                $control_btn .= '<input type="hidden" name="item_number" value="'.$top_i__id.'-'.$i_focus['i__id'].'-'.$detected_x_type['x__type'].'-'.$x__source.'">';
                $control_btn .= '<input type="hidden" name="currency_code" value="'.$currency_parts[0].'">';
                $control_btn .= '<input type="hidden" name="amount" value="'.$currency_parts[1].'">';
                $control_btn .= '<input type="hidden" name="no_shipping" value="1">';
                $control_btn .= '<input type="hidden" name="notify_url" value="https://mench.com/-26595">';
                $control_btn .= '<input type="hidden" name="cancel_return" value="https://'.get_domain('m__message').'/'.$top_i__id.'/'.$i_focus['i__id'].'?cancel_pay=1">';
                $control_btn .= '<input type="hidden" name="return" value="https://'.get_domain('m__message').'/'.$top_i__id.'/'.$i_focus['i__id'].'?process_pay=1">';
                $control_btn .= '<input type="hidden" name="cmd" value="_xclick">';

                $control_btn .= '<input type="submit" class="round-btn adj-btn" name="pay_now" id="pay_now" value="$"><span class="nav-title css__title">'.$e___4737[$i_focus['i__type']]['m__title'].' '.$total_dues[0]['x__message'].'</span>';
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

        $buttons_ui .= '<div>'.( $control_btn ? $control_btn : '&nbsp;' ).'</div>';

        if($control_btn){
            $buttons_found++;
        }

    }

    if($buttons_found > 0){
        echo '<div class="nav-controller margin-top-down">';
        echo $buttons_ui;
        echo '</div>';
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
    $comments = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'x__type' => 12419,
        'x__right' => $i_focus['i__id'],
    ), array('x__source'), view_memory(6404,11064), 0, array('x__spectrum' => 'ASC'));

    //For now we have comments completely hidden:
    $headline_ui = view_i_note_list(12419, true, $i_focus, $comments, true);
    echo view_headline(12419, count($comments), $e___11035[12419], $headline_ui, false);

} else {

    //NEXT IDEAS
    echo view_i_list(12211, $top_i__id, $i_focus, $is_next, $member_e);

}


?>