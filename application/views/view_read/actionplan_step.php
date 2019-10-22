<script>
    //Set global variables:
    var en_creator_id = <?= $session_en['en_id'] ?>;
</script>
<script src="/js/custom/actionplan-step.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
<?php


//Prep user intention ids array:
$user_intentions_ids = array();
foreach($user_intents as $user_in){
    array_push($user_intentions_ids, $user_in['in_id']);
}



//Fetch parent tree all the way to the top of Action Plan ln_child_intent_id
$found_grandpa_intersect = false; //Makes sure user can access this step as it should related to one of their intentions...


//echo '<div class="list-group parent-actionplans" style="margin-top: 10px;">';

if(in_array($in['in_id'], $user_intentions_ids)){
    //Show link back to Action Plan:
    $found_grandpa_intersect = true;

    /*
    echo '<a href="/actionplan" class="list-group-item">';
    echo '<span class="pull-left">';
    echo '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-left"></i></span>';
    echo '</span>';
    echo ' Back to Action Plan</a>';
    */
}


//Go through parents and detect intersects with user intentions. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
$recursive_parents = $this->BLOG_model->in_fetch_recursive_public_parents($in['in_id']);

foreach ($recursive_parents as $grand_parent_ids) {
    //Does this parent and its grandparents have an intersection with the user intentions?
    if(array_intersect($grand_parent_ids, $user_intentions_ids)){

        //Fetch parent intent & show:
        /*
        $parent_ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $grand_parent_ids[0],
        ));

        echo echo_actionplan_step_parent($parent_ins[0]);
        */

        //We found an intersect:
        $found_grandpa_intersect = true;

    }
}
//echo '</div>';




//Can they access this intent?
if(!$found_grandpa_intersect){

    //Terminate access:
    echo '<div class="alert alert-danger" role="alert">Error: This step does not belong to any of your intentions.</div>';

} else {

    //Start showing the page:
    $time_estimate = echo_time_range($in);

    echo '<div style="padding-top:0px;">&nbsp;</div>';

    echo '<h1>' . echo_in_outcome($in['in_outcome']). '</h1>';

    echo '<div class="sub_title">';

    //Progression link:
    $en_all_6144 = $this->config->item('en_all_6144');
    $en_all_6146 = $this->config->item('en_all_6146');
    $en_all_6186 = $this->config->item('en_all_6186'); //Link Statuses
    $submission_messages = null;
    $trigger_on_complete_tips = false;
    if($advance_step['status']){
        foreach($advance_step['current_progression_links'] as $pl){

            //Should we trigger on-complete links?
            if(in_array($pl['ln_status_entity_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */) && in_array($pl['ln_type_entity_id'], $this->config->item('en_ids_6255') /* Action Plan Steps Progressed */)){
                $trigger_on_complete_tips = true;
            }

            if(strlen($pl['ln_content']) > 0){

                //User seems to have submitted messages for this:
                $submission_messages .= '<span style="margin-right:10px;" class="status-label underdot" data-toggle="tooltip" data-placement="top" title="Message added '.echo_time_difference(strtotime($pl['ln_timestamp'])).' ago">'.( $pl['ln_status_entity_id'] == 6176 /* Link Published */ ? $en_all_6146[$pl['ln_type_entity_id']]['m_icon'] /* Show Progression Type */ : $en_all_6186[$pl['ln_status_entity_id']]['m_icon'] /* Show Status */ ).' '.$en_all_6146[$pl['ln_type_entity_id']]['m_name'].'</span>';


                $submission_messages .= '<div class="white-bg">'.$this->READ_model->dispatch_message($pl['ln_content'], $session_en).'</div>';
            }
        }
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