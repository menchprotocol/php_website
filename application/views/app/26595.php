<?php

//Called when the paypal payment is complete:
if(isset($_POST) && isset($_POST['payment_status']) && $_POST['payment_status']=='Completed' && isset($_POST['item_number']) && intval($_POST['item_number'])>0 && intval($_POST['x__source'])>0 && intval($_POST['x__up'])>0 && intval($_POST['top_i__id'])>0){

    //Seems like a valid Paypal IPN Call:
    $next_is = $this->I_model->fetch(array(
        'i__id' => $_POST['item_number'],
        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
    ));

    if(count($next_is)){

        //Mark idea as complete:
        $this->X_model->mark_complete($_POST['top_i__id'], $next_is[0], array(
            'x__type' => 26595,
            'x__source' => $_POST['x__source'],
            'x__up' => $_POST['x__up'], //Currency type
            'x__metadata' => $_POST,
            'x__message' => doubleval(( $_POST['payment_gross']>$_POST['mc_gross'] ? $_POST['payment_gross'] : $_POST['mc_gross'] )),
        ));

    } else {
        echo 'Invalid top ID';
    }

} else {
    echo 'Invalid Paypal Post Data: '.print_r($_POST, true);
}
