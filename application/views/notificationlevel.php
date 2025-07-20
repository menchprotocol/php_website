<?php



$handlestring = ( isset($_GET['handlestring']) && isset($_GET['hash']) && isset($_GET['time']) && view_hash($_GET['time'].$_GET['handlestring'])==$_GET['hash'] ? $_GET['handlestring'] : $handle_session['handlestring'] );

if(strlen($handlestring)){

    //Notification Settings
    foreach($this->Handles->read(array(
        'handlestring' => $handlestring,
    )) as $e){
        echo '<h3 style="text-align: center; margin: -10px 0 21px 0;">'.$e['handlevalue'].'</h3>';
    }

    echo '<div style="max-width:610px; margin: 0 auto;">'.view_instant_select(28904, $e['handleid'], 0).'</div>';

} else {

    echo 'No valid user handle to load notifications.';

}


