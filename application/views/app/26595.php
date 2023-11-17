<?php

$new_x = array();
$is_good = false;

//Called when the paypal payment is complete:
if(isset($_POST['payment_status'])){

    //Log New Payment:
    $item_numbers = explode('-', $_POST['item_number']);
    $top_i__id = intval($item_numbers[0]);
    $i__id = intval($item_numbers[1]);
    //$currency_type = intval($item_numbers[2]); //Deprecated
    $x__creator = intval($item_numbers[3]);
    $pay_amount = doubleval(( strlen($_POST['payment_gross']) ? $_POST['payment_gross'] : $_POST['mc_gross']));

    //Seems like a valid Paypal IPN Call:
    $next_is = $this->I_model->fetch(array(
        'i__id' => $i__id,
        'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    ));

    if ($top_i__id > 0 && $i__id > 0 && $x__creator > 0 && count($next_is)) {

        $is_pending = ($_POST['payment_status']=='Pending');

        if($pay_amount > 0){

            //Paid:
            $is_good = true;

            //Log Payment:
            $new_x = $this->X_model->mark_complete($top_i__id, $next_is[0], array(
                'x__type' => ( $is_pending ? 35572 /* Pending Payment */ : 26595 ),
                'x__weight' => intval($_POST['quantity']),
                'x__creator' => $x__creator,
                'x__metadata' => $_POST,
            ));

        } else {

            //Refund Completed:
            $is_good = true;

            //Find issued tickets:
            $original_payment = $this->X_model->fetch(array(
                'x__type' => 26595,
                'x__creator' => $x__creator,
                'x__left' => $next_is[0]['i__id'],
            ));

            //Log Refund:
            $new_x = $this->X_model->mark_complete($top_i__id, $next_is[0], array(
                'x__type' => ( $is_pending ? 39597 /* Pending Refund */ : 31967 ) ,
                'x__weight' => (-1 * ( isset($original_payment[0]['x__weight']) ? $original_payment[0]['x__weight'] : 1 )),
                'x__creator' => $x__creator,
                'x__metadata' => $_POST,
                'x__reference' => ( isset($original_payment[0]['x__id']) ? $original_payment[0]['x__id'] : 0 ),
                'x__website' => ( isset($original_payment[0]['x__website']) && $original_payment[0]['x__website']>0 ? $original_payment[0]['x__website'] : 0 ),
            ));

        }
    }
}


if(strlen($_POST['item_number']) && !$is_good && isset($_POST) && count($_POST)){
    $this->X_model->create(array(
        'x__type' => 4246, //Platform Bug Reports
        'x__message' => 'Invalid item number',
        'x__metadata' => array(
            'new_x' => $new_x,
            'post' => $_POST,
        ),
    ));
    echo 'Invalid inputs';
}



