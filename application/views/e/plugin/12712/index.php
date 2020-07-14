<?php

if(!isset($_GET['e__id']) || !bigintval($_GET['e__id'])){
    echo 'Missing source ID (Append ?e__id=SOURCE_ID in URL)';
} else {
    //Fetch Idea:
    $es = $this->E_model->fetch(array(
        'e__id' => bigintval($_GET['e__id']),
    ));
    if(count($es) > 0){

        //unserialize metadata if needed:
        if(strlen($es[0]['e__metadata']) > 0){
            $es[0]['e__metadata'] = unserialize($es[0]['e__metadata']);
        }
        view_json($es[0]);

    } else {
        echo 'Source @'.bigintval($_GET['e__id']).' not found!';
    }
}

