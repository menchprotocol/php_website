<?php

if(!isset($_GET['chainid']) || !intval($_GET['chainid'])){

    echo 'Missing chain ID (Append ?chainid=chain_ID in URL)';

} else {

    //We have the inputs we need


    //Fetch Chain metadata and display it:
    $x = $this->Ideachains->read(array(
        'chainid' => $_GET['chainid'],
    ));

    if (count($x) < 1) {

        echo 'Invalid Chain ID';

    } elseif(!user_session(12701)) {

        echo blocked_reasoning(12701);

    } else {

        //Print on scree:
        view_json($x[0]);

    }

}
