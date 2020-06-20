<?php

if(!isset($_GET['x__id']) || !intval($_GET['x__id'])){

    echo 'Missing DISCOVER ID (Append ?x__id=INTERACTION_ID in URL)';

} else {

    //We have the inputs we need...


    //Fetch link metadata and display it:
    $discoveries = $this->DISCOVER_model->fetch(array(
        'x__id' => $_GET['x__id'],
    ));

    if (count($discoveries) < 1) {

        echo 'Invalid Interaction ID';

    } elseif(!superpower_assigned(12701)) {

        echo view_unauthorized_message(12701);

    } else {

        //unserialize metadata if needed:
        if(strlen($discoveries[0]['x__metadata']) > 0){
            $discoveries[0]['x__metadata'] = unserialize($discoveries[0]['x__metadata']);
        }

        //Print on scree:
        view_json($discoveries[0]);

    }

}
