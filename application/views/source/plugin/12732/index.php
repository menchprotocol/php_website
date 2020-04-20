<?php

//SOURCE/IDEA SYNC STATUSES (Hope to get zero)
$session_en = superpower_assigned();
$in_query = ( isset($_GET['in_id']) && intval($_GET['in_id']) ? array('in_id' => $_GET['in_id']) : array() );
$en_query = ( isset($_GET['en_id']) && intval($_GET['en_id']) ? array('en_id' => $_GET['en_id']) : array() );

if(!count($en_query)){
    echo 'IDEA: '.nl2br(print_r($this->IDEA_model->in_match_ln_status($session_en['en_id']), $in_query)).'<hr />';
}

if(!count($in_query)){
    echo 'SOURCE: '.nl2br(print_r($this->SOURCE_model->en_match_ln_status($session_en['en_id']), $en_query)).'<hr />';
}