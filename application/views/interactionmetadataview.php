<?php

if(!isset($_GET['linkid']) || !intval($_GET['linkid'])){

    echo 'Missing TRANSACTION ID (Append ?linkid=TRANSACTION_ID in URL)';

} else {

    //We have the inputs we need


    //Fetch transaction metadata and display it:
    $x = $this->Mench_ledger->fetch(array(
        'linkid' => $_GET['linkid'],
    ));

    if (count($x) < 1) {

        echo 'Invalid Transaction ID';

    } elseif(!superpower_unlocked(12701)) {

        echo view__unauthorized_message(12701);

    } else {

        //Print on scree:
        view__json($x[0]);

    }

}
