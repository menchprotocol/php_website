<?php

if(!isset($_GET['LinkId']) || !intval($_GET['LinkId'])){

    echo 'Missing TRANSACTION ID (Append ?LinkId=TRANSACTION_ID in URL)';

} else {

    //We have the inputs we need


    //Fetch transaction metadata and display it:
    $x = $this->Mench_ledger->fetch(array(
        'LinkId' => $_GET['LinkId'],
    ));

    if (count($x) < 1) {

        echo 'Invalid Transaction ID';

    } elseif(!superpower_unlocked(12701)) {

        echo view__unauthorized_message(12701);

    } else {

        //unserialize metadata if needed:
        if(strlen($x[0]['LinkMetadata']) > 0){
            $x[0]['LinkMetadata'] = unserialize($x[0]['LinkMetadata']);
        }

        //Print on scree:
        view__json($x[0]);

    }

}
