<?php

if(!isset($_GET['i__id']) || !intval($_GET['i__id'])){
    echo 'Missing Idea ID (Append ?i__id=IDEA_ID in URL)';
} else {
    //Fetch Idea:
    $ideas = $this->MAP_model->fetch(array(
        'i__id' => intval($_GET['i__id']),
    ));
    if(count($ideas) > 0){

        //unserialize metadata if needed:
        if(strlen($ideas[0]['i__metadata']) > 0){
            $ideas[0]['i__metadata'] = unserialize($ideas[0]['i__metadata']);
        }
        view_json($ideas[0]);

    } else {
        echo 'Source @'.intval($_GET['i__id']).' not found!';
    }
}