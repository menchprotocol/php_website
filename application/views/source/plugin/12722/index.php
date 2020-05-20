<?php

if(!isset($_GET['read__id']) || !intval($_GET['read__id'])){

    echo 'Missing Transaction ID (Append ?read__id=READ_ID in URL)';

} else {

    //We have the inputs we need...


    //Fetch link metadata and display it:
    $lns = $this->READ_model->fetch(array(
        'read__id' => $_GET['read__id'],
    ));

    if (count($lns) < 1) {

        echo 'Invalid Transaction ID';

    } elseif(!superpower_assigned(12701)) {

        echo view_unauthorized_message(12701);

    } else {

        //unserialize metadata if needed:
        if(strlen($lns[0]['read__metadata']) > 0){
            $lns[0]['read__metadata'] = unserialize($lns[0]['read__metadata']);
        }

        //Print on scree:
        view_json($lns[0]);

    }

}
