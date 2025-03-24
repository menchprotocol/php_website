<?php

//TODO REplace with $_GET['i__hashtag'] & $_GET['e__handle']

$obj = ( isset($_GET['obj']) ? $_GET['obj'] : null );
$s__id = ( isset($_GET['s__id']) && $obj ? intval($_GET['s__id']) : 0 );

//Call the update function and passon possible values:
print_r(update_algolia($obj, $s__id));