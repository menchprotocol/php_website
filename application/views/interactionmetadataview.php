<?php

if(!isset($_GET['chainid']) || !intval($_GET['chainid'])){

    echo 'Missing link ID (Append ?chainid=link_ID in URL)';

} else {

    //We have the inputs we need


    //Fetch Link metadata and display it:
    $x = $this->Links->read(array(
        'chainid' => $_GET['chainid'],
    ));

    if (count($x) < 1) {

        echo 'Invalid Link ID';

    } elseif(!player_session(12701)) {

        echo blocked_reasoning(12701);

    } else {

        //Print on scree:
        view_json($x[0]);

    }

}
