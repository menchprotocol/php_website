<?php
$superpower_31000 = superpower_active(31000, true);

if(isset($_GET['x__id']) && strlen($_GET['x__id']) > 0 && isset($_GET['x__time']) && strlen($_GET['x__time']) > 0){

    //Validate Ticket Input:
    $x = $this->X_model->fetch(array(
        'x__id' => $_GET['x__id'],
        'x__time' => $_GET['x__time'],
    ));

    if(!count($x)){

        echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Invalid Ticket ID</div>';

    } else {

        $url = 'https://'.get_domain('m__message', ( isset($member_e['e__id']) ? $member_e['e__id'] : 0 )).'/-26560?x__id='.$x[0]['x__id'].'&x__time='.$tr_time;
        echo $url;
        echo '<hr />';
        echo(generateQR($url));

    }

    if($superpower_31000){
        echo '<p style="text-align: center">Admin ticket</p>';
    }

}



if($member_e) {

    //Search for my tickets and group based on Upcoming & Past:
    echo '<p style="text-align: center">You have no upcoming tickets</p>';

} else {
    echo '<p style="text-align: center">Missing ticket ID & timestamp. Make sure to click on the link that was emailed to you to manage your ticket.</p>';
}