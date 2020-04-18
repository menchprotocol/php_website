<?php

if(!isset($_GET['en_id']) || !intval($_GET['en_id'])){
    echo 'Missing source ID (Append ?en_id=SOURCE_ID in URL)';
}

//Fetch Idea:
$ens = $this->SOURCE_model->en_fetch(array(
    'en_id' => intval($_GET['en_id']),
));
if(count($ens) > 0){
    echo_json(unserialize($ens[0]));
} else {
    echo 'Source @'.intval($_GET['en_id']).' not found!';
}
