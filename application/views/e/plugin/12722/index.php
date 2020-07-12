<?php

if(!isset($_GET['x__id']) || !intval($_GET['x__id'])){

    echo 'Missing DISCOVER ID (Append ?x__id=TRANSACTION_ID in URL)';

} else {

    //We have the inputs we need...


    //Fetch transaction metadata and display it:
    $x = $this->X_model->fetch(array(
        'x__id' => $_GET['x__id'],
    ));

    if (count($x) < 1) {

        echo 'Invalid Transaction ID';

    } elseif(!superpower_assigned(12701)) {

        echo view_unauthorized_message(12701);

    } else {

        //unserialize metadata if needed:
        if(strlen($x[0]['x__metadata']) > 0){
            $x[0]['x__metadata'] = unserialize($x[0]['x__metadata']);
        }

        //Print on scree:
        view_json($x[0]);

    }

}
