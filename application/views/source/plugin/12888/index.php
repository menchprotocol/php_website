<?php

if(!isset($_GET['source__id']) || !intval($_GET['source__id'])){

    echo 'Missing source ID (Append ?source__id=SOURCE_ID in URL)';

} else {

    //Fetch Source:
    $sources = $this->SOURCE_model->fetch(array(
        'source__id' => intval($_GET['source__id']),
    ));
    if(count($sources) > 0){

        //unserialize metadata if needed:
        view_json($this->SOURCE_model->metadat_experts($sources[0]));

    } else {
        echo 'Source @'.intval($_GET['source__id']).' not found!';
    }
}

