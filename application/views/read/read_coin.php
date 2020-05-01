<script>
    var in_loaded_id = <?= $in['in_id'] ?>;
</script>
<script src="/application/views/read/read_coin.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>

<div class="container">
<?php

//PLAYER
$recipient_en = superpower_assigned();
if(!isset($recipient_en['en_id']) ){
    $recipient_en['en_id'] = 0;
}

//VIEW TRANSACTION
$this->LEDGER_model->ln_create(array(
    'ln_creator_source_id' => $recipient_en['en_id'],
    'ln_type_source_id' => 7610, //PLAYER VIEWED IDEA
    'ln_previous_idea_id' => $in['in_id'],
    'ln_order' => fetch_cookie_order('7610_'.$in['in_id']),
));

//HOME PAGE?
$is_home_page = ( $in['in_id'] == config_var(12156) );
if($is_home_page){
    $en_all_11035 = $this->config->item('en_all_11035'); //NAVIGATION
    echo '<div class="read-topic show-min"><span class="icon-block">'.$en_all_11035[12581]['m_icon'].'</span>'.$en_all_11035[12581]['m_name'].'</div>';
}



//MESSAGES
$in__messages = $this->LEDGER_model->ln_fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'ln_type_source_id' => 4231, //IDEA NOTES Messages
    'ln_next_idea_id' => $in['in_id'],
), array(), 0, 0, array('ln_order' => 'ASC'));

//NEXT IDEAS
$in__next = $this->LEDGER_model->ln_fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'ln_previous_idea_id' => $in['in_id'],
), array('in_next'), 0, 0, array('ln_order' => 'ASC'));


$in_reads_list = false;
if($recipient_en['en_id'] > 0){

    //Fetch entire Bookshelf:
    $player_read_ids = $this->READ_model->read_ids($recipient_en['en_id']);

    if(in_array($in['in_id'], $player_read_ids)){

        $in_reads_list = true;

    } else {

        //Go through parents ideas and detect intersects with user ideas. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
        foreach($this->IDEA_model->in_recursive_parents($in['in_id']) as $grand_parent_ids) {

            //Does this parent and its grandparents have an intersection with the user ideas?
            if (array_intersect($grand_parent_ids, $player_read_ids)) {
                //Idea is part of their Bookshelf:
                $in_reads_list = true;
                break;
            }
        }
    }
}



/*
 *
 * Determine next Reads
 *
 */
