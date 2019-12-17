
<script src="/js/custom/actionplan-step.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
<?php


/*
$this->load->view('view_read/actionplan_step', array(
    'session_en' => $session_en,
    'user_blogs' => $user_blogs,
    'advance_step' => $this->READ_model->read__blog_echo($session_en['en_id'], $in_id, false),
    'in' => $ins[0], //Currently focused blog:
));
*/


//Start showing the page:
$time_estimate = echo_time_range($in);

echo '<div style="padding-top:0px;">&nbsp;</div>';

echo '<h1>' . echo_in_title($in['in_title']). '</h1>';

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
        if(in_array($pl['ln_status_player_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */) && in_array($pl['ln_type_player_id'], $this->config->item('en_ids_6255') /* 🔴 READING LIST Steps Progressed */)){
            $trigger_on_complete_tips = true;
        }

        if(strlen($pl['ln_content']) > 0){

            //User seems to have submitted messages for this:
            $submission_messages .= '<span style="margin-right:10px;" class="status-label underdot" data-toggle="tooltip" data-placement="top" title="Message added '.echo_time_difference(strtotime($pl['ln_timestamp'])).' ago">'.( $pl['ln_status_player_id'] == 6176 /* Link Published */ ? $en_all_6146[$pl['ln_type_player_id']]['m_icon'] /* Show Progression Type */ : $en_all_6186[$pl['ln_status_player_id']]['m_icon'] /* Show Status */ ).' '.$en_all_6146[$pl['ln_type_player_id']]['m_name'].'</span>';


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

?>