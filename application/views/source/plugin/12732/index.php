<?php

//SOURCE/IDEA SYNC STATUSES (Hope to get zero)
$session_source = superpower_assigned(null, true);
$idea_query = ( isset($_GET['idea__id']) && intval($_GET['idea__id']) ? array('idea__id' => $_GET['idea__id']) : array() );
$source_query = ( isset($_GET['source__id']) && intval($_GET['source__id']) ? array('source__id' => $_GET['source__id']) : array() );

if(!count($source_query)){
    echo 'IDEA: '.nl2br(print_r($this->IDEA_model->match_read_status($session_source['source__id'], $idea_query), true)).'<hr />';
}

if(!count($idea_query)){
    echo 'SOURCE: '.nl2br(print_r($this->SOURCE_model->match_read_status($session_source['source__id'], $source_query), true)).'<hr />';
}