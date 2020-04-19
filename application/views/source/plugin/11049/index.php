<?php

if(!isset($_GET['in_id']) || !intval($_GET['in_id'])){
    echo 'Missing Idea ID (Append ?in_id=IDEA_ID in URL)';
} else {
    //Fetch Idea:
    $ins = $this->IDEA_model->in_fetch(array(
        'in_id' => intval($_GET['in_id']),
    ));
    if(count($ins) > 0){

        //unserialize metadata if needed:
        if(strlen($ins[0]['in_metadata']) > 0){
            $ins[0]['in_metadata'] = unserialize($ins[0]['in_metadata']);
        }
        echo_json($ins[0]);

    } else {
        echo 'Source @'.intval($_GET['in_id']).' not found!';
    }
}