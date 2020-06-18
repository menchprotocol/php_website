<?php

if(!isset($_GET['e__id']) || !intval($_GET['e__id'])){

    echo 'Missing source ID (Append ?e__id=SOURCE_ID in URL)';

} else {

    //Fetch Source:
    $sources = $this->SOURCE_model->fetch(array(
        'e__id' => intval($_GET['e__id']),
    ));
    if(count($sources) > 0){

        //unserialize metadata if needed:
        view_json($this->SOURCE_model->metadata_experts($sources[0]));

    } else {
        echo 'Source @'.intval($_GET['e__id']).' not found!';
    }
}