if(!$in_reads_list){

    //IDEA TITLE
    echo '<h1 class="block-one"><span class="icon-block top-icon"><i class="fas fa-circle read"></i></span><span class="title-block-lg">' . echo_in_title($in) . '</span></h1>';


    foreach($in__messages as $message_ln) {
        echo $this->COMMUNICATION_model->send_message(
            $message_ln['ln_content'],
            $recipient_en
        );
    }

    $is_or = in_array($in['in_type_source_id'], $this->config->item('en_ids_6193'));

    if($is_or){
        $all_child_featured = true;
        foreach($in__next as $key => $child_in){
            if(!in_array($child_in['in_status_source_id'], $this->config->item('en_ids_12138'))){
                $all_child_featured = false;
                break;
            }
        }
    } else {
        $all_child_featured = false;
    }







    if($all_child_featured){

        echo '<div class="cover-list" style="padding: 33px 0 33px 33px;">';
        if(count($in__next) > 0){
            //List Children:
            foreach($in__next as $key => $child_in){
                echo echo_in_cover($child_in, false, in_calc_common_prefix($in__next, 'in_title'));
            }
        }
        echo '</div>';

    } else {

        //IDEA METADATA
        $metadata = unserialize($in['in_metadata']);
        $has_time = ( isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0 );
        $has_idea = ( isset($metadata['in__metadata_max_steps']) && $metadata['in__metadata_max_steps']>=2 );

        if ($has_time || $has_idea || count($in__next)) {

            if($has_idea){
                $metadata['in__metadata_max_steps']--;//Do not include the main idea itself
            }

            echo '<div class="read-topic skip-grey"><a href="javascript:void(0);" onclick="$(\'.contentTabIdeas\').toggleClass(\'hidden\')" class="idea doupper"><span class="icon-block"><i class="fas fa-plus-circle contentTabIdeas idea"></i><i class="fas fa-minus-circle contentTabIdeas hidden idea"></i></span>'.( $has_idea ? $metadata['in__metadata_max_steps'].' Idea'.echo__s($metadata['in__metadata_max_steps']) : '' ).( $has_time ? ( $has_idea ? ' in ' : '' ).echo_time_hours($metadata['in__metadata_max_seconds']) : '' ).'</a></div>';

            //BODY
            echo '<div class="contentTabIdeas hidden" style="padding-bottom:21px;">';
            if(count($in__next) > 0){
                //List Children:
                echo '<div class="list-group">';
                foreach($in__next as $key => $child_in){
                    echo echo_in_read($child_in, $is_or, in_calc_common_prefix($in__next, 'in_title'));
                }
                echo '</div>';
            }
            echo '</div>';

        }


        //Expert References?
        $source_count = ( isset($metadata['in__metadata_experts']) ? count($metadata['in__metadata_experts']) : 0 );
        if(isset($metadata['in__metadata_sources'])){
            foreach($metadata['in__metadata_sources'] as $channel_id => $channel_contents){
                $source_count += count($channel_contents);
            }
        }
        if ($source_count > 0) {

            echo '<div class="read-topic skip-grey"><a href="javascript:void(0);" onclick="$(\'.contentTabExperts\').toggleClass(\'hidden\')" class="source doupper"><span class="icon-block"><i class="fas fa-plus-circle contentTabExperts source"></i><i class="fas fa-minus-circle contentTabExperts source hidden"></i></span>'.$source_count.' Expert Source'.echo__s($source_count).'</a></div>';

            echo '<div class="contentTabExperts hidden" style="padding-bottom:21px;">';
            echo '<div class="list-group single-color">';

            if(isset($metadata['in__metadata_experts'])){
                foreach($metadata['in__metadata_experts'] as $expert){
                    echo echo_en_basic($expert);
                }
            }

            if(isset($metadata['in__metadata_sources'])) {
                foreach ($metadata['in__metadata_sources'] as $channel_id => $channel_contents) {
                    foreach ($channel_contents as $channel_content) {
                        echo echo_en_basic($channel_content);
                    }
                }
            }

            echo '</div>';
            echo '</div>';

        }


        //Redirect to login page:
        echo '<div class="inline-block margin-top-down read-add pull-right"><a class="btn btn-read btn-circle" href="/read/start/'.$in['in_id'].'"><i class="fas fa-step-forward"></i></a></div>';

    }

    echo '<div class="doclear">&nbsp;</div>';

    return true;
}



/*
 * Previously in source's Bookshelf...
 *
 */





//Fetch progress history:
$read_completes = $this->LEDGER_model->ln_fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
    'ln_creator_source_id' => $recipient_en['en_id'],
    'ln_previous_idea_id' => $in['in_id'],
));


$qualify_for_autocomplete = ( isset($_GET['check_if_empty']) && !count($in__next) || (count($in__next)==1 && $in['in_type_source_id'] == 6677)) && !count($in__messages) && !in_array($in['in_type_source_id'], $this->config->item('en_ids_12324'));


