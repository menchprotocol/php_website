<script>
    var i_loaded_id = <?= $i_focus['i__id'] ?>;
</script>

<script src="/application/views/x/layout.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>


<?php

echo '<div class="container">';
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
$e___13291 = $this->config->item('e___13291'); //DISCOVER TABS

$i_type_meet_requirement = in_array($i_focus['i__type'], $this->config->item('n___7309'));
$recipient_e = superpower_assigned();
if(!isset($recipient_e['e__id']) ){
    $recipient_e['e__id'] = 0;
}


//NEXT IDEAS
$is_next = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'x__left' => $i_focus['i__id'],
), array('x__right'), 0, 0, array('x__sort' => 'ASC'));

$chapters = count($is_next);
$completion_rate['completion_percentage'] = 0;
$in_my_x = ( $recipient_e['e__id'] ? $this->X_model->i_home($i_focus['i__id'], $recipient_e) : false );


if($recipient_e['e__id']){

    //VIEW DISCOVER
    $this->X_model->create(array(
        'x__member' => $recipient_e['e__id'],
        'x__type' => 7610, //PLAYER VIEWED IDEA
        'x__left' => $i_focus['i__id'],
        'x__sort' => fetch_cookie_order('7610_'.$i_focus['i__id']),
    ));

    if ($in_my_x) {

        // % DONE
        $completion_rate = $this->X_model->completion_progress($recipient_e['e__id'], $i_focus);

        //Fetch progress history:
        $x_completes = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVER COMPLETE
            'x__member' => $recipient_e['e__id'],
            'x__left' => $i_focus['i__id'],
        ));


        if($i_type_meet_requirement){

            //Reverse check answers to see if they have previously unlocked a path:
            $unlocked_connections = $this->X_model->fetch(array(
                'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVER IDEA LINKS
                'x__right' => $i_focus['i__id'],
                'x__member' => $recipient_e['e__id'],
            ), array('x__left'), 1);

            if(count($unlocked_connections) > 0){

                //They previously have unlocked a path here!

                //Determine DISCOVER COIN type based on it's connection type's parents that will hold the appropriate discover coin.
                $x_completion_type_id = 0;
                foreach($this->config->item('e___12327') /* DISCOVER UNLOCKS */ as $e__id => $m){
                    if(in_array($unlocked_connections[0]['x__type'], $m['m_parents'])){
                        $x_completion_type_id = $e__id;
                        break;
                    }
                }

                //Could we determine the coin type?
                if($x_completion_type_id > 0){

                    //Yes, Issue coin:
                    array_push($x_completes, $this->X_model->mark_complete($i_focus, array(
                        'x__type' => $x_completion_type_id,
                        'x__member' => $recipient_e['e__id'],
                        'x__left' => $i_focus['i__id'],
                    )));

                } else {

                    //Oooops, we could not find it, report bug:
                    $this->X_model->create(array(
                        'x__type' => 4246, //Platform Bug Reports
                        'x__member' => $recipient_e['e__id'],
                        'x__message' => 'x_coin() found idea connector ['.$unlocked_connections[0]['x__type'].'] without a valid unlock method @12327',
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
                    array_push($x_completes, $this->X_model->mark_complete($i_focus, array(
                        'x__type' => 7492, //TERMINATE
                        'x__member' => $recipient_e['e__id'],
                        'x__left' => $i_focus['i__id'],
                    )));


                }
            }
        }


        //DISCOVERIES UI
        echo '<div class="hideIfEmpty focus_x_top"></div>';

        //DISCOVER PROGRESS ONLY AT TOP LEVEL
        if($completion_rate['completion_percentage']>0 && $completion_rate['completion_percentage']<100){
            echo '<div class="progress-bg-list no-horizonal-margin" title="discover '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)" data-toggle="tooltip" data-placement="bottom"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
        }

    }

}






