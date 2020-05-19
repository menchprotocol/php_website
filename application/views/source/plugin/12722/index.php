<?php

if(!isset($_GET['ln_id']) || !intval($_GET['ln_id'])){

    echo 'Missing Transaction ID (Append ?ln_id=TRANSACTION_ID in URL)';

} else {

    //We have the inputs we need...


    //Fetch link metadata and display it:
    $lns = $this->READ_model->fetch(array(
        'ln_id' => $_GET['ln_id'],
    ));

    if (count($lns) < 1) {

        echo 'Invalid Transaction ID';

    } elseif(!superpower_assigned(12701)) {

        echo view_unauthorized_message(12701);

    } else {

        //unserialize metadata if needed:
        if(strlen($lns[0]['ln_metadata']) > 0){
            $lns[0]['ln_metadata'] = unserialize($lns[0]['ln_metadata']);
        }

        //Print on scree:
        view_json($lns[0]);

    }

}