//Is it incomplete & can it be instantly marked as complete?
if (!count($read_completes) && in_array($in['in_type_source_id'], $this->config->item('en_ids_12330'))) {

    //We might be able to complete it now:
    //It can, let's process it accordingly for each type within @12330
    if ($in['in_type_source_id'] == 6677 && $qualify_for_autocomplete) {

        //They should read and then complete...
        array_push($read_completes, $this->READ_model->read_is_complete($in, array(
            'ln_type_source_id' => 4559, //READ MESSAGES
            'ln_creator_source_id' => $recipient_en['en_id'],
            'ln_previous_idea_id' => $in['in_id'],
        )));

    } elseif (in_array($in['in_type_source_id'], array(6914,6907))) {

        //Reverse check answers to see if they have previously unlocked a path:
        $unlocked_connections = $this->LEDGER_model->ln_fetch(array(
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINKS
            'ln_next_idea_id' => $in['in_id'],
            'ln_creator_source_id' => $recipient_en['en_id'],
        ), array('in_previous'), 1);

        if(count($unlocked_connections) > 0){

            //They previously have unlocked a path here!

            //Determine READ COIN type based on it's connection type's parents that will hold the appropriate read coin.
            $read_completion_type_id = 0;
            foreach($this->config->item('en_all_12327') /* READ UNLOCKS */ as $en_id => $m){
                if(in_array($unlocked_connections[0]['ln_type_source_id'], $m['m_parents'])){
                    $read_completion_type_id = $en_id;
                    break;
                }
            }

            //Could we determine the coin type?
            if($read_completion_type_id > 0){

                //Yes, Issue coin:
                array_push($read_completes, $this->READ_model->read_is_complete($in, array(
                    'ln_type_source_id' => $read_completion_type_id,
                    'ln_creator_source_id' => $recipient_en['en_id'],
                    'ln_previous_idea_id' => $in['in_id'],
                )));

            } else {

                //Oooops, we could not find it, report bug:
                $this->LEDGER_model->ln_create(array(
                    'ln_type_source_id' => 4246, //Platform Bug Reports
                    'ln_creator_source_id' => $recipient_en['en_id'],
                    'ln_content' => 'read_coin() found idea connector ['.$unlocked_connections[0]['ln_type_source_id'].'] without a valid unlock method @12327',
                    'ln_previous_idea_id' => $in['in_id'],
                    'ln_parent_transaction_id' => $unlocked_connections[0]['ln_id'],
                ));

            }

        } else {

            //Try to find paths to unlock:
            $unlock_paths = $this->IDEA_model->in_unlock_paths($in);

            //Set completion method:
            if(!count($unlock_paths)){

                //No path found:
                array_push($read_completes, $this->READ_model->read_is_complete($in, array(
                    'ln_type_source_id' => 7492, //TERMINATE
                    'ln_creator_source_id' => $recipient_en['en_id'],
                    'ln_previous_idea_id' => $in['in_id'],
                )));


            }
        }
    }
}


// % DONE
$completion_rate = $this->READ_model->read_completion_progress($recipient_en['en_id'], $in);
$metadata = unserialize($in['in_metadata']);
$has_time_estimate = ( isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0 );


echo '<div class="hideIfEmpty main_reads_top"></div>';

//READ PROGRESS
if($completion_rate['completion_percentage']>0){
    echo '<div class="progress-bg-list no-horizonal-margin" title="Read '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
} else {
    //Replace with empty space:
    echo '<div class="high3x">&nbsp;</div>';
}

//READ TITLE
echo '<h1 class="block-one"><span class="icon-block top-icon"><i class="fas fa-circle read" aria-hidden="true"></i></span><span class="title-block-lg">' . echo_in_title($in) . '</span></h1>';



foreach($in__messages as $message_ln) {
    echo $this->COMMUNICATION_model->send_message(
        $message_ln['ln_content'],
        $recipient_en
    );
}



if(count($read_completes) && $qualify_for_autocomplete){
    //Move to the next one as there is nothing to do here:
    echo "<script> $(document).ready(function () { window.location = '/read/next/' + in_loaded_id + '".( isset($_GET['previous_read']) && $_GET['previous_read']>0 ? '?previous_read='.$_GET['previous_read'] : '' )."'; }); </script>";
}




//PREVIOUSLY UNLOCKED:
$unlocked_steps = $this->LEDGER_model->ln_fetch(array(
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
    'ln_type_source_id' => 6140, //READ UNLOCK LINK
    'ln_creator_source_id' => $recipient_en['en_id'],
    'ln_previous_idea_id' => $in['in_id'],
), array('in_next'), 0);

//Did we have any steps unlocked?
if(count($unlocked_steps) > 0){
    echo_in_list($in, $unlocked_steps, $recipient_en, '<span class="icon-block"><i class="fas fa-lock-open"></i></span>UNLOCKED:', false);
}




