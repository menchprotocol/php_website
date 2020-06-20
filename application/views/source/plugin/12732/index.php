<?php

//SOURCE/IDEA SYNC STATUSES (Hope to get zero)
$session_source = superpower_assigned(null, true);
$idea_query = ( isset($_GET['i__id']) && intval($_GET['i__id']) ? array('i__id' => $_GET['i__id']) : array() );
$e_query = ( isset($_GET['e__id']) && intval($_GET['e__id']) ? array('e__id' => $_GET['e__id']) : array() );

if(!count($e_query)){
    echo 'IDEA: '.nl2br(print_r($this->MAP_model->match_x_status($session_source['e__id'], $idea_query), true)).'<hr />';
}

if(!count($idea_query)){
    echo 'SOURCE: '.nl2br(print_r($this->SOURCE_model->match_x_status($session_source['e__id'], $e_query), true)).'<hr />';
}