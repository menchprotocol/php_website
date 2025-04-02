<?php

if(!isset($_GET['linkid']) || !intval($_GET['linkid'])){

    echo 'Missing TRANSACTION ID (Append ?linkid=TRANSACTION_ID in URL)';

} else {

    //We have the inputs we need


    //Fetch transaction metadata and display it:
    $x = $this->Ledger->fetch(array(
        'linkid' => $_GET['linkid'],
    ));

    if (count($x) < 1) {

        echo 'Invalid Transaction ID';

    } elseif(!superpower_unlocked(12701)) {

        echo view_unauthorized_message(12701);

    } else {

        //Print on scree:
        view_json($x[0]);

    }

}
