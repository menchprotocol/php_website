<?php

$counter = 0;
foreach($this->X_model->fetch(array(
    'x__type' => 26092,
), array(), 0) as $x) {
    $counter++;
    $parts = explode(' ',$x['x__message']);
    $this->X_model->update($x['x__id'], array(
        'x__message' => 'CAD '.str_replace('.00','',$parts[1]),
    ));
}
echo $counter.' Ceaned';

//Called when the paypal payment is complete:
if(isset($_POST) && isset($_POST['payment_status']) && $_POST['payment_status']=='Completed' && isset($_POST['item_number']) && intval($_POST['item_number'])>0){

            //Seems like a valid Paypal IPN Call:
            //TODO Fetch Subscription row with intval($_POST['item_number'])

            $payment_received = doubleval(( $_POST['payment_gross']>$_POST['mc_gross'] ? $_POST['payment_gross'] : $_POST['mc_gross'] ));

            //Save this new transaction:
            $this->X_model->create(array(
                'x__type' => 26595, //Paypal Payment Received
                'x__source' => $to_e__id, //Sent to this u
                'x__reference' => $add_fields['x__id'], //Save transaction

                //Import potential Idea/source connections from transaction:
                'x__right' => $add_fields['x__right'],
                'x__left' => $add_fields['x__left'],
                'x__down' => $add_fields['x__down'],
                'x__up' => $add_fields['x__up'],
            ));

            $transaction = $this->Db_model->t_create(array(
                    't_status' => 1, //Payment received from Student
                    't_timestamp' => date("Y-m-d H:i:s"),
                    't_paypal_id' => $_POST['txn_id'],
                    't_paypal_ipn' => json_encode($_POST),
                    't_currency' => $_POST['mc_currency'],
                    't_payment_type' => $_POST['payment_type'],
                    't_total' => $payment_received,
                    't_fees' => doubleval(( $_POST['payment_fee']>$_POST['mc_fee'] ? $_POST['payment_fee'] : $_POST['mc_fee'] )),
                    //TODO Link to subsciption & user...
                ));

} else {
    echo 'Invalid Paypal Post Data';
}
