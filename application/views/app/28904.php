<?php

$e__id = ( isset($_GET['e__id']) && isset($_GET['e__hash']) && md5($_GET['e__id'].$this->config->item('cred_password_salt'))==$_GET['e__hash'] ? $_GET['e__id'] : $member_e['e__id'] );

if($e__id > 0){
    //Notification Settings
    foreach($this->E_model->fetch(array(
        'e__id' => $e__id,
    )) as $e){
        echo '<div style="max-width: 540px; margin: 0 auto; text-align: center; font: 1.4em;">'.view_cover(12274,$e['e__cover']).' '.$e['e__title'].'</div>';
    }
    echo '<div style="max-width: 540px; margin: 0 auto;">'.view_radio_e(28904, $e__id, 0).'</div>';
    echo '<input type="hidden" id="member__id_override" value="'.$e__id.'" />';
} else {
    js_redirect('/-4269?url='.urlencode($_SERVER['REQUEST_URI']), 13);
}


