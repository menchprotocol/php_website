<?php

if(!isset($_GET['i__hashtag'])){
    die('Missing Idea Hashtag');
}


//Define the user to fetch their discoveries for this idea:
if(!isset($_GET['e__handle']) || !strlen($_GET['e__handle'])){
    $_GET['e__handle'] = $player_e['e__handle'];
}


//Generate list & settings:
$list_settings = list_settings($_GET['i__hashtag']);
echo '<h1>' . view_i_title($list_settings['i']) . '</h1>';


foreach($this->Sources->read(array(
    'LOWER(e__handle)' => strtolower($_GET['e__handle']),
)) as $e){
    //List the idea:
    print_r(array(
        'find_next' => $this->Ledger->find_next($e['e__id'], $list_settings['i']['i__hashtag'], $list_settings['i'], 0, false),
        'tree_progress' => $this->Ledger->tree_progress($e['e__id'], $list_settings['i']),
    ));
}
