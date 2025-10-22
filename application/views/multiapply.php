<?php

//TODO RETIRE

if ($_GET['focus__id'] == 12273 && user_session(12700) && isset($_POST['s__id']) && isset($_POST['mass_action_toggle']) && isset($_POST['mass_value1_'.$_POST['mass_action_toggle']]) && isset($_POST['mass_value2_'.$_POST['mass_action_toggle']])) {

    //Process mass action:
    $process_mass_action = $this->Posts->command($_POST['s__id'], intval($_POST['mass_action_toggle']), $_POST['mass_value1_'.$_POST['mass_action_toggle']], $_POST['mass_value2_'.$_POST['mass_action_toggle']], $user_session['userid']);

    //Pass-on results to UI:
    $this->session->set_flashdata('flash_message', '<div class="alert '.( $process_mass_action['status'] ? 'alert-warning' : 'alert-danger' ).'" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>'.$process_mass_action['message'].'</div>');

    foreach($this->Posts->read(array('postid' => $_POST['s__id'])) as $i){
        header("Location: /" . $i['posthashtag'] );
    }

} elseif ($_GET['focus__id'] == 12274 && user_session(12700) && isset($_POST['s__id']) && isset($_POST['mass_action_toggle']) && isset($_POST['mass_value1_'.$_POST['mass_action_toggle']]) && isset($_POST['mass_value2_'.$_POST['mass_action_toggle']])) {

    //Process mass action:
    $process_mass_action = $this->Users->command($_POST['s__id'], intval($_POST['mass_action_toggle']), $_POST['mass_value1_'.$_POST['mass_action_toggle']], $_POST['mass_value2_'.$_POST['mass_action_toggle']], $user_session['userid']);

    //Pass-on results to UI:
    $this->session->set_flashdata('flash_message', '<div class="alert '.( $process_mass_action['status'] ? 'alert-info' : 'alert-danger' ).'" role="alert"><span class="icon-block"><i class="far fa-info-circle"></i></span>'.$process_mass_action['message'].'</div>');

    foreach($this->Users->read(array('userid' => $_POST['s__id'])) as $e){
        header("Location: " . view_memory(42903,42902) . $e['userhandle'] );
    }

} else {

    echo 'Missing valid input';

}