//LOCKED
if (in_array($in['in_type_source_id'], $this->config->item('en_ids_7309'))) {


    //Requirement lock
    if(!count($read_completes) && !count($unlocked_connections) && count($unlock_paths)){

        //List Unlock paths:
        echo_in_list($in, $unlock_paths, $recipient_en, '<span class="icon-block"><i class="fas fa-step-forward"></i></span>SUGGESTED IDEAS:');

    }

    //List Children if any:
    echo_in_list($in, $in__next, $recipient_en, null, ( $completion_rate['completion_percentage'] < 100 ));


} elseif (in_array($in['in_type_source_id'], $this->config->item('en_ids_7712'))){

    //SELECT ANSWER

    //Has no children:
    if(!count($in__next)){

        //Mark this as complete since there is no child to choose from:
        if(!count($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',' , $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
            'ln_creator_source_id' => $recipient_en['en_id'],
            'ln_previous_idea_id' => $in['in_id'],
        )))){

            array_push($read_completes, $this->READ_model->read_is_complete($in, array(
                'ln_type_source_id' => 4559, //READ MESSAGES
                'ln_creator_source_id' => $recipient_en['en_id'],
                'ln_previous_idea_id' => $in['in_id'],
            )));

        }

        echo_in_next_previous($in['in_id'], $recipient_en);
        return true;

    } else {

        //First fetch answers based on correct order:
        $read_answers = array();
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'ln_previous_idea_id' => $in['in_id'],
        ), array('in_next'), 0, 0, array('ln_order' => 'ASC')) as $ln){
            //See if this answer was seleted:
            if(count($this->LEDGER_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINK
                'ln_previous_idea_id' => $in['in_id'],
                'ln_next_idea_id' => $ln['in_id'],
                'ln_creator_source_id' => $recipient_en['en_id'],
            )))){
                array_push($read_answers, $ln);
            }
        }

        if(count($read_answers) > 0){
            //MODIFY ANSWER
            echo '<div class="edit_select_answer">';

            //List answers:
            echo_in_list($in, $read_answers, $recipient_en, '<span class="icon-block">&nbsp;</span>YOU ANSWERED:', false);

            echo '<div class="doclear">&nbsp;</div>';

            echo_in_next_previous($in['in_id'], $recipient_en);

            echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-read btn-circle" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');"><i class="fas fa-pen"></i></a></div>';

            echo '<div class="doclear">&nbsp;</div>';

            echo '</div>';
        }


        echo '<div class="edit_select_answer '.( count($read_answers)>0 ? 'hidden' : '' ).'">';

        //HTML:
        if ($in['in_type_source_id'] == 6684) {

            echo '<div class="read-topic"><span class="icon-block">&nbsp;</span>SELECT ONE:</div>';

        } elseif ($in['in_type_source_id'] == 7231) {

            echo '<div class="read-topic"><span class="icon-block">&nbsp;</span>SELECT ONE OR MORE:</div>';

        }

        //Open for list to be printed:
        echo '<div class="list-group list-answers" in_type_source_id="'.$in['in_type_source_id'].'">';




        //Determine Prefix:
        $common_prefix = in_calc_common_prefix($in__next, 'in_title');


        //List children to choose from:
        foreach($in__next as $key => $child_in) {

            //Has this been previously selected?
            $previously_selected = count($this->LEDGER_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINKS
                'ln_previous_idea_id' => $in['in_id'],
                'ln_next_idea_id' => $child_in['in_id'],
                'ln_creator_source_id' => $recipient_en['en_id'],
            )));


            echo '<a href="javascript:void(0);" onclick="select_answer('.$child_in['in_id'].')" is-selected="'.( $previously_selected ? 1 : 0 ).'" answered_ins="'.$child_in['in_id'].'" class="ln_answer_'.$child_in['in_id'].' answer-item list-group-item itemread no-left-padding">';


            echo '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';
            echo '<td class="icon-block check-icon" style="padding: 0 !important;"><i class="'.( $previously_selected ? 'fas' : 'far' ).' fa-circle read"></i></td>';

            echo '<td style="width: 100%; padding: 0 !important;">';
            echo '<b class="montserrat idea-url" style="margin-left:0;">'.echo_in_title($child_in, false, $common_prefix).'</b>';
            echo '</td>';

            echo '</tr></table>';


            echo '</a>';
        }


        //Close list:
        echo '</div>';




        echo '<div class="result-update margin-top-down"></div>';

        echo echo_in_previous_read($in['in_id'], $recipient_en);

        //Button to submit selection:
        if(count($read_answers)>0){
            echo '<div class="inline-block margin-top-down pull-left"><a class="btn btn-read btn-circle" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');"><i class="fas fa-arrow-left"></i></a></div>';
        }

        echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-read btn-circle" href="javascript:void(0)" onclick="read_answer()"><i class="fas fa-step-forward"></i></a></div>';

        echo '</div>';

    }

} else {

    if ($in['in_type_source_id'] == 6677) {

        //READ ONLY

        //Next Ideas:
        echo_in_list($in, $in__next, $recipient_en);

    } elseif ($in['in_type_source_id'] == 6683) {

        //TEXT RESPONSE

        echo '<textarea class="border i_content padded read_input" placeholder="Your Answer Here..." id="read_text_answer">'.( count($read_completes) ? trim($read_completes[0]['ln_content']) : '' ).'</textarea>';

        echo '<div class="text_saving_result margin-top-down"></div>';

        //Show Previous Button:
        echo echo_in_previous_read($in['in_id'], $recipient_en);

        //Save/Upload & Next:
        echo '<div class="margin-top-down inline-block pull-right"><a class="btn btn-read btn-circle" href="javascript:void(0);" onclick="read_text_answer()"><i class="fas fa-step-forward"></i></a></div>';


        if(count($read_completes)){
            //Next Ideas:
            echo_in_list($in, $in__next, $recipient_en, null,false);
        }

        echo '<script> $(document).ready(function () { autosize($(\'#read_text_answer\')); $(\'#read_text_answer\').focus(); }); </script>';


    } elseif (in_array($in['in_type_source_id'], $this->config->item('en_ids_7751'))) {

        //FILE UPLOAD

        echo '<div class="playerUploader">';
        echo '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

        echo '<input class="inputfile" type="file" name="file" id="fileType'.$in['in_type_source_id'].'" />';


        if(!count($read_completes)) {

            //Show Previous Button:
            echo '<div class="file_saving_result">';
            echo echo_in_previous_read($in['in_id'], $recipient_en);
            echo '</div>';

            //Show next here but keep hidden until file is uploaded:
            echo '<div class="go_next_upload hidden">';
            echo_in_next_previous($in['in_id'], $recipient_en);
            echo '</div>';

            echo '<div class="inline-block margin-top-down edit_select_answer pull-right"><label class="btn btn-read btn-circle inline-block" for="fileType'.$in['in_type_source_id'].'"><i class="fad fa-cloud-upload-alt" style="margin-left: -4px;"></i></label></div>';

        } else {

            echo '<div class="file_saving_result">';

            echo '<div class="read-topic"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div><div class="previous_answer">'.$this->COMMUNICATION_model->send_message($read_completes[0]['ln_content']).'</div>';

            echo '</div>';

            //Any child ideas?
            echo_in_list($in, $in__next, $recipient_en, null, true, false);

            echo '<div class="inline-block margin-top-down pull-right"><label class="btn btn-read inline-block btn-circle" for="fileType'.$in['in_type_source_id'].'" style="margin-left:5px;"><i class="fad fa-cloud-upload-alt" style="margin-left: -4px;"></i></label></div>';

        }

        echo '<div class="doclear">&nbsp;</div>';
        echo '</form>';
        echo '</div>';


    } else {

        //UNKNOWN IDEA TYPE
        $this->LEDGER_model->ln_create(array(
            'ln_type_source_id' => 4246, //Platform Bug Reports
            'ln_creator_source_id' => $recipient_en['en_id'],
            'ln_content' => 'step_echo() unknown idea type source ID ['.$in['in_type_source_id'].'] that could not be rendered',
            'ln_previous_idea_id' => $in['in_id'],
        ));

    }

}

?>
</div>