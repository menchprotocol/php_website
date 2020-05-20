<?php

if(!isset($_GET['source__id']) || !intval($_GET['source__id'])){
    echo 'Missing source ID (Append ?source__id=SOURCE_ID in URL)';
} else {
    //Fetch Idea:
    $sources = $this->SOURCE_model->fetch(array(
        'source__id' => intval($_GET['source__id']),
    ));
    if(count($sources) > 0){

        //unserialize metadata if needed:
        if(strlen($sources[0]['source__metadata']) > 0){
            $sources[0]['source__metadata'] = unserialize($sources[0]['source__metadata']);
        }
        view_json($sources[0]);

    } else {
        echo 'Source @'.intval($_GET['source__id']).' not found!';
    }
}

