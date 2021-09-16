<?php

//Called when the paypal payment is complete:
if(isset($_POST)){

    $this->X_model->create(array(
        'x__type' => 27901,
        'x__metadata' => $_POST,
    ));

} else {
    echo 'Invalid Post Data: '.print_r($_POST, true);
}



