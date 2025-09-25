<?php



$userhandle = ( isset($_GET['userlogin']) && isset($_GET['hash']) && isset($_GET['time']) && view_hash($_GET['time'].$_GET['userlogin'])==$_GET['hash'] ? $_GET['userlogin'] : $user_session['userhandle'] );

if(strlen($userhandle)){

    //Notification Settings
    foreach($this->Users->read(array(
        'userhandle' => $userhandle,
    )) as $e){
        echo '<h3 style="text-align: center; margin: -10px 0 21px 0;">'.$e['username'].'</h3>';
    }

    echo '<div style="max-width:610px; margin: 0 auto;">'.view_instant_select(28904, $e['userid'], 0).'</div>';

} else {

    echo 'No valid user user to load notifications.';

}


