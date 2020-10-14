<?php

$obj = ( isset($_GET['obj']) ? $_GET['obj'] : false );
$s__id = ( isset($_GET['s__id']) ? intval($_GET['s__id']) : 0 );

if(!intval(view_memory(6404,12678))){
    die('Algolia is currently disabled');
}

//Call the update function and passon possible values:
view_json(update_algolia($obj, $s__id));