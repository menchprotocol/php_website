<?php

if(isset($_GET['x__id']) && strlen($_GET['x__id']) > 0){

    $x = $this->X_model->fetch(array(
        'x__id' => $_GET['x__id'],
    ));

    if(!count($x)){
        die('Invalid Ticket ID URL');
    }
    $tr_time = strtotime($x[0]['x__time']);

    if(isset($_GET['x__time']) && $_GET['x__time']==$tr_time){
        echo 'Verified URL<br />';
    } else {
        echo 'Unverified<br />';
    }

    $member_e = superpower_unlocked();
    $url = 'https://'.get_domain('m__message', ( isset($member_e['e__id']) ? $member_e['e__id'] : 0 )).'/-30840?x__id='.$x[0]['x__id'].'&x__time='.$tr_time;
    echo $url;
    echo '<hr />';

    echo(generateQR($url));

} else {

    echo 'Missing ticket ID. Make sure to click on the link that was emailed to you.';

}
