<?php

$obj = ( isset($_GET['obj']) ? $_GET['obj'] : null );
$s__id = ( isset($_GET['s__id']) && $obj ? intval($_GET['s__id']) : 0 );

if(!intval(view_memory(6404,12678))){
    die('Search is currently disabled');
}


//Call the update function and passon possible values:
print_r(update_algolia($obj, $s__id));