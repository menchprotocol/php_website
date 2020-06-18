<?php

if(!isset($_GET['x__id']) || !intval($_GET['x__id'])){

    echo 'Missing DISCOVER ID (Append ?x__id=INTERACTION_ID in URL)';

} else {

    //We have the inputs we need...


    //Fetch link metadata and display it:
    $reads = $this->DISCOVER_model->fetch(array(
        'x__id' => $_GET['x__id'],
    ));

    if (count($reads) < 1) {

        echo 'Invalid Read ID';

    } elseif(!superpower_assigned(12701)) {

        echo view_unauthorized_message(12701);

    } else {

        //unserialize metadata if needed:
        if(strlen($reads[0]['x__metadata']) > 0){
            $reads[0]['x__metadata'] = unserialize($reads[0]['x__metadata']);
        }

        //Print on scree:
        view_json($reads[0]);

    }

}
