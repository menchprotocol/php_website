<?php

$e__handle = ( isset($_GET['e__handle']) && isset($_GET['e__hash']) && view_e__hash($_GET['e__handle'])==$_GET['e__hash'] ? $_GET['e__handle'] : $member_e['e__handle'] );

if($e__handle > 0){
    //Notification Settings
    foreach($this->E_model->fetch(array(
        'e__id' => $e__handle,
    )) as $e){
        echo '<h3 style="text-align: center; margin: -10px 0 21px 0;">'.$e['e__title'].'</h3>';
    }
    echo '<div style="max-width: 540px; margin: 0 auto;">'.view_radio_e(28904, $e['e__id'], 0).'</div>';
} else {
    js_php_redirect(view_app_link(4269).'?url='.urlencode($_SERVER['REQUEST_URI']), 13);
}


