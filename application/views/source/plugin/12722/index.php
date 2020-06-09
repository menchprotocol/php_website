<?php

if(!isset($_GET['read__id']) || !intval($_GET['read__id'])){

    echo 'Missing Read ID (Append ?read__id=READ_ID in URL)';

} else {

    //We have the inputs we need...


    //Fetch link metadata and display it:
    $reads = $this->READ_model->fetch(array(
        'read__id' => $_GET['read__id'],
    ));

    if (count($reads) < 1) {

        echo 'Invalid Read ID';

    } elseif(!superpower_assigned(12701)) {

        echo view_unauthorized_message(12701);

    } else {

        //unserialize metadata if needed:
        if(strlen($reads[0]['read__metadata']) > 0){
            $reads[0]['read__metadata'] = unserialize($reads[0]['read__metadata']);
        }

        //Print on scree:
        view_json($reads[0]);

    }

}
