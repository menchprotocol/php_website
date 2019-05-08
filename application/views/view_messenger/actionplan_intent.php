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

//Go through parents and detect intersects with student intentions. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
foreach ($this->Intents_model->in_fetch_recursive_parents($in['in_id'], 2) as $parent_in_id => $grand_parent_ids) {
    //Does this parent and its grandparents have an intersection with the student intentions?
    if(array_intersect($grand_parent_ids, $student_in_ids)){
        //Fetch parent intent & show:
        $parent_ins = $this->Intents_model->in_fetch(array(
            'in_id' => $parent_in_id,
        ));

        //See if parent is complete:
        $parent_progression_steps = $this->Links_model->ln_fetch(array(
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
$trigger_on_complete_tips = false;
foreach($advance_step['progression_links'] as $pl){

    echo '<span style="margin-right:10px;" class="status-label underdot" data-toggle="tooltip" data-placement="top" title="Status is '.$fixed_fields['ln_student_status'][$pl['ln_status']]['s_name'].': '.$fixed_fields['ln_student_status'][$pl['ln_status']]['s_desc'].'">'.( $pl['ln_status']==2 /* Published? */ ? $en_all_6146[$pl['ln_type_entity_id']]['m_icon'] /* Show Progression Type */ : $fixed_fields['ln_student_status'][$pl['ln_status']]['s_icon'] /* Show Status */ ).' '.$en_all_6146[$pl['ln_type_entity_id']]['m_name'].'</span>';

    //Should we trigger on-complete links?
    if($pl['ln_status']==2 && in_array($pl['ln_type_entity_id'], $this->config->item('en_ids_6255'))){
        $trigger_on_complete_tips = true;
    }

    if(strlen($pl['ln_content']) > 0){
        //Student seems to have submitted messages for this:
        $submission_messages .= '<span class="i_content"><span class="msg">'.$en_all_4331[$in['in_requirement_entity_id']]['m_icon'].' '.$en_all_4331[$in['in_requirement_entity_id']]['m_name'].' message added '.echo_time_difference(strtotime($pl['ln_timestamp'])).' ago:</span></span>';

        $submission_messages .= '<div class="white-bg">'.$this->Communication_model->dispatch_message($pl['ln_content'], $session_en).'</div>';
    }

}


//Completion Percentage so far:
$completion_rate = $this->Actionplan_model->actionplan_completion_rate($in, $session_en['en_id']);
echo '<span class="status-label underdot" style="margin-right:10px;" data-toggle="tooltip" data-placement="top" title="'.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Steps Completed"><i class="fas fa-check-circle"></i> '.$completion_rate['completion_percentage'].'%</span>';



//Completion Requirements if any:
if($in['in_type']==0 && $in['in_requirement_entity_id'] != 6087){
    //This has a completion requirement, show it:
    echo '<span class="status-label" style="margin-right:10px;">'.$en_all_4331[$in['in_requirement_entity_id']]['m_icon'].' '.$en_all_4331[$in['in_requirement_entity_id']]['m_name'].' Message Required</span>';
}

//Completion time cost:
if($time_estimate){
    echo '<span style="margin-right:10px;" class="status-label underdot" data-toggle="tooltip" data-placement="top" title="The estimated time to complete"><i class="fas fa-alarm-clock"></i>' . $time_estimate.'</span>';
}

echo '</div>';




//Show messages:
if($advance_step['status']){

    //All good, show messages:
    echo $advance_step['html_messages'];

} else {
    //Ooooops, show error:
    echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: '.$advance_step['message'].'</div>';
}


//Show possible submission messages:
if($submission_messages){
    echo $submission_messages;
}
?>