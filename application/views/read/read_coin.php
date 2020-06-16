<script>
    var idea_loaded_id = <?= $idea_focus['idea__id'] ?>;
</script>

<script src="/application/views/read/read_coin.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>

<div class="container">

<?php

$idea_fetch_cover = idea_fetch_cover($idea_focus['idea__id']);
$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
$sources__12467 = $this->config->item('sources__12467'); //MENCH COINS
$idea_type_meet_requirement = in_array($idea_focus['idea__type'], $this->config->item('sources_id_7309'));
$recipient_source = superpower_assigned();
$is_home_page = $idea_focus['idea__id']==$this->config->item('featured_idea__id') || $idea_focus['idea__id']==$this->config->item('starting_idea__id');
if(!isset($recipient_source['source__id']) ){
    $recipient_source['source__id'] = 0;
}

//VIEW READ
$this->READ_model->create(array(
    'read__source' => $recipient_source['source__id'],
    'read__type' => 7610, //PLAYER VIEWED IDEA
    'read__left' => $idea_focus['idea__id'],
    'read__sort' => fetch_cookie_order('7610_'.$idea_focus['idea__id']),
));


//NEXT IDEAS
$ideas_next = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'read__left' => $idea_focus['idea__id'],
), array('read__right'), 0, 0, array('read__sort' => 'ASC'));

$chapters = count($ideas_next);
$common_prefix = idea_calc_common_prefix($ideas_next, 'idea__title');



//ALREADY IN READS?
$completion_rate['completion_percentage'] = 0;
$in_my_reads = ( $recipient_source ? $this->READ_model->idea_home($idea_focus['idea__id'], $recipient_source) : false );


if ($in_my_reads) {

    // % DONE
    $completion_rate = $this->READ_model->completion_progress($recipient_source['source__id'], $idea_focus);

    //Fetch progress history:
    $read_completes = $this->READ_model->fetch(array(
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'read__type IN (' . join(',', $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
        'read__source' => $recipient_source['source__id'],
        'read__left' => $idea_focus['idea__id'],
    ));


    if($idea_type_meet_requirement){

        //Reverse check answers to see if they have previously unlocked a path:
        $unlocked_connections = $this->READ_model->fetch(array(
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINKS
            'read__right' => $idea_focus['idea__id'],
            'read__source' => $recipient_source['source__id'],
        ), array('read__left'), 1);

        if(count($unlocked_connections) > 0){

            //They previously have unlocked a path here!

            //Determine READ COIN type based on it's connection type's parents that will hold the appropriate read coin.
            $read_completion_type_id = 0;
            foreach($this->config->item('sources__12327') /* READ UNLOCKS */ as $source__id => $m){
                if(in_array($unlocked_connections[0]['read__type'], $m['m_parents'])){
                    $read_completion_type_id = $source__id;
                    break;
                }
            }

            //Could we determine the coin type?
            if($read_completion_type_id > 0){

                //Yes, Issue coin:
                array_push($read_completes, $this->READ_model->mark_complete($idea_focus, array(
                    'read__type' => $read_completion_type_id,
                    'read__source' => $recipient_source['source__id'],
                    'read__left' => $idea_focus['idea__id'],
                )));

            } else {

                //Oooops, we could not find it, report bug:
                $this->READ_model->create(array(
                    'read__type' => 4246, //Platform Bug Reports
                    'read__source' => $recipient_source['source__id'],
                    'read__message' => 'read_coin() found idea connector ['.$unlocked_connections[0]['read__type'].'] without a valid unlock method @12327',
                    'read__left' => $idea_focus['idea__id'],
                    'read__reference' => $unlocked_connections[0]['read__id'],
                ));

            }

        } else {

            //Try to find paths to unlock:
            $unlock_paths = $this->IDEA_model->unlock_paths($idea_focus);

            //Set completion method:
            if(!count($unlock_paths)){

                //No path found:
                array_push($read_completes, $this->READ_model->mark_complete($idea_focus, array(
                    'read__type' => 7492, //TERMINATE
                    'read__source' => $recipient_source['source__id'],
                    'read__left' => $idea_focus['idea__id'],
                )));


            }
        }
    }


    //READS UI
    echo '<div class="hideIfEmpty focus_reads_top"></div>';

    //READ PROGRESS ONLY AT TOP LEVEL
    if($completion_rate['completion_percentage']>0 && $completion_rate['completion_percentage']<100){
        echo '<div class="progress-bg-list no-horizonal-margin" title="Read '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)" data-toggle="tooltip" data-placement="bottom"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
    }

}





