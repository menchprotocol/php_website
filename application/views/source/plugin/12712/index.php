<?php

if(!isset($_GET['source__id']) || !intval($_GET['source__id'])){
    echo 'Missing source ID (Append ?source__id=SOURCE_ID in URL)';
} else {
    //Fetch Idea:
    $ens = $this->SOURCE_model->fetch(array(
        'source__id' => intval($_GET['source__id']),
    ));
    if(count($ens) > 0){

        //unserialize metadata if needed:
        if(strlen($ens[0]['source__metadata']) > 0){
            $ens[0]['source__metadata'] = unserialize($ens[0]['source__metadata']);
        }
        view_json($ens[0]);

    } else {
        echo 'Source @'.intval($_GET['source__id']).' not found!';
    }
}

