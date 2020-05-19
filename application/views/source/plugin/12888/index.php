<?php

if(!isset($_GET['en_id']) || !intval($_GET['en_id'])){

    echo 'Missing source ID (Append ?en_id=SOURCE_ID in URL)';

} else {

    //Fetch Source:
    $ens = $this->SOURCE_model->fetch(array(
        'en_id' => intval($_GET['en_id']),
    ));
    if(count($ens) > 0){

        //unserialize metadata if needed:
        echo_json($this->SOURCE_model->metadat_experts($ens[0]));

    } else {
        echo 'Source @'.intval($_GET['en_id']).' not found!';
    }
}

