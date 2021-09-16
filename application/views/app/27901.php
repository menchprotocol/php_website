<?php

//Called when the paypal payment is complete:
if(1){

    $this->X_model->create(array(
        'x__type' => 27901,
        'x__metadata' => array(
            'POST' => $_POST,
            'GET' => $_GET,
            'REQUEST' => $_REQUEST,
        ),
    ));

    echo 'Got it';

} else {
    echo 'Invalid Post Data: '.print_r($_POST, true);
}



