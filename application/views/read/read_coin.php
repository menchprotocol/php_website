<script>
    var idea_loaded_id = <?= $in['idea__id'] ?>;
</script>

<script src="/application/views/read/read_coin.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>

<div class="container">

<?php

$idea_fetch_cover = idea_fetch_cover($in['idea__id']);
$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
$metadata = unserialize($in['idea__metadata']);
$has_time_estimate = ( isset($metadata['idea___max_seconds']) && $metadata['idea___max_seconds']>0 );
$idea_type_meet_requirement = in_array($in['idea__type'], $this->config->item('sources_id_7309'));
$recipient_en = superpower_assigned();
$is_home_page = $in['idea__id']==config_var(12156);
if(!isset($recipient_en['source__id']) ){
    $recipient_en['source__id'] = 0;
}

//VIEW READ
$this->READ_model->create(array(
    'read__source' => $recipient_en['source__id'],
    'read__type' => 7610, //PLAYER VIEWED IDEA
    'read__left' => $in['idea__id'],
    'read__sort' => fetch_cookie_order('7610_'.$in['idea__id']),
));


//MESSAGES
$idea__messages = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type' => 4231, //IDEA NOTES Messages
    'read__right' => $in['idea__id'],
), array(), 0, 0, array('read__sort' => 'ASC'));


//NEXT IDEAS
$ideas_next = $this->READ_model->fetch(array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'read__left' => $in['idea__id'],
), array('idea_next'), 0, 0, array('read__sort' => 'ASC'));

$chapters = count($ideas_next);
$common_prefix = idea_calc_common_prefix($ideas_next, 'idea__title');



//ALREADY IN READS?
$completion_rate['completion_percentage'] = 0;
$read_idea_home = $this->READ_model->idea_home($in['idea__id'], $recipient_en);


