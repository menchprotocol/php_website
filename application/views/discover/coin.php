<script>
    var idea_loaded_id = <?= $idea_focus['i__id'] ?>;
</script>

<script src="/application/views/discover/coin.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>

<div class="container">

<?php

$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
$sources__13291 = $this->config->item('sources__13291'); //DISCOVER TABS
$idea_type_meet_requirement = in_array($idea_focus['i__type'], $this->config->item('sources_id_7309'));
$recipient_source = superpower_assigned();
if(!isset($recipient_source['e__id']) ){
    $recipient_source['e__id'] = 0;
}


//NEXT IDEAS
$ideas_next = $this->DISCOVER_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'x__left' => $idea_focus['i__id'],
), array('x__right'), 0, 0, array('x__sort' => 'ASC'));

$chapters = count($ideas_next);
$completion_rate['completion_percentage'] = 0;
$in_my_discoveries = ( $recipient_source['e__id'] ? $this->DISCOVER_model->idea_home($idea_focus['i__id'], $recipient_source) : false );


if($recipient_source['e__id']){

    //VIEW DISCOVER
    $this->DISCOVER_model->create(array(
        'x__player' => $recipient_source['e__id'],
        'x__type' => 7610, //PLAYER VIEWED IDEA
        'x__left' => $idea_focus['i__id'],
        'x__sort' => fetch_cookie_order('7610_'.$idea_focus['i__id']),
    ));

    if ($in_my_discoveries) {

        // % DONE
        $completion_rate = $this->DISCOVER_model->completion_progress($recipient_source['e__id'], $idea_focus);

        //Fetch progress history:
        $discovery_completes = $this->DISCOVER_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('sources_id_12229')) . ')' => null, //DISCOVER COMPLETE
            'x__player' => $recipient_source['e__id'],
            'x__left' => $idea_focus['i__id'],
        ));


        if($idea_type_meet_requirement){

            //Reverse check answers to see if they have previously unlocked a path:
            $unlocked_connections = $this->DISCOVER_model->fetch(array(
                'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //DISCOVER IDEA LINKS
                'x__right' => $idea_focus['i__id'],
                'x__player' => $recipient_source['e__id'],
            ), array('x__left'), 1);

            if(count($unlocked_connections) > 0){

                //They previously have unlocked a path here!

                //Determine DISCOVER COIN type based on it's connection type's parents that will hold the appropriate discover coin.
                $discovery_completion_type_id = 0;
                foreach($this->config->item('sources__12327') /* DISCOVER UNLOCKS */ as $e__id => $m){
                    if(in_array($unlocked_connections[0]['x__type'], $m['m_parents'])){
                        $discovery_completion_type_id = $e__id;
                        break;
                    }
                }

                //Could we determine the coin type?
                if($discovery_completion_type_id > 0){

                    //Yes, Issue coin:
                    array_push($discovery_completes, $this->DISCOVER_model->mark_complete($idea_focus, array(
                        'x__type' => $discovery_completion_type_id,
                        'x__player' => $recipient_source['e__id'],
                        'x__left' => $idea_focus['i__id'],
                    )));

                } else {

                    //Oooops, we could not find it, report bug:
                    $this->DISCOVER_model->create(array(
                        'x__type' => 4246, //Platform Bug Reports
                        'x__player' => $recipient_source['e__id'],
                        'x__message' => 'x_coin() found idea connector ['.$unlocked_connections[0]['x__type'].'] without a valid unlock method @12327',
                        'x__left' => $idea_focus['i__id'],
                        'x__reference' => $unlocked_connections[0]['x__id'],
                    ));

                }

            } else {

                //Try to find paths to unlock:
                $unlock_paths = $this->MAP_model->unlock_paths($idea_focus);

                //Set completion method:
                if(!count($unlock_paths)){

                    //No path found:
                    array_push($discovery_completes, $this->DISCOVER_model->mark_complete($idea_focus, array(
                        'x__type' => 7492, //TERMINATE
                        'x__player' => $recipient_source['e__id'],
                        'x__left' => $idea_focus['i__id'],
                    )));


                }
            }
        }


        //DISCOVERIES UI
        echo '<div class="hideIfEmpty focus_discoveries_top"></div>';

        //DISCOVER PROGRESS ONLY AT TOP LEVEL
        if($completion_rate['completion_percentage']>0 && $completion_rate['completion_percentage']<100){
            echo '<div class="progress-bg-list no-horizonal-margin" title="Discovered '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)" data-toggle="tooltip" data-placement="bottom"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
        }

    }

}




