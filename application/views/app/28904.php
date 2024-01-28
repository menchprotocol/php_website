<?php



$e__handle = ( isset($_GET['e__handle']) && isset($_GET['e__hash']) && isset($_GET['e__time']) && view__hash($_GET['e__time'].$_GET['e__handle'])==$_GET['e__hash'] ? $_GET['e__handle'] : $member_e['e__handle'] );

if(strlen($e__handle)){

    //Notification Settings
    foreach($this->E_model->fetch(array(
        'e__handle' => $e__handle,
    )) as $e){
        echo '<h3 style="text-align: center; margin: -10px 0 21px 0;">'.$e['e__title'].'</h3>';
    }

    echo '<div style="max-width: 540px; margin: 0 auto;">'.view_single_select(28904, $e['e__id'], 0).'</div>';

} else {

    echo 'No valid user handle to load notifications.';

}


