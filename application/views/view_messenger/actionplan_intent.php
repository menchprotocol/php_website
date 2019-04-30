<?php

//$in & $student_intents is passed for all the top-level intent...

//Prep student intention ids array:
$student_in_ids = array();
foreach($student_intents as $student_in){
    array_push($student_in_ids, $student_in['in_id']);
}


//Set variables:
$is_or_branch = ( $in['in_type']==1 );

$in__children = $this->Database_model->ln_fetch(array(
    'ln_status' => 2, //Published
    'in_status' => 2, //Published
    'ln_type_entity_id' => 4228, //Fixed Intent Links
    'ln_parent_intent_id' => $in['in_id'],
), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
$has_children = (count($in__children) > 0);

//Fetch student progression data:
$progression_steps = $this->Database_model->ln_fetch(array(
    'ln_type_entity_id IN (' . ( $is_or_branch ? 6157 /* Question Answered */ : join(',', $this->config->item('en_ids_6146')) ) . ')' => null, //Action Plan Progression Link Types
    'ln_miner_entity_id' => $session_en['en_id'],
    'ln_parent_intent_id' => $in['in_id'],
    'ln_status' => 2, //Published
));
$is_incomplete = ( count($progression_steps)==0 );


$recursive_parent_ins = $this->Platform_model->in_fetch_recursive_parents($in['in_id'], 2);
$message_in_requirements = $this->Platform_model->in_req_completion($in);
$time_estimate = echo_time_range($in);
$show_children = ( $has_children && ( $is_or_branch || !$message_in_requirements ) );

//Submission button visible after first button was clicked:
$show_written_input = ($message_in_requirements && $is_incomplete);


//Fetch parent tree all the way to the top of Action Plan ln_child_intent_id
echo '<div class="list-group parent-actionplans" style="margin-top: 10px;">';
foreach ($recursive_parent_ins as $parent_in_id => $grand_parent_ids) {
    //Does this parent and its grandparents have an intersection with the student intentions?
    if(array_intersect($grand_parent_ids, $student_in_ids)){
        //Fetch parent intent & show:
        $parent_ins = $this->Database_model->in_fetch(array(
            'in_id' => $parent_in_id,
        ));

        //See if parent is complete:
        $parent_progression_steps = $this->Database_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
            'ln_miner_entity_id' => $session_en['en_id'],
            'ln_parent_intent_id' => $parent_in_id,
            'ln_status >=' => 0,
        ));

        echo echo_in_actionplan_step($parent_ins[0], 1, (count($parent_progression_steps) > 0 ? $parent_progression_steps[0]['ln_status'] : 0));
    }
}
echo '</div>';


//Show title
echo '<h3 class="master-h3 primary-title">' . $in['in_outcome'] . '</h3>';
echo '<div class="sub_title">';

//Show completion progress for the single parent intent:

if($time_estimate){
    echo ' &nbsp;&nbsp;<span class="status-label underdot" data-toggle="tooltip" data-placement="top" title="The estimated time to complete"><i class="fas fa-alarm-clock"></i> ' . $time_estimate.'</span>';
}

//Show completion requirements if not OR branch (We do not want to influence the student's response)
if($in['in_requirement_entity_id'] != 6087){
    $en_all_4331 = $this->config->item('en_all_4331');
    //This has a completion requirement, show it:
    echo '&nbsp;&nbsp;<span class="status-label underdot">'.$en_all_4331[$in['in_requirement_entity_id']]['m_icon'].' '.$en_all_4331[$in['in_requirement_entity_id']]['m_name'].' Response</span>';
}


echo '</div>';





//Show main messages:
$advance_step = $this->Platform_model->actionplan_advance_step(array('en_id' => $en_id), $ins[0]['in_id'], true);
if($advance_step['status']){
    //All good, show messages:
    echo $advance_step['message'];
} else {
    //Ooooops, show error:
    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: '.$advance_step['message'].'</div>';
}


//TODO Fetch/show Student responses?




//Show completion options below messages:
if ($message_in_requirements || ($in['in_type']==0 && !$has_children)) {

    if (!$show_written_input && !$is_incomplete) {
        //Show button to make text visible:
        echo '<div class="left-grey"><a href="javascript:void(0);" onclick="$(\'.toggle_text\').toggle();" class="toggle_text btn btn-xs btn-black"><i class="fas fa-edit"></i> ' . ($is_incomplete ? 'Add Written Answer' : 'Modify Answer') . '</a></div>';
    }

    echo '<div class="left-grey">';
    echo '<form method="POST" action="/messenger/actionplan_update_step">';

    //echo '<input type="hidden" name="ln_id"  value="' . 0 . '" />';

    echo '<div class="toggle_text" style="' . ($show_written_input ? '' : 'display:none; ') . '">';
    if ($message_in_requirements) {
        echo '<div style="color:#2b2b2b; font-size:0.7em; margin:0 !important; padding:0;"><i class="fas fa-exclamation-triangle"></i> ' . $message_in_requirements . '</div>';
    }
    //echo '<textarea name="ln_content" class="form-control maxout" style="padding:5px !important; margin:0 !important;">' . 0 . '</textarea>';
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


//Echo next button (if available):
$next_in_id = $this->Platform_model->actionplan_find_next_step($session_en['en_id'], false);
if ($next_in_id > 0 && $next_in_id != $in['in_id'] ) {
    echo '<a href="/messenger/actionplan/' . $next_in_id . '" class="btn ' . (!$show_written_input && !$is_incomplete ? 'btn-md btn-primary' : 'btn-xs btn-black') . '">Next Step <i class="fas fa-angle-right"></i></a>';
}

?>