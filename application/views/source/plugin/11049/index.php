<?php

if(!isset($_GET['idea__id']) || !intval($_GET['idea__id'])){
    echo 'Missing Idea ID (Append ?idea__id=IDEA_ID in URL)';
} else {
    //Fetch Idea:
    $ins = $this->IDEA_model->fetch(array(
        'idea__id' => intval($_GET['idea__id']),
    ));
    if(count($ins) > 0){

        //unserialize metadata if needed:
        if(strlen($ins[0]['idea__metadata']) > 0){
            $ins[0]['idea__metadata'] = unserialize($ins[0]['idea__metadata']);
        }
        view_json($ins[0]);

    } else {
        echo 'Source @'.intval($_GET['idea__id']).' not found!';
    }
}