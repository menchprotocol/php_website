<?php
$member_e = superpower_unlocked();
$superpower_31000 = $member_e && superpower_active(31000, true);

if(isset($_GET['x__id']) && strlen($_GET['x__id']) > 0 && ( $superpower_31000 || (isset($_GET['x__time']) && strlen($_GET['x__time']) > 0))){

    echo '<p>Admin Ticketing UI Enabled!</p>';
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





    if($superpower_31000){
        echo 'CECKED IN 1 TICKET<br />';
        echo 'CECKED IN OTHER 2 IN GROUP?<br />';
    } else {
        echo 'Unverified<br />';

    }


    $url = 'https://'.get_domain('m__message', ( isset($member_e['e__id']) ? $member_e['e__id'] : 0 )).'/-26560?x__id='.$x[0]['x__id'].'&x__time='.$tr_time;
    echo $url;
    echo '<hr />';

    echo(generateQR($url));

}

if($superpower_31000){

    //Show list of recent tickets sold.

}


if($member_e) {

    //Search for my tickets and group based on Upcoming & Past:
    echo 'You have no upcoming tickets';

} else {
    echo 'Missing ticket ID & timestamp. Make sure to click on the link that was emailed to you to manage your ticket.';
}