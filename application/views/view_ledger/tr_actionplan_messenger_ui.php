<?php

//Is this an un-answered OR intent?
$en_all_6107 = $this->config->item('en_all_6107');
$or_answer_in_id = 0; //We would assume so unless proven otherwise...
$actionplan_non_responses = array(); //Will only be populated if an OR branch has already been answered
$is_step = (count($actionplan_parents) == 1); //This could be the top-level Action Plan Intent OR an Action Plan Step to get to the intent...

//Fetch OR Children since they are never added to Action Plans:
if($in['in_type']==1){

    if(count($actionplan_children) == 1){

        //Student has already responded to this OR branch, fetch non-responded intent to give students FYI view:
        $actionplan_non_responses = $this->Database_model->fn___tr_fetch(array(
            'tr_status' => 2, //Published
            'in_status' => 2, //Published
            'tr_type_entity_id' => 4228, //Fixed intent links only
            'tr_parent_intent_id' => $in['in_id'],
            'tr_child_intent_id !=' => $actionplan_children[0]['tr_child_intent_id'],
        ), array('in_child'), 0, 0, array('tr_order' => 'ASC')); //Child intents must be ordered

        /*
         *
         * Future Note/Warning:
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

    } elseif(count($actionplan_children) > 1){

        //This should never happen as we can only have a single response to an OR branch!

    } else {

        //Student has not yet responded, fetch OR children:
        $actionplan_children = $this->Database_model->fn___tr_fetch(array(
            'tr_status' => 2, //Published
            'in_status' => 2, //Published
            'tr_type_entity_id' => 4228, //Fixed intent links only
            'tr_parent_intent_id' => $in['in_id'],
        ), array('in_child'), 0, 0, array('tr_order' => 'ASC')); //Child intents must be ordered

        if(count($actionplan_children) < 1){
            //Ooooopsi, this OR branch does not have any children, so nothing can be done from the student side...
            //Treat it as an AND branch for now:
            $in['in_type'] = 0;
        } else {
            //All good, this OR branch has not yet been answered:
            $or_answer_in_id = $in['in_id'];
        }
    }
}


//Fetch completion requirements for this intent:
$message_in_requirements = $this->Matrix_model->fn___in_req_completion($in);
$time_estimate = fn___echo_time_range($in);


$has_children = (count($actionplan_children) > 0);
//We want to show the child intents in specific conditions to ensure a step-by-step navigation by the user through the browser Action Plan
//(Note that the conversational UI already has this step-by-step navigation in mind, but the user has more flexibility in the Browser side)
$list_children = (count($actionplan_parents) == 0 || !($actionplan_parents[0]['tr_status'] == 0) || $in['in_type']==1 || !$message_in_requirements);


if ($is_step) {
    //Inform the user of any completion requirements:
    $message_in_requirements = $this->Matrix_model->fn___in_req_completion($in);

    //Submission button visible after first button was clicked:
    $is_incomplete = ($actionplan_parents[0]['tr_status'] < 1 || ($actionplan_parents[0]['tr_status'] == 1 && count($actionplan_children) == 0));
    $show_written_input = ($message_in_requirements && $is_incomplete);
}


//Do we have a next item?
$next_button = null;
if ($actionplan['tr_status'] == 1) {
    //Active Action Plan, attempt to find next item, which we should be able to find:
    $next_ins = $this->Matrix_model->fn___actionplan_next_in($actionplan['tr_id']);
    if ($next_ins) {
        if ($next_ins[0]['in_id'] == $in['in_id']) {
            //$next_button = '<span style="font-size: 0.7em; padding-left:5px; display:inline-block;"><i class="fas fa-shield-check"></i> This is the next-in-line intent</span>';
            $next_button = null;
        } else {
            $next_button = '<a href="/messenger/actionplan/' . $next_ins[0]['in_id'] . '" class="btn ' . ($is_step && !$show_written_input && !$is_incomplete ? 'btn-md btn-primary' : 'btn-xs btn-black') . '" data-toggle="tooltip" data-placement="top" title="Next intent-in-line is to ' . $next_ins[0]['in_outcome'] . '">Next-in-line <i class="fas fa-angle-right"></i></a>';
        }
    }
}

//Include JS file:
echo '<script src="/js/custom/actionplan-master-js.js?v=v' . $this->config->item('app_version') . '" type="text/javascript"></script>';

//Fetch parent tree all the way to the top of Action Plan tr_child_intent_id
echo '<div class="list-group parent-actionplans" style="margin-top: 10px;">';
foreach ($actionplan_parents as $tr) {
    echo fn___echo_in_actionplan_step($tr, 1);
}
echo '</div>';


//Show title
echo '<h3 class="master-h3 primary-title">' . $in['in_outcome'] . '</h3>';
echo '<div class="sub_title">';

if ($is_step) {

    echo '<span class="status-label underdot" data-toggle="tooltip" data-placement="top" title="'.$en_all_6107[4559]['m_desc'].'">'.$en_all_6107[4559]['m_icon'].$en_all_6107[4559]['m_name'].'</span> &nbsp;&nbsp;';

    //Show completion progress for the single parent intent:

    echo fn___echo_fixed_fields('tr_student_status', $actionplan_parents[0]['tr_status'], false, 'top');
    if($time_estimate){
        echo ' &nbsp;&nbsp;<span class="status-label underdot" data-toggle="tooltip" data-placement="top" title="The estimated time to complete this '.$en_all_6107[4559]['m_name'].'"><i class="fas fa-alarm-clock"></i> ' . $time_estimate.'</span>';
    }

    //TODO Fetch/show Student responses?

} else {

    echo '<span class="status-label underdot" data-toggle="tooltip" data-placement="top" title="'.$en_all_6107[4235]['m_desc'].'">'.$en_all_6107[4235]['m_icon'].$en_all_6107[4235]['m_name'].'</span> &nbsp;&nbsp;';

    //This must be top level Action Plan, show Action Plan data:
    echo fn___echo_fixed_fields('tr_student_status', $actionplan['tr_status'], false, 'top');
    if($time_estimate){
        echo ' &nbsp;&nbsp;<span class="status-label underdot" data-toggle="tooltip" data-placement="top" title="The estimated time to complete this '.$en_all_6107[4559]['m_name'].'"><i class="fas fa-alarm-clock"></i> ' . $time_estimate.'</span>';
    }
}
echo '</div>';


//Show Published Messages:
foreach ($this->Database_model->fn___tr_fetch(array(
    'tr_status' => 2, //Published
    'tr_type_entity_id' => 4231, //Intent Note Messages
    'tr_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('tr_order' => 'ASC')) as $tr) {
    echo '<div class="tip_bubble">';
    echo $this->Chat_model->fn___dispatch_message($tr['tr_content'], $actionplan);
    echo '</div>';
}

//Show completion options below messages:
if ($is_step && ($message_in_requirements || ($in['in_type']==0 && !$has_children))) {

    if (!$show_written_input && !$is_incomplete && strlen($actionplan_parents[0]['tr_content']) > 0 /* For now only allow is complete */) {
        //Show button to make text visible:
        echo '<div class="left-grey"><a href="javascript:void(0);" onclick="$(\'.toggle_text\').toggle();" class="toggle_text btn btn-xs btn-black"><i class="fas fa-edit"></i> ' . ($is_incomplete ? 'Add Written Answer' : 'Modify Answer') . '</a></div>';
    }

    echo '<div class="left-grey">';
    echo '<form method="POST" action="/messenger/update_k_save">';

    echo '<input type="hidden" name="tr_id"  value="' . $actionplan_parents[0]['tr_id'] . '" />';

    echo '<div class="toggle_text" style="' . ($show_written_input ? '' : 'display:none; ') . '">';
    if ($message_in_requirements) {
        echo '<div style="color:#2b2b2b; font-size:0.7em; margin:0 !important; padding:0;"><i class="fas fa-exclamation-triangle"></i> ' . $message_in_requirements . '</div>';
    }
    echo '<textarea name="tr_content" class="form-control maxout" style="padding:5px !important; margin:0 !important;">' . $actionplan_parents[0]['tr_content'] . '</textarea>';
    echo '</div>';


    if ($has_children && !$list_children) {
        echo '<button type="submit" class="btn btn-primary"><i class="fas fa-check-square"></i> Got It, Continue <i class="fas fa-angle-right"></i></button>';
    } elseif ($is_incomplete) {
        echo '<button type="submit" name="fn___actionplan_next_in" value="1" class="btn btn-primary"><i class="fas fa-check-square"></i> Mark Complete & Go Next <i class="fas fa-angle-right"></i></button>';
    } elseif (!$show_written_input) {
        echo '<button type="submit" class="btn btn-primary toggle_text" style="display:none;"><i class="fas fa-edit"></i> Update Answer</button>';
    } else {
        echo '<button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Update Answer</button>';
    }

    echo '</form>';
    echo '</div>';

}



