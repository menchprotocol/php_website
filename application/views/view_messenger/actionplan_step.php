<script>
    //Set global variables:
    var en_miner_id = <?= $session_en['en_id'] ?>;
</script>
<script src="/js/custom/messenger-actionplan-progress.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>
<?php


//Prep user intention ids array:
$user_intentions_ids = array();
foreach($user_intents as $user_in){
    array_push($user_intentions_ids, $user_in['in_id']);
}



//Fetch parent tree all the way to the top of Action Plan ln_child_intent_id
$found_grandpa_intersect = false; //Makes sure user can access this step as it should related to one of their intentions...


echo '<div class="list-group parent-actionplans" style="margin-top: 10px;">';

if(in_array($in['in_id'], $user_intentions_ids)){
    //Show link back to Action Plan:
    $found_grandpa_intersect = true;
    echo '<a href="/actionplan" class="list-group-item">';
    echo '<span class="pull-left">';
    echo '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-left"></i></span>';
    echo '</span>';
    echo ' Back to Action Plan</a>';
}


//Go through parents and detect intersects with user intentions. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
foreach ($this->Intents_model->in_fetch_recursive_public_parents($in['in_id']) as $parent_in_id => $grand_parent_ids) {
    //Does this parent and its grandparents have an intersection with the user intentions?
    if(array_intersect($grand_parent_ids, $user_intentions_ids)){
        //Fetch parent intent & show:
        $parent_ins = $this->Intents_model->in_fetch(array(
            'in_id' => $parent_in_id,
        ));

        //See if parent is complete:
        $parent_progression_steps = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
            'ln_miner_entity_id' => $session_en['en_id'],
            'ln_parent_intent_id' => $parent_in_id,
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ));

        echo echo_actionplan_step_parent($parent_ins[0], (count($parent_progression_steps) > 0 ? $parent_progression_steps[0]['ln_status_entity_id'] : 6174 /* Link New */));

        //We found an intersect:
        $found_grandpa_intersect = true;
    }
}
echo '</div>';




//Can they access this intent?
if(!$found_grandpa_intersect){

    //Terminate access:
    echo '<div class="alert alert-danger" role="alert">Error: This step does not belong to any of your intentions.</div>';

} else {

    //Start showing the page:
    $time_estimate = echo_time_range($in);


    //Show title
    echo '<h3 class="master-h3 primary-title">' . echo_in_outcome($in['in_outcome']). '</h3>';
    echo '<div class="sub_title">';

    //Progression link:
    $en_all_6794 = $this->config->item('en_all_6794');
    $en_all_6146 = $this->config->item('en_all_6146');
    $en_all_6186 = $this->config->item('en_all_6186'); //Link Statuses
    $submission_messages = null;
    $trigger_on_complete_tips = false;
    if($advance_step['status']){
        foreach($advance_step['progression_links'] as $pl){
            echo '<span style="margin-right:10px;" class="status-label underdot" data-toggle="tooltip" data-placement="top" title="Status is '.$en_all_6186[$pl['ln_status_entity_id']]['m_name'].': '.$en_all_6186[$pl['ln_status_entity_id']]['m_desc'].'">'.( $pl['ln_status_entity_id'] == 6176 /* Link Published */ ? $en_all_6146[$pl['ln_type_entity_id']]['m_icon'] /* Show Progression Type */ : $en_all_6186[$pl['ln_status_entity_id']]['m_icon'] /* Show Status */ ).' '.$en_all_6146[$pl['ln_type_entity_id']]['m_name'].'</span>';

            //Should we trigger on-complete links?
            if(in_array($pl['ln_status_entity_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */) && in_array($pl['ln_type_entity_id'], $this->config->item('en_ids_6255') /* Action Plan Steps Progressed */)){
                $trigger_on_complete_tips = true;
            }

            if(strlen($pl['ln_content']) > 0){
                //User seems to have submitted messages for this:
                $submission_messages .= '<span class="i_content"><span class="msg">Message added '.echo_time_difference(strtotime($pl['ln_timestamp'])).' ago:</span></span>';

                $submission_messages .= '<div class="white-bg">'.$this->Communication_model->dispatch_message($pl['ln_content'], $session_en).'</div>';
            }

        }
    }


    //Completion Percentage so far:
    $completion_rate = $this->Actionplan_model->actionplan_completion_progress($session_en['en_id'], $in);
    echo '<span class="status-label underdot" style="margin-right:10px;" data-toggle="tooltip" data-placement="top" title="'.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Steps Completed"><i class="fas fa-check-circle"></i> '.$completion_rate['completion_percentage'].'%</span>';



    //Requires Manual Response if any:
    if(in_array($in['in_type_entity_id'], $this->config->item('en_ids_6794'))){
        //This has a completion requirement, show it:
        echo '<span class="status-label" style="margin-right:10px;">'.$en_all_6794[$in['in_type_entity_id']]['m_icon'].' '.$en_all_6794[$in['in_type_entity_id']]['m_name'].'</span>';
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

}
?>