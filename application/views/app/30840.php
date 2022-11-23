<?php

if(isset($_GET['x__id']) && strlen($_GET['x__id']) > 0){

    $x = $this->X_model->fetch(array(
        'x__id' => $_GET['x__id'],
    ));

    if(!count($x)){
        die('Invalid Ticket ID URL');
    }

    $member_e = superpower_unlocked();
    $url = 'https://'.get_domain('m__message', ( isset($member_e['e__id']) ? $member_e['e__id'] : 0 )).'/-30840?x__id='.$x[0]['x__id'].'&x__time='.$x[0]['x__time'];
    echo $url;
    echo '<hr />';

    echo(generateQR($url));

} else {

    echo 'Missing ticket ID. Make sure to click on the link that was emailed to you.';

}
