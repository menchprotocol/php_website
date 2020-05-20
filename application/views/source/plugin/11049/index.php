<?php

if(!isset($_GET['idea__id']) || !intval($_GET['idea__id'])){
    echo 'Missing Idea ID (Append ?idea__id=IDEA_ID in URL)';
} else {
    //Fetch Idea:
    $ideas = $this->IDEA_model->fetch(array(
        'idea__id' => intval($_GET['idea__id']),
    ));
    if(count($ideas) > 0){

        //unserialize metadata if needed:
        if(strlen($ideas[0]['idea__metadata']) > 0){
            $ideas[0]['idea__metadata'] = unserialize($ideas[0]['idea__metadata']);
        }
        view_json($ideas[0]);

    } else {
        echo 'Source @'.intval($_GET['idea__id']).' not found!';
    }
}