//IDEA TITLE
echo '<h1 class="block-one" '.( !$recipient_source['source__id'] ? ' style="padding-top: 21px;" ' : '' ).'><span class="icon-block top-icon">'.view_read_icon_legend( $completion_rate['completion_percentage']>0 , $completion_rate['completion_percentage'] ).'</span><span class="title-block-lg">' . view_idea__title($idea_focus) . '</span></h1>';





//IDEA LAYOUT
$idea_stats = idea_stats($idea_focus['idea__metadata']);
$tab_group = 13291;
$tab_pills = '<ul class="nav nav-pills nav-sm">';
$tab_content = '';
$tab_pill_count = 0;

foreach($this->config->item('sources__'.$tab_group) as $read__type => $m){


    //Is this a caret menu?
    if(in_array(11040 , $m['m_parents'])){
        echo view_caret($read__type, $m, $idea_focus['idea__id']);
        continue;
    }

    $counter = null; //Assume no counters
    $this_tab = '';

    if($read__type==4231){

        //MESSAGES
        $messages = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type' => 4231, //IDEA NOTES Messages
            'read__right' => $idea_focus['idea__id'],
        ), array(), 0, 0, array('read__sort' => 'ASC'));
        $counter = count($messages);

        $this_tab .= '<div style="margin-bottom:34px;">';
        if($counter){
            foreach($messages as $message_read) {
                $counter++;
                $this_tab .= $this->READ_model->message_send(
                    $message_read['read__message'],
                    $recipient_source
                );
            }
        } else {
            $this_tab .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>This idea has no messages</div>';
        }
        $this_tab .= '</div>';

    } elseif($read__type==12273 && !$is_home_page && ( $idea_stats['ideas_average']>$chapters || (!$in_my_reads && ($idea_stats['ideas_average']>0 || $chapters>0)))){

        //IDEAS
        $counter = $idea_stats['ideas_average'];

        //IDEA or TIME difference?
        if($idea_stats['ideas_min']!=$idea_stats['ideas_max'] || $idea_stats['duration_min']!=$idea_stats['duration_max']){
            $this_tab .= '<p class="space-content">The number of ideas you read (and the time it takes to read them) depends on the choices you make interactively along the way:</p>';
            $this_tab .= '<p class="space-content" style="margin-bottom:34px;">';
            $this_tab .= '<span class="reading-paths">Minimum:</span>'.$sources__12467[12273]['m_icon'].' <span class="reading-count montserrat idea">'.$idea_stats['ideas_min'].'</span><span class="mono-space">'.view_time_hours($idea_stats['duration_min']).'</span><br />';
            $this_tab .= '<span class="reading-paths">Average:</span>'.$sources__12467[12273]['m_icon'].' <span class="reading-count montserrat idea">'.$idea_stats['ideas_average'].'</span><span class="mono-space">'.view_time_hours($idea_stats['duration_average']).'</span><br />';
            $this_tab .= '<span class="reading-paths">Maximum:</span>'.$sources__12467[12273]['m_icon'].' <span class="reading-count montserrat idea">'.$idea_stats['ideas_max'].'</span><span class="mono-space">'.view_time_hours($idea_stats['duration_max']).'</span>';
            $this_tab .= '</p>';
        }

        //NEXT IDEAS
        if(!$in_my_reads && $chapters){
            $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
            foreach($ideas_next as $key => $next_idea){
                $this_tab .= view_idea_read($next_idea, idea_calc_common_prefix($ideas_next, 'idea__title'));
            }
            $this_tab .= '</div>';
        }

        //IDEA PREVIOUS
        $ideas_previous = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            'read__right' => $idea_focus['idea__id'],
            'read__left !=' => $this->config->item('featured_idea__id'),
        ), array('read__left'), 0);
        if(count($ideas_previous)){
            $this_tab .= '<p class="space-content">'.view_idea__title($idea_focus).' Helps you:</p>';
            $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
            foreach($ideas_previous as $key => $previous_idea){
                $this_tab .= view_idea_read($previous_idea);
            }
            $this_tab .= '</div>';
        }

    } elseif($read__type==12864 && !$is_home_page && $idea_stats['sources_count']){

        //EXPERTS
        $counter = $idea_stats['sources_count'];
        $this_tab .= '<p class="space-content">Ideas mapped from these expert sources:</p>';
        $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
        foreach ($idea_stats['sources_array'] as $source_source) {
            $this_tab .= view_source_basic($source_source);
        }
        $this_tab .= '</div>';

    } elseif($read__type==7545 && !$is_home_page && $idea_stats['certificate_count']){

        //CERTIFICATES
        $counter = $idea_stats['certificate_count'];
        $this_tab .= '<p class="space-content">Completion may earn you some of the following certificates:</p>';
        $this_tab .= '<div class="list-group" style="margin-bottom:34px;">';
        foreach ($idea_stats['certificate_array'] as $source_source) {
            $source_source['read__message'] = ''; //Remove for this
            $this_tab .= view_source_basic($source_source);
        }
        $this_tab .= '</div>';

    } elseif($read__type==12419 && !$is_home_page){

        //COMMENTS
        $comments = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type' => 12419, //COMMENTS
            'read__right' => $idea_focus['idea__id'],
        ), array('read__source'), 0, 0, array('read__sort' => 'ASC'));
        $counter = count($comments);

        $this_tab .= '<div style="margin-bottom:34px;">';
        $this_tab .= view_idea_note_mix($read__type, $comments);
        $this_tab .= '</div>';

    } elseif($read__type==13023 && !$is_home_page){

        //SHARE
        $this_url = $this->config->item('base_url').'/'.$idea_focus['idea__id'];

        $this_tab .= '<div class="share-this space-content" style="margin-bottom:34px;">';
        $this_tab .= '<div style="margin-bottom:13px;">Share URL:</div>';
        $this_tab .= '<input style="margin-bottom:13px;" type="url" value="' .$this_url . '" class="form-control border">';
        $this_tab .= '<div style="margin-bottom:13px;">Or share using:</div>';
        foreach($this->config->item('sources__13023') as $m2) {
            $this_tab .= '<div class="icon-block"><div data-network="'.$m2['m_desc'].'" data-url="'.$this_url.'" data-title="'.$idea_focus['idea__title'].'" data-image="'.$idea_fetch_cover.'" class="st-custom-button" title="Share This Idea Using '.$m2['m_name'].'">'.$m2['m_icon'].'</div></div>';
        }
        $this_tab .= '</div>';

    } else {

        //Not supported via here:
        continue;

    }



    if(!$counter && in_array($read__type, $this->config->item('sources_id_13298'))){
        //Hide since Zero count:
        continue;
    }

    if(!$recipient_source['source__id'] && in_array($read__type, $this->config->item('sources_id_13304'))){
        //Hide since Not logged in:
        continue;
    }

    $default_active = in_array($read__type, $this->config->item('sources_id_13300'));
    $tab_pill_count++;

    $tab_pills .= '<li class="nav-item"><a class="nav-link tab-nav-'.$tab_group.' tab-head-'.$read__type.' '.( $default_active ? ' active ' : '' ).'" href="javascript:void(0);" onclick="loadtab('.$tab_group.','.$read__type.', '.$idea_focus['idea__id'].', 0)">'.$m['m_icon'].( is_null($counter) || $default_active ? '' : ' <span class="en-type-counter-'.$read__type.'">'.view_number($counter).'</span>' ).'<span class="show-active-max">&nbsp;'.$m['m_name'].'</span></a></li>';

    $tab_content .= '<div class="tab-content tab-group-'.$tab_group.' tab-data-'.$read__type.( $default_active ? '' : ' hidden ' ).'">';
    $tab_content .= $this_tab;
    $tab_content .= '</div>';

}
$tab_pills .= '</ul>';


