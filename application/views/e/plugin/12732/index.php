<?php

//SOURCE/IDEA SYNC STATUSES (Hope to get zero)
$user_e = superpower_assigned(null, true);
$i_query = ( isset($_GET['i__id']) && intval($_GET['i__id']) ? array('i__id' => $_GET['i__id']) : array() );
$e_query = ( isset($_GET['e__id']) && intval($_GET['e__id']) ? array('e__id' => $_GET['e__id']) : array() );

if(!count($e_query)){
    echo 'IDEA: '.nl2br(print_r($this->I_model->match_x_status($user_e['e__id'], $i_query), true)).'<hr />';
}

if(!count($i_query)){
    echo 'SOURCE: '.nl2br(print_r($this->E_model->match_x_status($user_e['e__id'], $e_query), true)).'<hr />';
}