<?php

//TODO RETIRE

if ($_GET['focus__id']==12273 && handle_session(12700) && isset($_POST['s__id']) && isset($_POST['mass_action_toggle']) && isset($_POST['mass_value1_'.$_POST['mass_action_toggle']]) && isset($_POST['mass_value2_'.$_POST['mass_action_toggle']])) {

    //Process mass action:
    $process_mass_action = $this->Hashtags->command($_POST['s__id'], intval($_POST['mass_action_toggle']), $_POST['mass_value1_'.$_POST['mass_action_toggle']], $_POST['mass_value2_'.$_POST['mass_action_toggle']], $handle_session['handleid']);

    //Pass-on results to UI:
    $this->session->set_flashdata('flash_message', '<div class="alert '.( $process_mass_action['status'] ? 'alert-warning' : 'alert-danger' ).'" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>'.$process_mass_action['message'].'</div>');

    foreach($this->Hashtags->read(array('hashtagid' => $_POST['s__id'])) as $i){
        header("Location: /" . $i['hashtagstring'] );
    }

} elseif ($_GET['focus__id']==12274 && handle_session(12700) && isset($_POST['s__id']) && isset($_POST['mass_action_toggle']) && isset($_POST['mass_value1_'.$_POST['mass_action_toggle']]) && isset($_POST['mass_value2_'.$_POST['mass_action_toggle']])) {

    //Process mass action:
    $process_mass_action = $this->Handles->command($_POST['s__id'], intval($_POST['mass_action_toggle']), $_POST['mass_value1_'.$_POST['mass_action_toggle']], $_POST['mass_value2_'.$_POST['mass_action_toggle']], $handle_session['handleid']);

    //Pass-on results to UI:
    $this->session->set_flashdata('flash_message', '<div class="alert '.( $process_mass_action['status'] ? 'alert-info' : 'alert-danger' ).'" role="alert"><span class="icon-block"><i class="far fa-info-circle"></i></span>'.$process_mass_action['message'].'</div>');

    foreach($this->Handles->read(array('handleid' => $_POST['s__id'])) as $e){
        header("Location: " . view_memory(42903,42902) . $e['handlehandle'] );
    }

} else {

    echo 'Missing valid input';

}