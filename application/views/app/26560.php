<?php
$superpower_31000 = superpower_active(31000, true);

if(isset($_GET['x__id']) && strlen($_GET['x__id']) > 0 && isset($_GET['x__time']) && strlen($_GET['x__time']) > 0){

    //Validate Ticket Input:
    $x = $this->X_model->fetch(array(
        'x__id' => $_GET['x__id'],
        'x__source' => $_GET['x__time'],
        'x__type IN (' . join(',', $this->config->item('n___32014')) . ')' => null, //Ticket Type
    ), array('x__source'));

    if(!count($x)){

        echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Invalid Ticket ID</div>';

    } else {

        $x__metadata = unserialize($x[0]['x__metadata']);
        $quantity = ( isset($x__metadata['quantity']) && $x__metadata['quantity']>1 ? $x__metadata['quantity'] : 1 );
        $is_top = $this->I_model->fetch(array(
            'i__id' => $x[0]['x__right'],
        ));
        $is_discovery = $this->I_model->fetch(array(
            'i__id' => $x[0]['x__left'],
        ));


        echo '<h2 style="text-align: center;">'.$is_top[0]['i__title'].'</h2>';
        echo '<h3 style="text-align: center;">'.$is_discovery[0]['i__title'].'</h3>';
        echo '<h3 style="text-align: center;">Ticket Holder: <a href="/@'.$x[0]['e__id'].'"><u>'.$x[0]['e__title'].'</u></a></h3>';
        echo '<h3 style="text-align: center;">Total Tickets: <b>'.$quantity.'</b></h3>';
        echo '<h3 style="text-align: center;">Ticket Status: <b>'.$quantity.'</b></h3>';

        $url = 'https://'.get_domain('m__message', ( isset($member_e['e__id']) ? $member_e['e__id'] : 0 )).'/-26560?x__id='.$x[0]['x__id'].'&x__source='.$x[0]['x__source'];
        echo '<div style="text-align: center; padding-bottom: 21px;">'.generateQR($url).'</div>';
        echo '<div style="text-align: center; font-size: 10px;">'.$url.'</div>';

    }

    if($superpower_31000){
        echo '<p style="text-align: center">Admin ticket</p>';
    }

} else {

    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing Ticket ID</div>';

}