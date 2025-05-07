<?php



$sourcehandle = ( isset($_GET['sourcehandle']) && isset($_GET['hash']) && isset($_GET['time']) && view_hash($_GET['time'].$_GET['sourcehandle'])==$_GET['hash'] ? $_GET['sourcehandle'] : $source_session['sourcehandle'] );

if(strlen($sourcehandle)){

    //Notification Settings
    foreach($this->Sources->read(array(
        'sourcehandle' => $sourcehandle,
    )) as $e){
        echo '<h3 style="text-align: center; margin: -10px 0 21px 0;">'.$e['sourcetext'].'</h3>';
    }

    echo '<div style="max-width:610px; margin: 0 auto;">'.view_instant_select(28904, $e['sourceid'], 0).'</div>';

} else {

    echo 'No valid user handle to load notifications.';

}


