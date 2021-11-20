<?php

$list_e__id = get_domain_setting(28646);
if(!intval($list_e__id)){
    echo 'Domain List @28646 not set for this domain.';
} elseif(in_array($list_e__id, $this->config->item('n___4755')) && !superpower_unlocked(12701)){
    echo 'Missing powers to see this list.';
} else {

    //Do we have any mass action to process here?
    if (superpower_unlocked(12703) && isset($_POST['coin__id']) && isset($_POST['mass_action_toggle']) && isset($_POST['mass_value1_'.$_POST['mass_action_toggle']]) && isset($_POST['mass_value2_'.$_POST['mass_action_toggle']])) {

        //Process mass action:
        $process_mass_action = $this->E_model->mass_update($_POST['coin__id'], intval($_POST['mass_action_toggle']), $_POST['mass_value1_'.$_POST['mass_action_toggle']], $_POST['mass_value2_'.$_POST['mass_action_toggle']], $member_e['e__id']);

        //Pass-on results to UI:
        $message = '<div class="msg alert '.( $process_mass_action['status'] ? 'alert-info' : 'alert-danger' ).'" role="alert"><span class="icon-block"><i class="fas fa-info-circle"></i></span>'.$process_mass_action['message'].'</div>';

    } else {



    }

    //Update session count and log transaction:
    $message = null; //No mass-action message to be appended...

    $new_order = ( $this->session->userdata('session_page_count') + 1 );
    $this->session->set_userdata('session_page_count', $new_order);

    $this->X_model->create(array(
        'x__source' => $member_e['e__id'],
        'x__type' => 4994, //Member Viewed Source
        'x__down' => $e__id,
        'x__spectrum' => $new_order,
    ));

    //Validate source ID and fetch data:
    $es = $this->E_model->fetch(array(
        'e__id' => $e__id,
    ));

    if (count($es) < 1) {
        return redirect_message(home_url());
    }

    //Load views:
    $this->load->view('header', array(
        'title' => $es[0]['e__title'],
        'flash_message' => $message, //Possible mass-action message for UI:
    ));
    $this->load->view('e_layout', array(
        'e' => $es[0],
        'member_e' => $member_e,
    ));
    $this->load->view('footer');

}
