<?php

//Is this an un-answered OR intent?
$is_step = (count($actionplan_parents) > 0); //This could be the top-level Student Intent OR an Completed Step to get to the intent...
$has_children = (count($actionplan_children) > 0);
$is_or_branch = ( $in['in_type']==1 );
$or_path_chosen = ( $is_or_branch && $has_children ); //Or branches do not have a children until students choose one...
$message_in_requirements = $this->Platform_model->in_req_completion($in); //Fetch completion requirements for this intent
$time_estimate = echo_time_range($in);
$show_children = ( $is_or_branch || ( $has_children && ( !$message_in_requirements || $actionplan_parents[0]['ln_status']==2 ) ) );
$force_linear = true; //If true, will force students to complete AND branches linearly

//We want to show the child intents in specific conditions to ensure a step-by-step navigation by the user through the browser Action Plan
//(Note that the conversational UI already has this step-by-step navigation in mind, but the user has more flexibility in the Browser side)


if ($is_step) {
    //Inform the user of any completion requirements:
    $message_in_requirements = $this->Platform_model->in_req_completion($in);

    //Submission button visible after first button was clicked:
    $is_incomplete = ($actionplan_parents[0]['ln_status'] < 1 || ($actionplan_parents[0]['ln_status'] == 1 && !$has_children));
    $show_written_input = ($message_in_requirements && $is_incomplete);
}


//Do we have a next item?
$next_button = null;
if ($actionplan['ln_status'] == 1) {
    //Active Action Plan, attempt to find next item, which we should be able to find:
    $next_in_id = $this->Platform_model->actionplan_next_step($session_en['en_id'], false);
    if ($next_in_id > 0 && $next_in_id != $in['in_id'] ) {
        $next_button = '<a href="/messenger/actionplan/' . $next_in_id . '" class="btn ' . ($is_step && !$show_written_input && !$is_incomplete ? 'btn-md btn-primary' : 'btn-xs btn-black') . '">Next Step <i class="fas fa-angle-right"></i></a>';
    }
}


