<?php

//Called when the paypal payment is complete:
$this->X_model->create(array(
    'x__app' => 27901,
    'x__type' => 27901,
    'x__metadata' => array(
        'POST' => $_POST,
        'GET' => $_GET,
        'REQUEST' => $_REQUEST,
    ),
));

header("Content-type: text/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<response>';
echo '<status>Success</status>';
echo '<remarks>We got it</remarks>';
echo '</response>';