//IDEA TITLE
echo '<h1 class="block-one" '.( !$recipient_source['e__id'] ? ' style="padding-top: 21px;" ' : '' ).'><span class="icon-block top-icon">'.view_x_icon_legend( $completion_rate['completion_percentage']>0 , $completion_rate['completion_percentage'] ).'</span><span class="title-block-lg">' . view_i_title($idea_focus) . '</span></h1>';



//IDEA LAYOUT
$idea_stats = idea_stats($idea_focus['i__metadata']);
$tab_group = 13291;
$tab_pills = '<ul class="nav nav-pills nav-sm">';
$tab_content = '';
$tab_pill_count = 0;

foreach($this->config->item('sources__'.$tab_group) as $x__type => $m){


    //Is this a caret menu?
    if(in_array(11040 , $m['m_parents'])){
        echo view_caret($x__type, $m, $idea_focus['i__id']);
        continue;
    }

    $counter = null; //Assume no counters
    $this_tab = '';

    if($x__type==4231){

        //MESSAGES
        $messages = $this->DISCOVER_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $idea_focus['i__id'],
        ), array(), 0, 0, array('x__sort' => 'ASC'));
        $counter = count($messages);

        $this_tab .= '<div style="margin-bottom:34px;">';
        if($counter){
            foreach($messages as $message_discovered) {
                $counter++;
                $this_tab .= $this->DISCOVER_model->message_send(
                    $message_discovered['x__message'],
                    $recipient_source
                );
            }
        }
        $this_tab .= '</div>';

    } elseif($x__type==13359 && ( $idea_stats['ideas_average']>$chapters || (!$in_my_discoveries && ($idea_stats['ideas_average']>0 || $chapters>0)))){

        //IDEAS
        $counter = $idea_stats['ideas_average'];

        //IDEA or TIME difference?
        if($idea_stats['ideas_min']!=$idea_stats['ideas_max'] || $idea_stats['duration_min']!=$idea_stats['duration_max']){
            $this_tab .= '<p class="space-content">The number of ideas you discover (and the time it takes to discover them) depends on the choices you make interactively along the way:</p>';
            $this_tab .= '<p class="space-content" style="margin-bottom:34px;">';
            $this_tab .= '<span class="discovering-paths">Minimum:</span>'.$sources__13291[13359]['m_icon'].' <span class="discovering-count montserrat idea">'.$idea_stats['ideas_min'].'</span><span class="mono-space">'.view_time_hours($idea_stats['duration_min']).'</span><br />';
            $this_tab .= '<span class="discovering-paths">Average:</span>'.$sources__13291[13359]['m_icon'].' <span class="discovering-count montserrat idea">'.$idea_stats['ideas_average'].'</span><span class="mono-space">'.view_time_hours($idea_stats['duration_average']).'</span><br />';
            $this_tab .= '<span class="discovering-paths">Maximum:</span>'.$sources__13291[13359]['m_icon'].' <span class="discovering-count montserrat idea">'.$idea_stats['ideas_max'].'</span><span class="mono-space">'.view_time_hours($idea_stats['duration_max']).'</span>';
            $this_tab .= '</p>';
        }

        //NEXT IDEAS
        if(!$in_my_discoveries && $chapters){
            $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
            foreach($ideas_next as $key => $next_idea){
                $this_tab .= view_i_discovered($next_idea, idea_calc_common_prefix($ideas_next, 'i__title'));
            }
            $this_tab .= '</div>';
        }

        //IDEA PREVIOUS
        $ideas_previous = $this->DISCOVER_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            'x__right' => $idea_focus['i__id'],
            'x__left !=' => $this->config->item('featured_i__id'),
        ), array('x__left'), 0);
        if(count($ideas_previous)){
            $this_tab .= '<p class="space-content">'.view_i_title($idea_focus).' Helps you:</p>';
            $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
            foreach($ideas_previous as $key => $previous_idea){
                $this_tab .= view_i_discovered($previous_idea);
            }
            $this_tab .= '</div>';
        }

    } elseif($x__type==4430 && $idea_stats['players_count']>0){

        //PLAYERS
        $counter = $idea_stats['players_count'];
        $this_tab .= '<p class="space-content">Ideas were mapped by these players:</p>';
        $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
        foreach ($idea_stats['players_array'] as $e_source) {
            $this_tab .= view_e_basic($e_source);
        }
        $this_tab .= '</div>';

    } elseif($x__type==12864 && $idea_stats['sources_count']>0){

        //EXPERTS
        $counter = $idea_stats['sources_count'];
        $this_tab .= '<p class="space-content">Ideas were mapped from these expert sources:</p>';
        $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
        foreach ($idea_stats['sources_array'] as $e_source) {
            $this_tab .= view_e_basic($e_source);
        }
        $this_tab .= '</div>';

    } elseif($x__type==7545 && $idea_stats['certificate_count']>0){

        //CERTIFICATES
        $counter = $idea_stats['certificate_count'];
        $this_tab .= '<p class="space-content">Completion could earn you some of the following certificates:</p>';
        $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
        foreach ($idea_stats['certificate_array'] as $e_source) {
            $e_source['x__message'] = ''; //Remove for this
            $this_tab .= view_e_basic($e_source);
        }
        $this_tab .= '</div>';

    } elseif($x__type==12419){

        //COMMENTS
        $comments = $this->DISCOVER_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'x__type' => 12419, //COMMENTS
            'x__right' => $idea_focus['i__id'],
        ), array('x__player'), 0, 0, array('x__sort' => 'ASC'));
        $counter = count($comments);

        $this_tab .= '<div style="margin-bottom:34px;">';
        $this_tab .= view_i_note_mix($x__type, $comments);
        $this_tab .= '</div>';

    } elseif($x__type==13023){

        //SHARE
        $this_url = $this->config->item('base_url').'/'.$idea_focus['i__id'];

        $this_tab .= '<div class="share-this space-content" style="margin-bottom:34px;">';
        $this_tab .= '<div style="margin-bottom:13px;">Share URL:</div>';
        $this_tab .= '<input style="margin-bottom:13px;" type="url" value="' .$this_url . '" class="form-control border">';
        $this_tab .= '<div style="margin-bottom:13px;">Or share using:</div>';
        foreach($this->config->item('sources__13023') as $m2) {
            $this_tab .= '<div class="icon-block"><div data-network="'.$m2['m_desc'].'" data-url="'.$this_url.'" data-title="'.$idea_focus['i__title'].'" class="st-custom-button" title="Share This Idea Using '.$m2['m_name'].'">'.$m2['m_icon'].'</div></div>';
        }
        $this_tab .= '</div>';

    } else {

        //Not supported via here:
        continue;

    }



    if(!$counter && in_array($x__type, $this->config->item('sources_id_13298'))){
        //Hide since Zero count:
        continue;
    }

    if(!$recipient_source['e__id'] && in_array($x__type, $this->config->item('sources_id_13304'))){
        //Hide since Not logged in:
        continue;
    }

    $default_active = in_array($x__type, $this->config->item('sources_id_13300'));
    $tab_pill_count++;

    $tab_pills .= '<li class="nav-item"><a class="nav-link tab-nav-'.$tab_group.' tab-head-'.$x__type.' '.( $default_active ? ' active ' : '' ).'" href="javascript:void(0);" onclick="loadtab('.$tab_group.','.$x__type.', '.$idea_focus['i__id'].', 0)">'.$m['m_icon'].( is_null($counter) || $default_active ? '' : ' <span class="en-type-counter-'.$x__type.'">'.view_number($counter).'</span>' ).'<span class="show-active-max">&nbsp;'.$m['m_name'].'</span></a></li>';

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




