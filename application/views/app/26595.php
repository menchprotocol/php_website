<?php

$new_x = array();
$is_good = false;

foreach($this->X_model->fetch(array(
    'x__type' => 26595,
    'x__weight' => 0,
), array(), 0) as $fix){
    $x__metadata = unserialize($fix['x__metadata']);
    if(isset($x__metadata['quantity']) && $x__metadata['quantity']>0) {
        $res = $this->X_model->update($fix['x__id'], array('x__weight' => intval($x__metadata['quantity'])));
        echo '<div>'.intval($x__metadata['quantity']).' ('.$res.')</div>';
    } else {
        echo '<div>ERROR:'.$fix['x__id'].'</div>';
        $this->db->query("DELETE FROM table__x WHERE x__id=".$fix['x__id'].";");
    }
}

//Called when the paypal payment is complete:
if(isset($_POST['payment_status']) && ($_POST['payment_status']=='Refunded' || $_POST['payment_status']=='Completed')){

    //Log New Payment:
    $item_numbers = explode('-', $_POST['item_number']);
    $top_i__id = intval($item_numbers[0]);
    $i__id = intval($item_numbers[1]);
    //$currency_type = intval($item_numbers[2]); //Deprecated
    $x__creator = intval($item_numbers[3]);
    $pay_amount = doubleval(($_POST['payment_gross'] > $_POST['mc_gross'] ? $_POST['payment_gross'] : $_POST['mc_gross']));

    //Seems like a valid Paypal IPN Call:
    $next_is = $this->I_model->fetch(array(
        'i__id' => $i__id,
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    ));

    if ($top_i__id > 0 && $i__id > 0 && $x__creator > 0 && count($next_is)) {

        if($pay_amount > 0 && $_POST['payment_status']=='Completed'){

            //Paid:
            $is_good = true;

            //Log Payment:
            $new_x = $this->X_model->mark_complete($top_i__id, $next_is[0], array(
                'x__type' => 26595,
                'x__weight' => intval($_POST['quantity']),
                'x__creator' => $x__creator,
                'x__metadata' => $_POST,
            ));

        } elseif($pay_amount < 0 && $_POST['payment_status']=='Refunded'){

            //Refunded:
            $is_good = true;

            //Find original payment:
            $original_payment = $this->X_model->fetch(array(
                'x__type' => 26595,
                'x__weight' => (-1 * intval($_POST['quantity'])),
                'x__creator' => $x__creator,
                'x__left' => $next_is[0]['i__id'],
            ));

            //Log Refund:
            $new_x = $this->X_model->mark_complete($top_i__id, $next_is[0], array(
                'x__type' => 31967,
                'x__creator' => $x__creator,
                'x__metadata' => $_POST,
                'x__reference' => ( isset($original_payment[0]['x__id']) ? $original_payment[0]['x__id'] : 0 ),
                'x__website' => ( isset($original_payment[0]['x__website']) && $original_payment[0]['x__website']>0 ? $original_payment[0]['x__website'] : 0 ),
            ));

        }
    }
}


if(!$is_good){
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



