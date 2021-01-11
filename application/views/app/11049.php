<?php

if(!isset($_GET['i__id']) || !intval($_GET['i__id'])){
    echo 'Missing Blog ID (Append ?i__id=BLOG_ID in URL)';
} else {
    //Fetch Blog:
    $is = $this->I_model->fetch(array(
        'i__id' => intval($_GET['i__id']),
    ));
    if(count($is) > 0){

        //unserialize metadata if needed:
        if(strlen($is[0]['i__metadata']) > 0){
            $is[0]['i__metadata'] = unserialize($is[0]['i__metadata']);
        }
        view_json($is[0]);

    } else {
        echo 'Source @'.intval($_GET['i__id']).' not found!';
    }
}