if ($has_children && $list_children) {
    echo '<div class="left-grey">';
    echo '<h5 class="badge badge-hy">' . ( $in['in_type']==1 /* OR Intent */ ? ( $or_answer_in_id ? 'Choose One:' : 'Choose One:' ) : 'Complete All:') . '</h5>';
    echo '<div class="list-group">';

    foreach ($actionplan_children as $tr) {
        echo fn___echo_in_actionplan_step($tr, 0, $or_answer_in_id);
    }

    //Do we have any non-response OR branches to also show?
    if(!$or_answer_in_id){
        //We might! Let's see:
       foreach($actionplan_non_responses as $tr){
           echo '<div class="list-group-item" style="text-decoration: line-through;">';
           echo '<span class="status-label" style="padding-bottom:1px;"><i class="fal fa-minus-square"></i></span> ';
           echo fn___echo_in_outcome($tr['in_outcome'], true);
           echo '</div>';
       }
    }

    echo '</div>';
    echo '</div>';
}


//Echo next button (if available):
echo $next_button;

//Give a skip option if not complete:
if ($is_step && in_array($actionplan_parents[0]['tr_status'], $this->config->item('tr_status_incomplete'))) {
    echo '<span class="skippable">or <a href="javascript:void(0);" onclick="confirm_skip(' . $actionplan['tr_id'] . ',' . $in['in_id'] . ',' . $actionplan_parents[0]['tr_id'] . ')">skip intent</a></span>';
}

?>