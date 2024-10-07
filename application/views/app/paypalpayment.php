<?php

$completion_status = array();
$is_good = false;

//Called when the paypal payment is complete:
if(isset($_POST['payment_status'])){

    //Log New Payment:
    $item_parts = explode(' ', $_POST['item_number']);

    $item_numbers['i_target'] = strtolower(( count($item_parts)==4 ? trim(str_replace('#','',$item_parts[0])) : false ));
    $item_numbers['i_destination'] = strtolower(( count($item_parts)==4 ? trim(str_replace('#','',$item_parts[1])) : trim($item_parts[0]) ));
    $item_numbers['e_wesbite'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 2 : 1 )])));
    $item_numbers['e_player'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 3 : 2 )])));

    //Fetch Objects based on handles:
    $player_es = $this->E_model->fetch(array(
        'LOWER(e__handle)' => $item_numbers['e_player'],
    ));
    $website_es = $this->E_model->fetch(array(
        'LOWER(e__handle)' => $item_numbers['e_wesbite'],
    ));
    $next_is = $this->I_model->fetch(array(
        'LOWER(i__hashtag)' => $item_numbers['i_destination'],
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    ));
    $target_is = ($item_numbers['i_target'] ? $this->I_model->fetch(array(
        'LOWER(i__hashtag)' => $item_numbers['i_target'],
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    )) : false);


    if(count($player_es) && count($next_is)) {

        $is_pending = ($_POST['payment_status']=='Pending');
        $is_good = true;

        //Is the payment amount greater than zero?
        if(doubleval(( strlen($_POST['payment_gross']) ? $_POST['payment_gross'] : $_POST['mc_gross'])) > 0){

            //Paid:
            $x__type = ( $is_pending ? 35572 /* Pending Payment */ : 26595 );

            //Log Payment:
            $completion_status = $this->X_model->mark_complete($x__type, $player_es[0]['e__id'], ( isset($target_is[0]['i__id']) ? $target_is[0]['i__id'] : 0 ), $next_is[0], array(), array(
                'x__weight' => intval($_POST['quantity']),
                'x__metadata' => $_POST,
            ));

        } else {

            $x__type = ( $is_pending ? 39597 /* Pending Refund */ : 31967 );

            //Find issued tickets:
            $original_payment = $this->X_model->fetch(array(
                'x__type' => 26595,
                'x__player' => $player_es[0]['e__id'],
                'x__previous' => $next_is[0]['i__id'],
            ));

            //Log Refund:
            $completion_status = $this->X_model->mark_complete($x__type, $player_es[0]['e__id'], ( isset($target_is[0]['i__id']) ? $target_is[0]['i__id'] : 0 ), $next_is[0], array(), array(
                'x__weight' => (-1 * ( isset($original_payment[0]['x__weight']) ? $original_payment[0]['x__weight'] : 1 )),
                'x__metadata' => $_POST,
                'x__reference' => ( isset($original_payment[0]['x__id']) ? $original_payment[0]['x__id'] : 0 ),
                'x__website' => ( isset($original_payment[0]['x__website']) && $original_payment[0]['x__website']>0 ? $original_payment[0]['x__website'] : 0 ),
            ));

        }
    }
}


if(!$is_good){

    echo '<div class="alert alert-danger" role="alert">Missing Paypal API Variables.</div>';

    if(isset($_POST) && count($_POST)){
        //No need to log this for now since we get errors for when invoices are paid or refunded, etc...
        $this->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'Unrecognized item number',
            'x__metadata' => array(
                'new_x' => $completion_status['new_x'],
                'post' => $_POST,
            ),
        ));
    }
}

