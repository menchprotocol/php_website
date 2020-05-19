<?php

if(!isset($_GET['en_id']) || !intval($_GET['en_id'])){
    echo 'Missing source ID (Append ?en_id=SOURCE_ID in URL)';
} else {
    //Fetch Idea:
    $ens = $this->SOURCE_model->fetch(array(
        'en_id' => intval($_GET['en_id']),
    ));
    if(count($ens) > 0){

        //unserialize metadata if needed:
        if(strlen($ens[0]['en_metadata']) > 0){
            $ens[0]['en_metadata'] = unserialize($ens[0]['en_metadata']);
        }
        view_json($ens[0]);

    } else {
        echo 'Source @'.intval($_GET['en_id']).' not found!';
    }
}

