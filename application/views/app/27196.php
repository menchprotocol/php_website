<?php
//&& superpower_active(12700, true)
// && superpower_unlocked(12703)
if ($_GET['e__id']==12273  && isset($_POST['card__id']) && isset($_POST['mass_action_toggle']) && isset($_POST['mass_value1_'.$_POST['mass_action_toggle']]) && isset($_POST['mass_value2_'.$_POST['mass_action_toggle']])) {

    //Process mass action:
    $process_mass_action = $this->I_model->mass_update($_POST['card__id'], intval($_POST['mass_action_toggle']), $_POST['mass_value1_'.$_POST['mass_action_toggle']], $_POST['mass_value2_'.$_POST['mass_action_toggle']], $member_e['e__id']);

    //Pass-on results to UI:
    $this->session->set_flashdata('flash_message', '<div class="msg alert '.( $process_mass_action['status'] ? 'alert-warning' : 'alert-danger' ).'" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>'.$process_mass_action['message'].'</div>');

    header("Location: /~" . $_POST['card__id'] );

} elseif ($_GET['e__id']==12274 && isset($_POST['card__id']) && isset($_POST['mass_action_toggle']) && isset($_POST['mass_value1_'.$_POST['mass_action_toggle']]) && isset($_POST['mass_value2_'.$_POST['mass_action_toggle']])) {

    //Process mass action:
    $process_mass_action = $this->E_model->mass_update($_POST['card__id'], intval($_POST['mass_action_toggle']), $_POST['mass_value1_'.$_POST['mass_action_toggle']], $_POST['mass_value2_'.$_POST['mass_action_toggle']], $member_e['e__id']);

    //Pass-on results to UI:
    $this->session->set_flashdata('flash_message', '<div class="msg alert '.( $process_mass_action['status'] ? 'alert-info' : 'alert-danger' ).'" role="alert"><span class="icon-block"><i class="fas fa-info-circle"></i></span>'.$process_mass_action['message'].'</div>');

    header("Location: /@" . $_POST['card__id'] );

} else {

    echo 'Missing valid input';

}