if($recipient_e['e__id']==1){

    $column_width = number_format(100/count($this->config->item('n___13289')), 2);

    echo '<div class="container fixed-bottom">';
    echo '<div class="row">';
    echo '<table class="discover-controller"><tr>';

    foreach($this->config->item('e___13289') as $e__id => $m) {



        if($e__id==12896){

            //Is Saved?
            $is_saved = count($CI->X_model->fetch(array(
                'x__up' => $recipient_e['e__id'],
                'x__right' => $i_focus['i__id'],
                'x__type' => 12896, //SAVED
                'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            )));

            $url = '<a class="btn btn-x btn-circle" href="javascript:void(0);" onclick="i_save('.$i_focus['i__id'].')"><i class="fas fa-bookmark toggle_saved '.( $is_saved ? '' : 'hidden' ).'"></i><i class="fal fa-bookmark toggle_saved '.( $is_saved ? 'hidden' : '' ).'"></i></a>';

        } elseif($e__id==12991){

            $url = '<a class="btn btn-x btn-circle" href="'.( isset($_GET['previous_x']) && $_GET['previous_x']>0 ? '/'.$_GET['previous_x'] : '/x/x_previous/0/'.$i_focus['i__id'] ).'">'.$m['m_icon'].'</a>';

        } elseif($e__id==12991){

            $url = '<a class="btn btn-x btn-circle" href="'.( isset($_GET['previous_x']) && $_GET['previous_x']>0 ? '/'.$_GET['previous_x'] : '/x/x_previous/0/'.$i_focus['i__id'] ).'">'.$m['m_icon'].'</a>';

        } else {

            $url = '<a href="/">'.$m['m_icon'].'</a>';

        }

        echo '<td style="width:'.$column_width.'%;">'.$url.'</td>';
    }

    echo '</tr></table>';
    echo '</div>';
    echo '</div>';
}




//IDEA TITLE
echo '<h1 class="block-one"><span class="icon-block top-icon">'.view_x_icon_legend( $completion_rate['completion_percentage']>0 , $completion_rate['completion_percentage'] ).'</span><span class="title-block-lg">' . view_i_title($i_focus) . '</span></h1>';



//DISCOVER LAYOUT
$i_stats = i_stats($i_focus['i__metadata']);
$tab_group = 13291;
$tab_pills = '<ul class="nav nav-pills nav-sm">';
$tab_content = '';
$tab_pill_count = 0;

foreach($this->config->item('e___'.$tab_group) as $x__type => $m){

    $superpower_actives = array_intersect($this->config->item('n___10957'), $m['m_parents']);
    if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
        continue;
    }

    //Is this a caret menu?
    if(in_array(11040 , $m['m_parents'])){
        echo view_caret($x__type, $m, $i_focus['i__id']);
        continue;
    }

    $counter = null; //Assume no counters
    $this_tab = '';

    if($x__type==4231){

        //MESSAGES
        $messages = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $i_focus['i__id'],
        ), array(), 0, 0, array('x__sort' => 'ASC'));
        $counter = count($messages);

        $this_tab .= '<div style="margin-bottom:34px;">';
        if($counter){
            foreach($messages as $message_x) {
                $counter++;
                $this_tab .= $this->X_model->message_send(
                    $message_x['x__message'],
                    $recipient_e
                );
            }
        }
        $this_tab .= '</div>';

    } elseif($x__type==12273 && $i_stats['i___13443']>1){

        //IDEAS
        $counter = $i_stats['i___13443'];

        //IDEA or TIME difference?
        if($i_stats['i___6169']!=$i_stats['i___6170'] || $i_stats['i___6161']!=$i_stats['i___6162']){

            //Variable time range:
            $this_tab .= '<p class="space-content">The number of ideas you discover (and the time it takes to discover them) depends on the choices you make interactively along the way:</p>';
            $this_tab .= '<p class="space-content" style="margin-bottom:34px;">';
            $this_tab .= '<span class="discovering-paths">Minimum:</span>'.$e___13291[12273]['m_icon'].' <span class="discovering-count montserrat idea">'.$i_stats['i___6169'].'</span><span class="mono-space">'.view_time_hours($i_stats['i___6161']).'</span><br />';
            $this_tab .= '<span class="discovering-paths">Average:</span>'.$e___13291[12273]['m_icon'].' <span class="discovering-count montserrat idea">'.$i_stats['i___13443'].'</span><span class="mono-space">'.view_time_hours($i_stats['i___13292']).'</span><br />';
            $this_tab .= '<span class="discovering-paths">Maximum:</span>'.$e___13291[12273]['m_icon'].' <span class="discovering-count montserrat idea">'.$i_stats['i___6170'].'</span><span class="mono-space">'.view_time_hours($i_stats['i___6162']).'</span>';
            $this_tab .= '</p>';

        } else {

            //Single Time range:
            $this_tab .= '<p class="space-content" style="margin-bottom:34px;">It takes <span class="mono-space">'.view_time_hours($i_stats['i___13292']).'</span> to discover '.$counter.' idea'.view__s($counter).':</p>';

        }

        //NEXT IDEAS
        if(!$in_my_x && $chapters){
            $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
            foreach($is_next as $key => $next_i){
                $this_tab .= view_i_x($next_i, i_calc_common_prefix($is_next, 'i__title'));
            }
            $this_tab .= '</div>';
        }

        //IDEA PREVIOUS
        $is_previous = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__right' => $i_focus['i__id'],
            'x__left !=' => config_var(13405),
        ), array('x__left'), 0);
        if(count($is_previous)){
            $this_tab .= '<p class="space-content">'.view_i_title($i_focus).' Helps you:</p>';
            $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
            foreach($is_previous as $key => $previous_i){
                $this_tab .= view_i_x($previous_i, null, false, array('completion_percentage' => 0));
            }
            $this_tab .= '</div>';
        }

    } elseif($x__type==12274){

        $counter = $i_stats['e_count'];

        //List Sources:
        foreach($this->config->item('e___4251') as $e__id2 => $m2){
            if($i_stats['count_'.$e__id2]>0){
                $this_tab .= '<div class="headline"><span class="icon-block">'.$m2['m_icon'].'</span>'.$i_stats['count_'.$e__id2].' '.$m2['m_name'].':</div>';
                $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
                foreach ($i_stats['array_'.$e__id2] as $e) {
                    $this_tab .= view_e_basic($e);
                }
                $this_tab .= '</div>';
            }
        }

    } elseif($x__type==7545){

        //CERTIFICATES
        $counter = $i_stats['count_7545'];
        $this_tab .= '<p class="space-content">Completion could earn you some of the following certificates:</p>';
        $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
        foreach ($i_stats['array_7545'] as $e) {
            $e['x__message'] = ''; //Remove for this
            $this_tab .= view_e_basic($e);
        }
        $this_tab .= '</div>';

    } elseif($x__type==12419){

        //COMMENTS
        $comments = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 12419, //COMMENTS
            'x__right' => $i_focus['i__id'],
        ), array('x__member'), 0, 0, array('x__sort' => 'ASC'));
        $counter = count($comments);

        $this_tab .= '<div style="margin-bottom:34px;">';
        $this_tab .= view_i_note_mix($x__type, $comments);
        $this_tab .= '</div>';

    } elseif($x__type==6255){

        $counter = x_coins_i(6255, $i_focus['i__id']);
        $this_tab .= '<p class="space-content">This idea has been discovered '.$counter.' times.</p>';

    } elseif($x__type==13023){

        //SHARE
        $this_url = $this->config->item('base_url').'/'.$i_focus['i__id'];

        $this_tab .= '<div class="share-this space-content" style="margin-bottom:34px;">';
        $this_tab .= '<div style="margin-bottom:13px;">Share URL:</div>';
        $this_tab .= '<input style="margin-bottom:13px;" type="url" value="' .$this_url . '" class="form-control border">';
        $this_tab .= '<div style="margin-bottom:13px;">Or share using:</div>';
        foreach($this->config->item('e___13023') as $m2) {
            $this_tab .= '<div class="icon-block"><div data-network="'.$m2['m_desc'].'" data-url="'.$this_url.'" data-title="'.$i_focus['i__title'].'" class="st-custom-button" title="Share This Idea Using '.$m2['m_name'].'">'.$m2['m_icon'].'</div></div>';
        }
        $this_tab .= '</div>';

    } else {

        //Not supported via here:
        continue;

    }



    if(!$counter && in_array($x__type, $this->config->item('n___13298'))){
        //Hide since Zero count:
        continue;
    }

    if(!$recipient_e['e__id'] && in_array($x__type, $this->config->item('n___13304'))){
        //Hide since Not logged in:
        continue;
    }

    $default_active = in_array($x__type, $this->config->item('n___13300'));
    $tab_pill_count++;

    $tab_pills .= '<li class="nav-item"><a class="nav-link tab-nav-'.$tab_group.' tab-head-'.$x__type.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m['m_icon']).'" href="javascript:void(0);" onclick="loadtab('.$tab_group.','.$x__type.')">'.$m['m_icon'].( is_null($counter) || $default_active ? '' : ' <span class="en-type-counter-'.$x__type.'">'.view_number($counter).'</span>' ).'<span class="show-max-active">&nbsp;'.$m['m_name'].'</span></a></li>';

    $tab_content .= '<div class="tab-content tab-group-'.$tab_group.' tab-data-'.$x__type.( $default_active ? '' : ' hidden ' ).'">';
    $tab_content .= $this_tab;
    $tab_content .= '</div>';

}
$tab_pills .= '</ul>';


