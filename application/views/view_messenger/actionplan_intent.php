<script>
    //Set global variables:
    var en_miner_id = <?= $session_en['en_id'] ?>;
</script>
<script src="/js/custom/messenger-actionplan-progress.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>

<?php

//$in & $student_intents is passed for all the top-level intent...


$time_estimate = echo_time_range($in);


//Prep student intention ids array:
$student_in_ids = array();
foreach($student_intents as $student_in){
    array_push($student_in_ids, $student_in['in_id']);
}

//Fetch parent tree all the way to the top of Action Plan ln_child_intent_id
echo '<div class="list-group parent-actionplans" style="margin-top: 10px;">';

if(in_array($in['in_id'], $student_in_ids)){
    //Show link back to Action Plan:
    echo '<a href="/messenger/actionplan" class="list-group-item">';
    echo '<span class="pull-left">';
    echo '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-left"></i></span>';
    echo '</span>';
    echo ' Back to Action Plan</a>';
}

foreach ($this->Platform_model->in_fetch_recursive_parents($in['in_id'], 2) as $parent_in_id => $grand_parent_ids) {
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
echo '<h3 class="master-h3 primary-title">' . echo_in_outcome($in['in_outcome'] , true). '</h3>';
echo '<div class="sub_title">';

//Progression link:
$en_all_4331 = $this->config->item('en_all_4331');
$en_all_6146 = $this->config->item('en_all_6146');
$fixed_fields = $this->config->item('fixed_fields');
$submission_messages = null;
foreach($advance_step['progression_links'] as $pl){

    echo '<span style="margin-right:10px;" class="status-label underdot" data-toggle="tooltip" data-placement="top" title="Status is '.$fixed_fields['ln_student_status'][$pl['ln_status']]['s_name'].': '.$fixed_fields['ln_student_status'][$pl['ln_status']]['s_desc'].'">'.$fixed_fields['ln_student_status'][$pl['ln_status']]['s_icon'].' '.$en_all_6146[$pl['ln_type_entity_id']]['m_name'].'</span>';

    if(strlen($pl['ln_content']) > 0){
        //Student seems to have submitted messages for this:
        $submission_messages .= $en_all_4331[$in['in_requirement_entity_id']]['m_icon'].' '.$en_all_4331[$in['in_requirement_entity_id']]['m_name'].' message added '.echo_time_difference(strtotime($pl['ln_timestamp'])).' ago: ';
        $submission_messages .= $this->Communication_model->dispatch_message($pl['ln_content'], $session_en);
    }

}

//Completion Requirements if any:
if($in['in_type']==0 && $in['in_requirement_entity_id'] != 6087){
    //This has a completion requirement, show it:
    echo '<span class="status-label" style="margin-right:10px;">'.$en_all_4331[$in['in_requirement_entity_id']]['m_icon'].' '.$en_all_4331[$in['in_requirement_entity_id']]['m_name'].' Message Required</span>';
}

//Completion time cost:
if($time_estimate){
    echo '<span style="margin-right:10px;" class="status-label underdot" data-toggle="tooltip" data-placement="top" title="The estimated time to complete"><i class="fas fa-alarm-clock"></i>' . $time_estimate.'</span>';
}

//Completion dollar cost:
if($in['in_dollar_cost'] > 0){
    echo '<span style="margin-right:10px;" class="status-label underdot" data-toggle="tooltip" data-placement="top" title="The estimated USD cost to purchase verified 3rd party products"><i class="fas fa-usd-circle"></i>' . number_format($in['in_dollar_cost'], 2).'</span>';
}

echo '</div>';



//Show possible submission messages:
echo $submission_messages;



//Show messages:
if($advance_step['status']){
    //All good, show messages:
    echo $advance_step['message'];
} else {
    //Ooooops, show error:
    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: '.$advance_step['message'].'</div>';
}




/*



//Set variables:
$is_or_branch = ( $in['in_type']==1 );


$message_in_requirements = $this->Platform_model->in_req_completion($in);

//Submission button visible after first button was clicked:
$show_written_input = ($message_in_requirements && $is_incomplete);






//TODO Fetch/show Student responses?




//Show completion options below messages:
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

*/
?>