if(!$is_home_page && $tab_pill_count > 1){
    //READ TABS
    echo $tab_pills;
}

//Show All Tab Content:
echo $tab_content;



if(!$in_my_reads){

    if($is_home_page){

        //FEATURED
        echo '<div class="read-topic"><span class="icon-block">'.$sources__11035[13216]['m_icon'].'</span>'.$sources__11035[13216]['m_name'].'</div>';
        echo '<div class="cover-list" style="padding:13px 0 33px 33px;">';
        foreach($ideas_next as $key => $next_idea){
            echo view_idea_cover($next_idea, false, idea_calc_common_prefix($ideas_next, 'idea__title'));
        }
        echo '</div>';

    } else {

        //GET STARTED
        echo '<div class="margin-top-down read-add"><a class="btn btn-read" href="/j'.$idea_focus['idea__id'].'">'.$sources__11035[4235]['m_icon'].' '.$sources__11035[4235]['m_name'].'</a></div>';

    }

} else {

    //PREVIOUSLY UNLOCKED:
    $unlocked_reads = $this->READ_model->fetch(array(
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        'read__type' => 6140, //READ UNLOCK LINK
        'read__source' => $recipient_source['source__id'],
        'read__left' => $idea_focus['idea__id'],
    ), array('read__right'), 0);

    //Did we have any steps unlocked?
    if(count($unlocked_reads) > 0){
        view_idea_list($idea_focus, $unlocked_reads, $recipient_source, 'UNLOCKED:', false);
    }



    /*
     *
     * IDEA TYPE INPUT CONTROLLER
     * Now let's show the appropriate
     * inputs that correspond to the
     * idea type that enable the reader
     * to move forward.
     *
     * */


    //LOCKED
    if ($idea_type_meet_requirement) {


        //Requirement lock
        if(!count($read_completes) && !count($unlocked_connections) && count($unlock_paths)){

            //List Unlock paths:
            view_idea_list($idea_focus, $unlock_paths, $recipient_source, 'SUGGESTED IDEAS:');

        }

        //List Children if any:
        view_idea_list($idea_focus, $ideas_next, $recipient_source, null, ( $completion_rate['completion_percentage'] < 100 ));


    } elseif (in_array($idea_focus['idea__type'], $this->config->item('sources_id_7712'))){

        //SELECT ANSWER

        //Has no children:
        if(!$chapters){

            //Mark this as complete since there is no child to choose from:
            if(!count($this->READ_model->fetch(array(
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__type IN (' . join(',' , $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
                'read__source' => $recipient_source['source__id'],
                'read__left' => $idea_focus['idea__id'],
            )))){

                array_push($read_completes, $this->READ_model->mark_complete($idea_focus, array(
                    'read__type' => 4559, //READ MESSAGES
                    'read__source' => $recipient_source['source__id'],
                    'read__left' => $idea_focus['idea__id'],
                )));

            }

            view_next_idea_previous($idea_focus['idea__id'], $recipient_source);
            return true;

        } else {

            //First fetch answers based on correct order:
            $read_answers = array();
            foreach($this->READ_model->fetch(array(
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'read__left' => $idea_focus['idea__id'],
            ), array('read__right'), 0, 0, array('read__sort' => 'ASC')) as $read){
                //See if this answer was seleted:
                if(count($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINK
                    'read__left' => $idea_focus['idea__id'],
                    'read__right' => $read['idea__id'],
                    'read__source' => $recipient_source['source__id'],
                )))){
                    array_push($read_answers, $read);
                }
            }

            if(count($read_answers) > 0){
                //MODIFY ANSWER
                echo '<div class="edit_select_answer">';

                //List answers:
                view_idea_list($idea_focus, $read_answers, $recipient_source, 'YOU ANSWERED:', false);

                echo '<div class="doclear">&nbsp;</div>';

                view_next_idea_previous($idea_focus['idea__id'], $recipient_source);

                echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-read btn-circle" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');"><i class="fas fa-pen"></i></a></div>';

                echo '<div class="doclear">&nbsp;</div>';

                echo '</div>';
            }


            echo '<div class="edit_select_answer '.( count($read_answers)>0 ? 'hidden' : '' ).'">';

            //HTML:
            if ($idea_focus['idea__type'] == 6684) {

                echo '<div class="read-topic"><span class="icon-block">&nbsp;</span>SELECT ONE:</div>';

            } elseif ($idea_focus['idea__type'] == 7231) {

                echo '<div class="read-topic"><span class="icon-block">&nbsp;</span>SELECT ONE OR MORE:</div>';

            }

            //Open for list to be printed:
            echo '<div class="list-group list-answers" idea__type="'.$idea_focus['idea__type'].'">';




            //List children to choose from:
            foreach($ideas_next as $key => $next_idea) {

                //Has this been previously selected?
                $previously_selected = count($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINKS
                    'read__left' => $idea_focus['idea__id'],
                    'read__right' => $next_idea['idea__id'],
                    'read__source' => $recipient_source['source__id'],
                )));

                echo '<a href="javascript:void(0);" onclick="select_answer('.$next_idea['idea__id'].')" is-selected="'.( $previously_selected ? 1 : 0 ).'" answered_ideas="'.$next_idea['idea__id'].'" class="read_answer_'.$next_idea['idea__id'].' answer-item list-group-item itemread no-left-padding">';


                echo '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';
                echo '<td class="icon-block check-icon" style="padding: 0 !important;"><i class="'.( $previously_selected ? 'fas' : 'far' ).' fa-circle read"></i></td>';

                echo '<td style="width:100%; padding: 0 !important;">';
                echo '<b class="montserrat idea-url" style="margin-left:0;">'.view_idea__title($next_idea, $common_prefix).'</b>';
                echo '</td>';

                echo '</tr></table>';


                echo '</a>';
            }


            //Close list:
            echo '</div>';




            echo '<div class="result-update margin-top-down"></div>';

            echo view_idea_previous_read($idea_focus['idea__id'], $recipient_source);

            //Button to submit selection:
            if(count($read_answers)>0){
                echo '<div class="inline-block margin-top-down pull-left"><a class="btn btn-read btn-circle" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');"><i class="fas fa-arrow-left"></i></a></div>';
            }

            echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-read btn-circle" href="javascript:void(0)" onclick="read_answer()">'.$sources__11035[12211]['m_icon'].'</a></div>';

            echo '</div>';

        }

    } elseif ($idea_focus['idea__type'] == 6677) {

        //READ ONLY
        view_idea_list($idea_focus, $ideas_next, $recipient_source);

    } elseif ($idea_focus['idea__type'] == 6683) {

        //TEXT RESPONSE

        echo '<div class="read-topic"><span class="icon-block">&nbsp;</span>YOUR RESPONSE:</div>';

        echo '<textarea class="border i_content padded read_input" placeholder="Write answer here" id="read_text_answer">'.( count($read_completes) ? trim($read_completes[0]['read__message']) : '' ).'</textarea>';

        echo '<div class="text_saving_result margin-top-down"></div>';

        //Show Previous Button:
        echo view_idea_previous_read($idea_focus['idea__id'], $recipient_source);

        //Save/Upload & Next:
        echo '<div class="margin-top-down inline-block pull-right"><a class="btn btn-read btn-circle" href="javascript:void(0);" onclick="read_text_answer()">'.$sources__11035[12211]['m_icon'].'</a></div>';


        if(count($read_completes)){
            //Next Ideas:
            view_idea_list($idea_focus, $ideas_next, $recipient_source, null,false);
        }

        echo '<script> $(document).ready(function () { autosize($(\'#read_text_answer\')); $(\'#read_text_answer\').focus(); }); </script>';


    } elseif (in_array($idea_focus['idea__type'], $this->config->item('sources_id_7751'))) {

        //FILE UPLOAD

        echo '<div class="playerUploader">';
        echo '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

        echo '<input class="inputfile" type="file" name="file" id="fileType'.$idea_focus['idea__type'].'" />';


        if(!count($read_completes)) {

            //Show Previous Button:
            echo '<div class="file_saving_result">';
            echo view_idea_previous_read($idea_focus['idea__id'], $recipient_source);
            echo '</div>';

            //Show next here but keep hidden until file is uploaded:
            echo '<div class="go_next_upload hidden">';
            view_next_idea_previous($idea_focus['idea__id'], $recipient_source);
            echo '</div>';

            echo '<div class="inline-block margin-top-down edit_select_answer pull-right"><label class="btn btn-read btn-circle inline-block" for="fileType'.$idea_focus['idea__type'].'"><i class="fad fa-cloud-upload-alt" style="margin-left: -4px;"></i></label></div>';

        } else {

            echo '<div class="file_saving_result">';

            echo '<div class="read-topic"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div>';

            echo '<div class="previous_answer">'.$this->READ_model->message_send($read_completes[0]['read__message']).'</div>';

            echo '</div>';

            //Any child ideas?
            view_idea_list($idea_focus, $ideas_next, $recipient_source, null, true, false);

            echo '<div class="inline-block margin-top-down pull-right"><label class="btn btn-read inline-block btn-circle" for="fileType'.$idea_focus['idea__type'].'" style="margin-left:5px;"><i class="fad fa-cloud-upload-alt" style="margin-left: -4px;"></i></label></div>';

        }

        echo '<div class="doclear">&nbsp;</div>';
        echo '</form>';
        echo '</div>';

    } else {

        //UNKNOWN IDEA TYPE
        $this->READ_model->create(array(
            'read__type' => 4246, //Platform Bug Reports
            'read__source' => $recipient_source['source__id'],
            'read__message' => 'step_echo() unknown idea type source ID ['.$idea_focus['idea__type'].'] that could not be rendered',
            'read__left' => $idea_focus['idea__id'],
        ));

    }
}

?>
</div>