if(!$in_my_discoveries){

    //GET STARTED
    echo '<div class="margin-top-down discover-add left-margin inline-block"><a class="btn btn-discover" href="/discover/x_start/'.$idea_focus['i__id'].'">'.$sources__11035[4235]['m_icon'].' '.$sources__11035[4235]['m_name'].'</a></div>';

} else {

    //PREVIOUSLY UNLOCKED:
    $unlocked_discoveries = $this->DISCOVER_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        'x__type' => 6140, //DISCOVER UNLOCK LINK
        'x__player' => $recipient_source['e__id'],
        'x__left' => $idea_focus['i__id'],
    ), array('x__right'), 0);

    //Did we have any steps unlocked?
    if(count($unlocked_discoveries) > 0){
        view_i_list($idea_focus, $unlocked_discoveries, $recipient_source, 'UNLOCKED:', false);
    }


    /*
     *
     * IDEA TYPE INPUT CONTROLLER
     * Now let's show the appropriate
     * inputs that correspond to the
     * idea type that enable the player
     * to move forward.
     *
     * */


    //LOCKED
    if ($idea_type_meet_requirement) {


        //Requirement lock
        if(!count($discovery_completes) && !count($unlocked_connections) && count($unlock_paths)){

            //List Unlock paths:
            view_i_list($idea_focus, $unlock_paths, $recipient_source, 'SUGGESTED IDEAS:');

        }

        //List Children if any:
        view_i_list($idea_focus, $ideas_next, $recipient_source, null, ( $completion_rate['completion_percentage'] < 100 ));


    } elseif (in_array($idea_focus['i__type'], $this->config->item('sources_id_7712'))){

        //SELECT ANSWER

        //Has no children:
        if(!$chapters){

            //Mark this as complete since there is no child to choose from:
            if(!count($this->DISCOVER_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',' , $this->config->item('sources_id_12229')) . ')' => null, //DISCOVER COMPLETE
                'x__player' => $recipient_source['e__id'],
                'x__left' => $idea_focus['i__id'],
            )))){

                array_push($discovery_completes, $this->DISCOVER_model->mark_complete($idea_focus, array(
                    'x__type' => 4559, //DISCOVER MESSAGES
                    'x__player' => $recipient_source['e__id'],
                    'x__left' => $idea_focus['i__id'],
                )));

            }

            view_next_idea_previous($idea_focus['i__id'], $recipient_source);
            return true;

        } else {

            //First fetch answers based on correct order:
            $x_answers = array();
            foreach($this->DISCOVER_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'x__left' => $idea_focus['i__id'],
            ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $discovery){
                //See if this answer was seleted:
                if(count($this->DISCOVER_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //DISCOVER IDEA LINK
                    'x__left' => $idea_focus['i__id'],
                    'x__right' => $discovery['i__id'],
                    'x__player' => $recipient_source['e__id'],
                )))){
                    array_push($x_answers, $discovery);
                }
            }

            if(count($x_answers) > 0){
                //MODIFY ANSWER
                echo '<div class="edit_select_answer">';

                //List answers:
                view_i_list($idea_focus, $x_answers, $recipient_source, 'YOU ANSWERED:', false);

                echo '<div class="doclear">&nbsp;</div>';

                view_next_idea_previous($idea_focus['i__id'], $recipient_source);

                echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-discover btn-circle" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');"><i class="fas fa-pen"></i></a></div>';

                echo '<div class="doclear">&nbsp;</div>';

                echo '</div>';
            }


            echo '<div class="edit_select_answer '.( count($x_answers)>0 ? 'hidden' : '' ).'">';

            //HTML:
            if ($idea_focus['i__type'] == 6684) {

                echo '<div class="discover-topic"><span class="icon-block">&nbsp;</span>SELECT ONE:</div>';

            } elseif ($idea_focus['i__type'] == 7231) {

                echo '<div class="discover-topic"><span class="icon-block">&nbsp;</span>SELECT ONE OR MORE:</div>';

            }

            //Open for list to be printed:
            echo '<div class="list-group list-answers" i__type="'.$idea_focus['i__type'].'">';




            //List children to choose from:
            $common_prefix = idea_calc_common_prefix($ideas_next, 'i__title');
            foreach($ideas_next as $key => $next_idea) {

                //Has this been previously selected?
                $previously_selected = count($this->DISCOVER_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //DISCOVER IDEA LINKS
                    'x__left' => $idea_focus['i__id'],
                    'x__right' => $next_idea['i__id'],
                    'x__player' => $recipient_source['e__id'],
                )));

                echo '<a href="javascript:void(0);" onclick="select_answer('.$next_idea['i__id'].')" is-selected="'.( $previously_selected ? 1 : 0 ).'" answered_ideas="'.$next_idea['i__id'].'" class="x_answer_'.$next_idea['i__id'].' answer-item list-group-item itemdiscover no-left-padding">';


                echo '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';
                echo '<td class="icon-block check-icon" style="padding: 0 !important;"><i class="'.( $previously_selected ? 'fas' : 'far' ).' fa-circle discover"></i></td>';

                echo '<td style="width:100%; padding: 0 !important;">';
                echo '<b class="montserrat idea-url" style="margin-left:0;">'.view_i_title($next_idea, $common_prefix).'</b>';
                echo '</td>';

                echo '</tr></table>';


                echo '</a>';
            }


            //Close list:
            echo '</div>';




            echo '<div class="result-update margin-top-down"></div>';

            echo view_i_previous_discovered($idea_focus['i__id'], $recipient_source);

            //Button to submit selection:
            if(count($x_answers)>0){
                echo '<div class="inline-block margin-top-down pull-left"><a class="btn btn-discover btn-circle" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');"><i class="fas fa-arrow-left"></i></a></div>';
            }

            echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-discover btn-circle" href="javascript:void(0)" onclick="x_answer()">'.$sources__11035[12211]['m_icon'].'</a></div>';

            echo '</div>';

        }

    } elseif ($idea_focus['i__type'] == 6677) {

        //DISCOVER ONLY
        view_i_list($idea_focus, $ideas_next, $recipient_source);

    } elseif ($idea_focus['i__type'] == 6683) {

        //TEXT RESPONSE

        echo '<div class="discover-topic"><span class="icon-block">&nbsp;</span>YOUR RESPONSE:</div>';

        echo '<textarea class="border i_content padded discover_input" placeholder="Write answer here" id="x_respond">'.( count($discovery_completes) ? trim($discovery_completes[0]['x__message']) : '' ).'</textarea>';

        echo '<div class="text_saving_result margin-top-down"></div>';

        //Show Previous Button:
        echo view_i_previous_discovered($idea_focus['i__id'], $recipient_source);

        //Save/Upload & Next:
        echo '<div class="margin-top-down inline-block pull-right"><a class="btn btn-discover btn-circle" href="javascript:void(0);" onclick="x_respond()">'.$sources__11035[12211]['m_icon'].'</a></div>';


        if(count($discovery_completes)){
            //Next Ideas:
            view_i_list($idea_focus, $ideas_next, $recipient_source, null,false);
        }

        echo '<script> $(document).ready(function () { autosize($(\'#x_respond\')); $(\'#x_respond\').focus(); }); </script>';


    } elseif (in_array($idea_focus['i__type'], $this->config->item('sources_id_7751'))) {

        //FILE UPLOAD

        echo '<div class="playerUploader">';
        echo '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

        echo '<input class="inputfile" type="file" name="file" id="fileType'.$idea_focus['i__type'].'" />';


        if(!count($discovery_completes)) {

            //Show Previous Button:
            echo '<div class="file_saving_result">';
            echo view_i_previous_discovered($idea_focus['i__id'], $recipient_source);
            echo '</div>';

            //Show next here but keep hidden until file is uploaded:
            echo '<div class="go_next_upload hidden">';
            view_next_idea_previous($idea_focus['i__id'], $recipient_source);
            echo '</div>';

            echo '<div class="inline-block margin-top-down edit_select_answer pull-right"><label class="btn btn-discover btn-circle inline-block" for="fileType'.$idea_focus['i__type'].'"><i class="fad fa-cloud-upload-alt" style="margin-left: -4px;"></i></label></div>';

        } else {

            echo '<div class="file_saving_result">';

            echo '<div class="discover-topic"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div>';

            echo '<div class="previous_answer">'.$this->DISCOVER_model->message_send($discovery_completes[0]['x__message']).'</div>';

            echo '</div>';

            //Any child ideas?
            view_i_list($idea_focus, $ideas_next, $recipient_source, null, true, false);

            echo '<div class="inline-block margin-top-down pull-right"><label class="btn btn-discover inline-block btn-circle" for="fileType'.$idea_focus['i__type'].'" style="margin-left:5px;"><i class="fad fa-cloud-upload-alt" style="margin-left: -4px;"></i></label></div>';

        }

        echo '<div class="doclear">&nbsp;</div>';
        echo '</form>';
        echo '</div>';

    } else {

        //UNKNOWN IDEA TYPE
        $this->DISCOVER_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__player' => $recipient_source['e__id'],
            'x__message' => 'step_echo() unknown idea type source ID ['.$idea_focus['i__type'].'] that could not be rendered',
            'x__left' => $idea_focus['i__id'],
        ));

    }
}

?>
</div>