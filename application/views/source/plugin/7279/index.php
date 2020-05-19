<?php

$obj = ( isset($_GET['obj']) ? $_GET['obj'] : false );
$obj_id = ( isset($_GET['obj_id']) ? intval($_GET['obj_id']) : 0 );

if(!intval(config_var(12678))){
    die('Algolia is currently disabled');
}

//Call the update function and passon possible values:
view_json(update_algolia($obj, $obj_id));