if($tab_pill_count > 1){
    //DISCOVER TABS
    echo $tab_pills;
}

//Show All Tab Content:
echo $tab_content;




if(!$in_my_x){

    //GET STARTED
    echo '<div class="margin-top-down x-add left-margin inline-block"><a class="btn btn-x" href="/x/x_start/'.$i_focus['i__id'].'">'.$e___11035[4235]['m_icon'].' '.$e___11035[4235]['m_name'].'</a></div>';

} else {

    //PREVIOUSLY UNLOCKED:
    $unlocked_x = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        'x__type' => 6140, //DISCOVER UNLOCK LINK
        'x__member' => $recipient_e['e__id'],
        'x__left' => $i_focus['i__id'],
    ), array('x__right'), 0);

    //Did we have any steps unlocked?
    if(count($unlocked_x) > 0){
        view_i_list($i_focus, $unlocked_x, $recipient_e, 'UNLOCKED:', false);
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


    //LOCKED
    if ($i_type_meet_requirement) {


        //Requirement lock
        if(!count($x_completes) && !count($unlocked_connections) && count($unlock_paths)){

            //List Unlock paths:
            view_i_list($i_focus, $unlock_paths, $recipient_e, 'SUGGESTED IDEAS:');

        }

        //List Children if any:
        view_i_list($i_focus, $is_next, $recipient_e, null, ( $completion_rate['completion_percentage'] < 100 ));


    } elseif (in_array($i_focus['i__type'], $this->config->item('n___7712'))){

        //SELECT ANSWER

        //Has no children:
        if(!$chapters){

            //Mark this as complete since there is no child to choose from:
            if(!count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',' , $this->config->item('n___12229')) . ')' => null, //DISCOVER COMPLETE
                'x__member' => $recipient_e['e__id'],
                'x__left' => $i_focus['i__id'],
            )))){

                array_push($x_completes, $this->X_model->mark_complete($i_focus, array(
                    'x__type' => 4559, //DISCOVER MESSAGES
                    'x__member' => $recipient_e['e__id'],
                    'x__left' => $i_focus['i__id'],
                )));

            }

            view_next_i_previous($i_focus['i__id'], $recipient_e);
            return true;

        } else {

            //First fetch answers based on correct order:
            $x_answers = array();
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'x__left' => $i_focus['i__id'],
            ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $x){
                //See if this answer was seleted:
                if(count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVER IDEA LINK
                    'x__left' => $i_focus['i__id'],
                    'x__right' => $x['i__id'],
                    'x__member' => $recipient_e['e__id'],
                )))){
                    array_push($x_answers, $x);
                }
            }

            if(count($x_answers) > 0){
                //MODIFY ANSWER
                echo '<div class="edit_select_answer">';

                //List answers:
                view_i_list($i_focus, $x_answers, $recipient_e, 'YOU ANSWERED:', false);

                echo '<div class="doclear">&nbsp;</div>';

                view_next_i_previous($i_focus['i__id'], $recipient_e);

                //EDIT ANSWER:
                echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-x" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');">'.$e___11035[13495]['m_icon'].' '.$e___11035[13495]['m_name'].'</a></div>';

                echo '<div class="doclear">&nbsp;</div>';

                echo '</div>';
            }


            echo '<div class="edit_select_answer '.( count($x_answers)>0 ? 'hidden' : '' ).'">';

            //HTML:
            if ($i_focus['i__type'] == 6684) {

                echo '<div class="headline"><span class="icon-block">&nbsp;</span>SELECT ONE:</div>';

            } elseif ($i_focus['i__type'] == 7231) {

                echo '<div class="headline"><span class="icon-block">&nbsp;</span>SELECT ONE OR MORE:</div>';

            }

            //Open for list to be printed:
            echo '<div class="list-group list-answers" i__type="'.$i_focus['i__type'].'">';




            //List children to choose from:
            $common_prefix = i_calc_common_prefix($is_next, 'i__title');
            foreach($is_next as $key => $next_i) {

                //Has this been previously selected?
                $previously_selected = count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVER IDEA LINKS
                    'x__left' => $i_focus['i__id'],
                    'x__right' => $next_i['i__id'],
                    'x__member' => $recipient_e['e__id'],
                )));

                echo '<a href="javascript:void(0);" onclick="select_answer('.$next_i['i__id'].')" is-selected="'.( $previously_selected ? 1 : 0 ).'" answered_is="'.$next_i['i__id'].'" class="x_answer_'.$next_i['i__id'].' answer-item list-group-item itemdiscover no-left-padding">';


                echo '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';
                echo '<td class="icon-block check-icon" style="padding: 0 !important;"><i class="'.( $previously_selected ? 'fas' : 'far' ).' fa-circle discover"></i></td>';

                echo '<td style="width:100%; padding: 0 !important;">';
                echo '<b class="montserrat i-url" style="margin-left:0;">'.view_i_title($next_i, $common_prefix).'</b>';
                echo '</td>';

                echo '</tr></table>';


                echo '</a>';
            }


            //Close list:
            echo '</div>';




            echo '<div class="result-update margin-top-down"></div>';

            echo view_i_previous($i_focus['i__id'], $recipient_e);

            //Button to submit selection:
            if(count($x_answers)>0){
                echo '<div class="inline-block margin-top-down pull-left"><a class="btn btn-x btn-circle" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');"><i class="fas fa-arrow-left"></i></a></div>';
            }

            echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-x btn-circle" href="javascript:void(0)" onclick="x_answer()">'.$e___11035[12211]['m_icon'].'</a></div>';

            echo '</div>';

        }

    } elseif ($i_focus['i__type'] == 6677) {

        //DISCOVER ONLY
        view_i_list($i_focus, $is_next, $recipient_e);

    } elseif ($i_focus['i__type'] == 6683) {

        //TEXT RESPONSE

        echo '<div class="headline"><span class="icon-block">&nbsp;</span>YOUR RESPONSE:</div>';

        echo '<textarea class="border i_content padded x_input" placeholder="Write answer here" id="x_respond">'.( count($x_completes) ? trim($x_completes[0]['x__message']) : '' ).'</textarea>';

        echo '<div class="text_saving_result margin-top-down"></div>';

        //Show Previous Button:
        echo view_i_previous($i_focus['i__id'], $recipient_e);

        //Save Answer
        echo '<div class="margin-top-down inline-block pull-right"><a class="btn btn-x" href="javascript:void(0);" onclick="x_respond()">'.$e___11035[13497]['m_icon'].' '.$e___11035[13497]['m_name'].'</a></div>';


        if(count($x_completes)){
            //Next Ideas:
            view_i_list($i_focus, $is_next, $recipient_e, null,false);
        }

        echo '<script> $(document).ready(function () { autosize($(\'#x_respond\')); $(\'#x_respond\').focus(); }); </script>';


    } elseif (in_array($i_focus['i__type'], $this->config->item('n___7751'))) {

        //FILE UPLOAD

        echo '<div class="memberUploader">';
        echo '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

        echo '<input class="inputfile" type="file" name="file" id="fileType'.$i_focus['i__type'].'" />';


        if(!count($x_completes)) {

            //Show Previous Button:
            echo '<div class="file_saving_result">';
            echo view_i_previous($i_focus['i__id'], $recipient_e);
            echo '</div>';

            //Show next here but keep hidden until file is uploaded:
            echo '<div class="go_next_upload hidden">';
            view_next_i_previous($i_focus['i__id'], $recipient_e);
            echo '</div>';

            echo '<div class="inline-block margin-top-down edit_select_answer pull-right"><label class="btn btn-x inline-block" for="fileType'.$i_focus['i__type'].'">'.$e___11035[13497]['m_icon'].' '.$e___11035[13497]['m_name'].'</label></div>';

        } else {

            echo '<div class="file_saving_result">';

            echo '<div class="headline"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div>';

            echo '<div class="previous_answer">'.$this->X_model->message_send($x_completes[0]['x__message']).'</div>';

            echo '</div>';

            //Any child ideas?
            view_i_list($i_focus, $is_next, $recipient_e, null, true, false);

            echo '<div class="inline-block margin-top-down pull-right"><label class="btn btn-x inline-block" for="fileType'.$i_focus['i__type'].'" style="margin-left:5px;">'.$e___11035[13497]['m_icon'].' '.$e___11035[13497]['m_name'].'</label></div>';

        }

        echo '<div class="doclear">&nbsp;</div>';
        echo '</form>';
        echo '</div>';

    } else {

        //UNKNOWN IDEA TYPE
        $this->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__member' => $recipient_e['e__id'],
            'x__message' => 'step_echo() unknown idea type source ID ['.$i_focus['i__type'].'] that could not be rendered',
            'x__left' => $i_focus['i__id'],
        ));

    }
}

echo '</div>';

?>