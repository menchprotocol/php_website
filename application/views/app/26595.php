<?php

$new_x = array();
$is_good = false;

//Called when the paypal payment is complete:
if(isset($_POST['payment_status']) && ($_POST['payment_status']=='Refunded' || $_POST['payment_status']=='Completed')){

    //Log New Payment:
    $item_numbers = explode('-', $_POST['item_number']);
    $top_i__id = intval($item_numbers[0]);
    $i__id = intval($item_numbers[1]);
    $currency_type = intval($item_numbers[2]);
    $x__source = intval($item_numbers[3]);
    $pay_amount = doubleval(($_POST['payment_gross'] > $_POST['mc_gross'] ? $_POST['payment_gross'] : $_POST['mc_gross']));

    //Seems like a valid Paypal IPN Call:
    $next_is = $this->I_model->fetch(array(
        'i__id' => $i__id,
        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
    ));

    if ($top_i__id > 0 && $i__id > 0 && in_array($currency_type, $this->config->item('n___26661')) && $x__source > 0 && count($next_is)) {

        if($pay_amount > 0 && $_POST['payment_status']=='Completed'){

            //Paid:
            $is_good = true;
            $new_x = $this->X_model->mark_complete($top_i__id, $next_is[0], array(
                'x__type' => 26595,
                'x__source' => $x__source,
                'x__up' => $currency_type,
                'x__metadata' => $_POST,
            ));

        } elseif($pay_amount < 0 && $_POST['payment_status']=='Refunded'){

            //Find original transaction first:
            foreach($this->X_model->fetch(array(
                'x__type' => 26595,
                'x__source' => $x__source,
                'x__left' => $next_is[0]['i__id'],
                'x__right' => $top_i__id,
                'x__up' => $currency_type,
            )) as $x_payment) {
                //Refunded:
                $new_x = $this->X_model->mark_complete($top_i__id, $next_is[0], array(
                    'x__type' => 31967,
                    'x__source' => $x__source,
                    'x__up' => $currency_type,
                    'x__reference' => $x_payment['x__id'],
                    'x__metadata' => $_POST,
                ));
                $is_good = true;
            }
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



