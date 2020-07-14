<?php

if(!isset($_GET['e__id']) || !intval($_GET['e__id'])){

    echo 'Missing source ID (Append ?e__id=SOURCE_ID in URL)';

} else {

    //Fetch Source:
    $es = $this->E_model->fetch(array(
        'e__id' => intval($_GET['e__id']),
    ));
    if(count($es) > 0){

        //unserialize metadata if needed:
        view_json($this->E_model->metadata_experts($es[0]));

    } else {
        echo 'Source @'.intval($_GET['e__id']).' not found!';
    }
}

