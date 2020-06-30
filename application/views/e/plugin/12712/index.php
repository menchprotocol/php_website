<?php

if(!isset($_GET['e__id']) || !intval($_GET['e__id'])){
    echo 'Missing source ID (Append ?e__id=SOURCE_ID in URL)';
} else {
    //Fetch Idea:
    $sources = $this->E_model->fetch(array(
        'e__id' => intval($_GET['e__id']),
    ));
    if(count($sources) > 0){

        //unserialize metadata if needed:
        if(strlen($sources[0]['e__metadata']) > 0){
            $sources[0]['e__metadata'] = unserialize($sources[0]['e__metadata']);
        }
        view_json($sources[0]);

    } else {
        echo 'Source @'.intval($_GET['e__id']).' not found!';
    }
}

