<?php
$superpower_31000 = superpower_active(31000, true);
$e___11035 = $this->config->item('e___11035'); //NAVIGATION

if(isset($_GET['x__id']) && strlen($_GET['x__id']) > 0 && isset($_GET['x__source']) && strlen($_GET['x__source']) > 0){

    //Validate Ticket Input:
    $x = $this->X_model->fetch(array(
        'x__id' => $_GET['x__id'],
        'x__source' => $_GET['x__source'],
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
        //Checkin-Status:
        $ticket_checked_in = $this->X_model->fetch(array(
            'x__reference' => $x[0]['x__id'],
            'x__type' => 32016,
        ), array('x__source'));

        $ticket_url = 'https://'.get_domain('m__message', ( isset($member_e['e__id']) ? $member_e['e__id'] : 0 )).'/-26560?x__id='.$x[0]['x__id'].'&x__source='.$x[0]['x__source'];
        $checkin_url = $ticket_url.'&checkin_32016=1';


        //Display UI:
        echo '<h2 style="text-align: center;">'.$is_top[0]['i__title'].'</h2>';
        echo '<h3 style="text-align: center;">'.$is_discovery[0]['i__title'].'</h3>';
        echo '<h3 style="text-align: center;"><i class="fas fa-user"></i> <a href="/@'.$x[0]['e__id'].'"><u>'.$x[0]['e__title'].'</u></a>&nbsp;&nbsp;&nbsp;<i class="fas fa-ticket"></i> <b>'.$quantity.' Ticket'.view__s($quantity).'</b></h3>';


        if(count($ticket_checked_in)){

            echo '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Ticket Scanned by <a href="/@'.$ticket_checked_in[0]['e__id'].'">'.$ticket_checked_in[0]['e__title'].'</a> at '.substr($ticket_checked_in[0]['x__time'], 0, 19).' PST (' . view_time_difference(strtotime($ticket_checked_in[0]['x__time'])) . ' Ago)</div>';

        } else {

            echo '<div style="text-align: center; padding-bottom: 21px;">'.generateQR($checkin_url).'</div>';

        }


        if($superpower_31000){

            if(isset($_GET['checkin_32016'])){
                //QR Code Scanned, auto check-in:

                if(count($ticket_checked_in)){

                    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Ticket already checked-in!</div>';

                } else {

                    //All good to check-in:
                    $this->X_model->create(array(
                        'x__source' => $member_e['e__id'], //Ticket Scanner
                        'x__type' => 32016,
                        'x__up' => $x[0]['e__id'],
                        'x__spectrum' => $quantity,
                        'x__right' => $x[0]['x__right'],
                        'x__left' => $x[0]['x__left'],
                        'x__reference' => $x[0]['x__id'],
                    ));

                    echo '<div class="msg alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully checkin for '.$quantity.' Ticket'.view__s($quantity).'</div>';

                    js_redirect($ticket_url, 13);

                }
            } else {

                //Give option for manual checkin:
                echo '<div style="text-align: center;"><div class="nav-controller select-btns msg-frame"><a class="btn btn-lrg btn-6255 go-next" href="'.$checkin_url.'">'.$e___11035[32016]['m__title'].' '.$e___11035[32016]['m__cover'].'</a></div></div>';


            }

        }

    }

} else {

    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing Ticket ID</div>';

}