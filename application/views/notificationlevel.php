<?php



$playerhandle = ( isset($_GET['playerhandle']) && isset($_GET['hash']) && isset($_GET['time']) && view_hash($_GET['time'].$_GET['playerhandle'])==$_GET['hash'] ? $_GET['playerhandle'] : $player_e['playerhandle'] );

if(strlen($playerhandle)){

    //Notification Settings
    foreach($this->Cacheplayers->fetch(array(
        'playerhandle' => $playerhandle,
    )) as $e){
        echo '<h3 style="text-align: center; margin: -10px 0 21px 0;">'.$e['playertext'].'</h3>';
    }

    echo '<div style="max-width:610px; margin: 0 auto;">'.view_instant_select(28904, $e['playerid'], 0).'</div>';

} else {

    echo 'No valid user handle to load notifications.';

}