//Fetch parent tree all the way to the top of Action Plan ln_child_intent_id
echo '<div class="list-group parent-actionplans" style="margin-top: 10px;">';
if($is_step){
    foreach ($actionplan_parents as $ln) {
        echo echo_in_actionplan_step($ln, 1, 1);
    }
} else {
    //Show link to Action Plan if we have 1+ intentions:
    $student_intents = $this->Database_model->ln_fetch(array(
        'ln_miner_entity_id' => $session_en['en_id'],
        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6147')) . ')' => null, //Action Plan Intentions
        'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
        'in_status' => 2, //Published
    ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

    if(count($student_intents) > 1){
        echo '<a href="/messenger/actionplan" class="list-group-item">';
        echo '<span class="pull-left">';
        echo '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-left"></i> '.count($student_intents).'</span>';
        echo '</span>';
        echo ' Student Intents';
        echo '</a>';
    }
}

echo '</div>';


//Show title
echo '<h3 class="master-h3 primary-title">' . $in['in_outcome'] . '</h3>';
echo '<div class="sub_title">';

if ($is_step) {

    //Show completion progress for the single parent intent:

    echo echo_fixed_fields('ln_student_status', $actionplan_parents[0]['ln_status'], false, 'top');
    if($time_estimate){
        echo ' &nbsp;&nbsp;<span class="status-label underdot" data-toggle="tooltip" data-placement="top" title="The estimated time to complete"><i class="fas fa-alarm-clock"></i> ' . $time_estimate.'</span>';
    }

    //TODO Fetch/show Student responses?

} else {

    //This must be top level Action Plan, show Action Plan data:
    echo echo_fixed_fields('ln_student_status', $actionplan['ln_status'], false, 'top');
    if($time_estimate){
        echo ' &nbsp;&nbsp;<span class="status-label underdot" data-toggle="tooltip" data-placement="top" title="The estimated time to complete"><i class="fas fa-alarm-clock"></i> ' . $time_estimate.'</span>';
    }
}
echo '</div>';


//Show Published Messages:
foreach ($this->Database_model->ln_fetch(array(
    'ln_status' => 2, //Published
    'ln_type_entity_id' => 4231, //Intent Note Messages
    'ln_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {
    echo '<div class="tip_bubble">';
    echo $this->Chat_model->dispatch_message($ln['ln_content'], $actionplan);
    echo '</div>';
}

//Show completion options below messages:
if ($is_step && ($message_in_requirements || ($in['in_type']==0 && !$has_children))) {

    if (!$show_written_input && !$is_incomplete && strlen($actionplan_parents[0]['ln_content']) > 0 /* For now only allow is complete */) {
        //Show button to make text visible:
        echo '<div class="left-grey"><a href="javascript:void(0);" onclick="$(\'.toggle_text\').toggle();" class="toggle_text btn btn-xs btn-black"><i class="fas fa-edit"></i> ' . ($is_incomplete ? 'Add Written Answer' : 'Modify Answer') . '</a></div>';
    }

    echo '<div class="left-grey">';
    echo '<form method="POST" action="/messenger/actionplan_update_step">';

    echo '<input type="hidden" name="ln_id"  value="' . $actionplan_parents[0]['ln_id'] . '" />';

    echo '<div class="toggle_text" style="' . ($show_written_input ? '' : 'display:none; ') . '">';
    if ($message_in_requirements) {
        echo '<div style="color:#2b2b2b; font-size:0.7em; margin:0 !important; padding:0;"><i class="fas fa-exclamation-triangle"></i> ' . $message_in_requirements . '</div>';
    }
    echo '<textarea name="ln_content" class="form-control maxout" style="padding:5px !important; margin:0 !important;">' . $actionplan_parents[0]['ln_content'] . '</textarea>';
    echo '</div>';


    if (!$show_children) {
        echo '<button type="submit" class="btn btn-primary"><i class="fas fa-check-square"></i> Got It, Continue <i class="fas fa-angle-right"></i></button>';
    } elseif ($is_incomplete) {
        echo '<button type="submit" name="fetch_next_step" value="1" class="btn btn-primary"><i class="fas fa-check-square"></i> Mark Complete & Go Next <i class="fas fa-angle-right"></i></button>';
    } elseif (!$show_written_input) {
        echo '<button type="submit" class="btn btn-primary toggle_text" style="display:none;"><i class="fas fa-edit"></i> Update Answer</button>';
    } else {
        echo '<button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Update Answer</button>';
    }

    echo '</form>';
    echo '</div>';

}



if ($show_children) {

    echo '<div class="left-grey">';
    echo '<h5 class="badge badge-hy">' . ( $is_or_branch && !$or_path_chosen ? 'Choose One:' : 'Complete All:' ) . '</h5>';
    echo '<div class="list-group">';


    if($is_or_branch){

        if($or_path_chosen){

            //List selected response:
            foreach ($actionplan_children as $ln) {
                echo echo_in_actionplan_step($ln, 0, 1);
            }

            //Line non-selected responses for FYI purposes:
            foreach($this->Database_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'in_status' => 2, //Published
                'ln_type_entity_id' => 4228, //Fixed intent links only
                'ln_parent_intent_id' => $in['in_id'],
                'ln_child_intent_id !=' => $actionplan_children[0]['ln_child_intent_id'],
            ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $ln){
                echo '<div class="list-group-item" style="text-decoration: line-through;">';
                echo '<span class="status-label" style="padding-bottom:1px;"><i class="fal fa-minus-square"></i></span> ';
                echo echo_in_outcome($ln['in_outcome'], true);
                echo '</div>';
            }

            /*
             *
             * Note/Warning:
             *
             * Student might see OR branch options that
             * they had not seen when making their selection.
             * For now, this is ok as its best to give them
             * more transparency than to not give it at all.
             * In the future, we can allow them to adjust
             * their response and even see which new options
             * have become available for this OR branch...
             *
             * */

        } else {

            //List all possible responses to enable student to choose:
            foreach ($this->Database_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'in_status' => 2, //Published
                'ln_type_entity_id' => 4228, //Fixed intent links only
                'ln_parent_intent_id' => $in['in_id'],
            ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $ln) {
                echo echo_in_actionplan_or_choose($in['in_id'], $actionplan['ln_parent_link_id'], $ln);
            }

        }

    } else {

        //AND branch:
        $incomplete_step = 0;
        foreach ($actionplan_children as $ln) {
            if($ln['ln_status'] < 2){
                $incomplete_step++;
            }
            echo echo_in_actionplan_step($ln, 0, ( $force_linear ? $incomplete_step : 1 ));
        }

    }






    echo '</div>';
    echo '</div>';
}


//Echo next button (if available):
echo $next_button;

//Give a skip option if not complete:
if ($is_step && in_array($actionplan_parents[0]['ln_status'], $this->config->item('ln_status_incomplete'))) {
    echo '<span class="skippable">or <a href="javascript:void(0);" onclick="confirm_skip(' . $in['in_id'] . ', ' . $session_en['en_id'] . ')">Skip this step</a></span>';
}

?>