if ($read_idea_home) {

    // % DONE
    $completion_rate = $this->READ_model->completion_progress($recipient_en['source__id'], $in);

    //Fetch progress history:
    $read_completes = $this->READ_model->fetch(array(
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'read__type IN (' . join(',', $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
        'read__source' => $recipient_en['source__id'],
        'read__left' => $in['idea__id'],
    ));


    if($idea_type_meet_requirement){

        //Reverse check answers to see if they have previously unlocked a path:
        $unlocked_connections = $this->READ_model->fetch(array(
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINKS
            'read__right' => $in['idea__id'],
            'read__source' => $recipient_en['source__id'],
        ), array('idea_previous'), 1);

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
                array_push($read_completes, $this->READ_model->is_complete($in, array(
                    'read__type' => $read_completion_type_id,
                    'read__source' => $recipient_en['source__id'],
                    'read__left' => $in['idea__id'],
                )));

            } else {

                //Oooops, we could not find it, report bug:
                $this->READ_model->create(array(
                    'read__type' => 4246, //Platform Bug Reports
                    'read__source' => $recipient_en['source__id'],
                    'read__message' => 'read_coin() found idea connector ['.$unlocked_connections[0]['read__type'].'] without a valid unlock method @12327',
                    'read__left' => $in['idea__id'],
                    'read__reference' => $unlocked_connections[0]['read__id'],
                ));

            }

        } else {

            //Try to find paths to unlock:
            $unlock_paths = $this->IDEA_model->unlock_paths($in);

            //Set completion method:
            if(!count($unlock_paths)){

                //No path found:
                array_push($read_completes, $this->READ_model->is_complete($in, array(
                    'read__type' => 7492, //TERMINATE
                    'read__source' => $recipient_en['source__id'],
                    'read__left' => $in['idea__id'],
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





//READ TITLE
echo '<h1 class="block-one" '.( !$recipient_en['source__id'] ? ' style="padding-top: 21px;" ' : '' ).'><span class="icon-block top-icon">'.view_idea_icon( $completion_rate['completion_percentage']>0 , $completion_rate['completion_percentage'] ).'</span><span class="title-block-lg">' . view_idea__title($in) . '</span></h1>';


//MESSAGES
foreach($idea__messages as $message_ln) {
    echo $this->READ_model->send_message(
        $message_ln['read__message'],
        $recipient_en
    );
}


if(!$read_idea_home){

    if($is_home_page){

        echo '<div class="cover-list" style="padding: 33px 0 33px 33px;">';
        if($chapters){
            //List Children:
            foreach($ideas_next as $key => $child_in){
                echo view_idea_cover($child_in, false, idea_calc_common_prefix($ideas_next, 'idea__title'));
            }
        }
        echo '</div>';

    } else {

        //METADATA
        $metadata = unserialize($in['idea__metadata']);



        //IDEA
        $idea_count = ( isset($metadata['idea___max_reads']) && $metadata['idea___max_reads']>=2 ? $metadata['idea___max_reads']-1 : $chapters );
        $idea_min = ( $idea_count && isset($metadata['idea___min_reads']) && $metadata['idea___min_reads']<$metadata['idea___max_reads'] ? $metadata['idea___min_reads']-1 : 0 );
        if ($idea_count) {

            $has_time = ( isset($metadata['idea___max_seconds']) && $metadata['idea___max_seconds']>0 );
            echo '<div class="read-topic idea"><a href="javascript:void(0);" onclick="$(\'.contentTabIdeas\').toggleClass(\'hidden\')" class="doupper"><span class="icon-block"><i class="fas fa-plus-circle contentTabIdeas"></i><i class="fas fa-minus-circle contentTabIdeas hidden"></i></span>'.$idea_count.' Idea'.view__s($idea_count).( $has_time ? ' IN '.view_time_range($metadata) : '' ).'</a></div>';

            //BODY
            echo '<div class="contentTabIdeas hidden" style="padding-bottom:21px;">';
            if($chapters > 0){
                //List Children:
                echo '<div class="list-group '.( !$recipient_en['source__id'] ? 'single-color' : '' ).'">';
                foreach($ideas_next as $key => $child_in){
                    echo view_idea_read($child_in, idea_calc_common_prefix($ideas_next, 'idea__title'));
                }
                echo '</div>';
            }
            echo '</div>';

        }




        //SOURCE
        $idea___experts = ( isset($metadata['idea___experts']) ? count($metadata['idea___experts']) : 0 );
        $idea___content = ( isset($metadata['idea___content']) ? count($metadata['idea___content']) : 0 );
        $source_count = $idea___experts + $idea___content;
        if ($source_count) {

            echo '<div class="read-topic source"><a href="javascript:void(0);" onclick="$(\'.contentTabExperts\').toggleClass(\'hidden\')" class="doupper"><span class="icon-block"><i class="fas fa-plus-circle contentTabExperts"></i><i class="fas fa-minus-circle contentTabExperts hidden"></i></span>'.$source_count.' Expert Source'.view__s($source_count).'</a></div>';

            echo '<div class="contentTabExperts hidden" style="padding-bottom:21px;">';
            if($idea_count > $chapters){
                echo '<p class="space-content">The '.$idea_count.' idea'.view__s($idea_count).' on '.$in['idea__title'].' were extracted and synthesized from these '.$source_count.' expert source'.view__s($source_count).':</p>';
            }
            echo '<div class="list-group single-color">';

            //Sort Expert Content
            if($idea___experts && $idea___content){
                $experts_content = array_merge($metadata['idea___content'], $metadata['idea___experts']);
            } elseif($idea___content){
                $experts_content = $metadata['idea___content'];
            } else {
                $experts_content = $metadata['idea___experts'];
            }
            usort($experts_content, 'sortByWeight');
            foreach ($experts_content as $source_source) {
                echo view_source_basic($source_source);
            }

            echo '</div>';
            echo '</div>';

        }


        //GET STARTED
        echo '<div class="inline-block margin-top-down read-add pull-right"><a class="btn btn-read btn-circle" href="/read/start/'.$in['idea__id'].'" title="'.$sources__11035[13008]['m_name'].'">'.$sources__11035[13008]['m_icon'].'</a></div>';

    }

} else {


    //PREVIOUSLY UNLOCKED:
    $unlocked_reads = $this->READ_model->fetch(array(
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        'read__type' => 6140, //READ UNLOCK LINK
        'read__source' => $recipient_en['source__id'],
        'read__left' => $in['idea__id'],
    ), array('idea_next'), 0);

    //Did we have any steps unlocked?
    if(count($unlocked_reads) > 0){
        view_idea_list($in, $unlocked_reads, $recipient_en, '<span class="icon-block"><i class="fas fa-lock-open"></i></span>UNLOCKED:', false);
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
            view_idea_list($in, $unlock_paths, $recipient_en, '<span class="icon-block">&nbsp;</span>SUGGESTED IDEAS:');

        }

        //List Children if any:
        view_idea_list($in, $ideas_next, $recipient_en, null, ( $completion_rate['completion_percentage'] < 100 ));


    } elseif (in_array($in['idea__type'], $this->config->item('sources_id_7712'))){

        //SELECT ANSWER

        //Has no children:
        if(!$chapters){

            //Mark this as complete since there is no child to choose from:
            if(!count($this->READ_model->fetch(array(
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__type IN (' . join(',' , $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
                'read__source' => $recipient_en['source__id'],
                'read__left' => $in['idea__id'],
            )))){

                array_push($read_completes, $this->READ_model->is_complete($in, array(
                    'read__type' => 4559, //READ MESSAGES
                    'read__source' => $recipient_en['source__id'],
                    'read__left' => $in['idea__id'],
                )));

            }

            view_idea_next_previous($in['idea__id'], $recipient_en);
            return true;

        } else {

            //First fetch answers based on correct order:
            $read_answers = array();
            foreach($this->READ_model->fetch(array(
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'read__left' => $in['idea__id'],
            ), array('idea_next'), 0, 0, array('read__sort' => 'ASC')) as $ln){
                //See if this answer was seleted:
                if(count($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINK
                    'read__left' => $in['idea__id'],
                    'read__right' => $ln['idea__id'],
                    'read__source' => $recipient_en['source__id'],
                )))){
                    array_push($read_answers, $ln);
                }
            }

            if(count($read_answers) > 0){
                //MODIFY ANSWER
                echo '<div class="edit_select_answer">';

                //List answers:
                view_idea_list($in, $read_answers, $recipient_en, '<span class="icon-block">&nbsp;</span>YOU ANSWERED:', false);

                echo '<div class="doclear">&nbsp;</div>';

                view_idea_next_previous($in['idea__id'], $recipient_en);

                echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-read btn-circle" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');"><i class="fas fa-pen"></i></a></div>';

                echo '<div class="doclear">&nbsp;</div>';

                echo '</div>';
            }


            echo '<div class="edit_select_answer '.( count($read_answers)>0 ? 'hidden' : '' ).'">';

            //HTML:
            if ($in['idea__type'] == 6684) {

                echo '<div class="read-topic"><span class="icon-block">&nbsp;</span>SELECT ONE:</div>';

            } elseif ($in['idea__type'] == 7231) {

                echo '<div class="read-topic"><span class="icon-block">&nbsp;</span>SELECT ONE OR MORE:</div>';

            }

            //Open for list to be printed:
            echo '<div class="list-group list-answers" idea__type="'.$in['idea__type'].'">';




            //List children to choose from:
            foreach($ideas_next as $key => $child_in) {

                //Has this been previously selected?
                $previously_selected = count($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINKS
                    'read__left' => $in['idea__id'],
                    'read__right' => $child_in['idea__id'],
                    'read__source' => $recipient_en['source__id'],
                )));

                echo '<a href="javascript:void(0);" onclick="select_answer('.$child_in['idea__id'].')" is-selected="'.( $previously_selected ? 1 : 0 ).'" answered_ins="'.$child_in['idea__id'].'" class="read_answer_'.$child_in['idea__id'].' answer-item list-group-item itemread no-left-padding">';


                echo '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';
                echo '<td class="icon-block check-icon" style="padding: 0 !important;"><i class="'.( $previously_selected ? 'fas' : 'far' ).' fa-circle read"></i></td>';

                echo '<td style="width:100%; padding: 0 !important;">';
                echo '<b class="montserrat idea-url" style="margin-left:0;">'.view_idea__title($child_in, $common_prefix).'</b>';
                echo '</td>';

                echo '</tr></table>';


                echo '</a>';
            }


            //Close list:
            echo '</div>';




            echo '<div class="result-update margin-top-down"></div>';

            echo view_idea_previous_read($in['idea__id'], $recipient_en);

            //Button to submit selection:
            if(count($read_answers)>0){
                echo '<div class="inline-block margin-top-down pull-left"><a class="btn btn-read btn-circle" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');"><i class="fas fa-arrow-left"></i></a></div>';
            }

            echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-read btn-circle" href="javascript:void(0)" onclick="read_answer()">'.$sources__11035[12211]['m_icon'].'</a></div>';

            echo '</div>';

        }

    } elseif ($in['idea__type'] == 6677) {

        //READ ONLY
        view_idea_list($in, $ideas_next, $recipient_en);

    } elseif ($in['idea__type'] == 6683) {

        //TEXT RESPONSE

        echo '<textarea class="border i_content padded read_input" placeholder="Write answer here" id="read_text_answer">'.( count($read_completes) ? trim($read_completes[0]['read__message']) : '' ).'</textarea>';

        echo '<div class="text_saving_result margin-top-down"></div>';

        //Show Previous Button:
        echo view_idea_previous_read($in['idea__id'], $recipient_en);

        //Save/Upload & Next:
        echo '<div class="margin-top-down inline-block pull-right"><a class="btn btn-read btn-circle" href="javascript:void(0);" onclick="read_text_answer()">'.$sources__11035[12211]['m_icon'].'</a></div>';


        if(count($read_completes)){
            //Next Ideas:
            view_idea_list($in, $ideas_next, $recipient_en, null,false);
        }

        echo '<script> $(document).ready(function () { autosize($(\'#read_text_answer\')); $(\'#read_text_answer\').focus(); }); </script>';


    } elseif (in_array($in['idea__type'], $this->config->item('sources_id_7751'))) {

        //FILE UPLOAD

        echo '<div class="playerUploader">';
        echo '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

        echo '<input class="inputfile" type="file" name="file" id="fileType'.$in['idea__type'].'" />';


        if(!count($read_completes)) {

            //Show Previous Button:
            echo '<div class="file_saving_result">';
            echo view_idea_previous_read($in['idea__id'], $recipient_en);
            echo '</div>';

            //Show next here but keep hidden until file is uploaded:
            echo '<div class="go_next_upload hidden">';
            view_idea_next_previous($in['idea__id'], $recipient_en);
            echo '</div>';

            echo '<div class="inline-block margin-top-down edit_select_answer pull-right"><label class="btn btn-read btn-circle inline-block" for="fileType'.$in['idea__type'].'"><i class="fad fa-cloud-upload-alt" style="margin-left: -4px;"></i></label></div>';

        } else {

            echo '<div class="file_saving_result">';

            echo '<div class="read-topic"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div><div class="previous_answer">'.$this->READ_model->send_message($read_completes[0]['read__message']).'</div>';

            echo '</div>';

            //Any child ideas?
            view_idea_list($in, $ideas_next, $recipient_en, null, true, false);

            echo '<div class="inline-block margin-top-down pull-right"><label class="btn btn-read inline-block btn-circle" for="fileType'.$in['idea__type'].'" style="margin-left:5px;"><i class="fad fa-cloud-upload-alt" style="margin-left: -4px;"></i></label></div>';

        }

        echo '<div class="doclear">&nbsp;</div>';
        echo '</form>';
        echo '</div>';


    } else {

        //UNKNOWN IDEA TYPE
        $this->READ_model->create(array(
            'read__type' => 4246, //Platform Bug Reports
            'read__source' => $recipient_en['source__id'],
            'read__message' => 'step_echo() unknown idea type source ID ['.$in['idea__type'].'] that could not be rendered',
            'read__left' => $in['idea__id'],
        ));

    }
}


//Share this button, only visible after saving:
echo '<div class="share-this hidden space-content">';
    echo '<div class="doclear">&nbsp;</div>';
    echo '<div style="padding-bottom:13px;">Share using:</div>';
    foreach($this->config->item('sources__13023') as $source__id => $m) {
        echo '<div class="icon-block"><div data-network="'.$m['m_desc'].'" data-url="'.$this->config->item('base_url').$in['idea__id'].'" data-title="'.$in['idea__title'].'" data-image="'.$idea_fetch_cover.'" class="st-custom-button" title="Share This Idea Using '.$m['m_name'].'">'.$m['m_icon'].'</div></div>';
    }
echo '</div>';


?>
</div>