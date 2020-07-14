<?php

if(!isset($_GET['i__id']) || !bigintval($_GET['i__id'])){
    echo 'Missing Idea ID (Append ?i__id=IDEA_ID in URL)';
} else {
    //Fetch Idea:
    $is = $this->I_model->fetch(array(
        'i__id' => bigintval($_GET['i__id']),
    ));
    if(count($is) > 0){

        //unserialize metadata if needed:
        if(strlen($is[0]['i__metadata']) > 0){
            $is[0]['i__metadata'] = unserialize($is[0]['i__metadata']);
        }
        view_json($is[0]);

    } else {
        echo 'Source @'.bigintval($_GET['i__id']).' not found!';
    }
}