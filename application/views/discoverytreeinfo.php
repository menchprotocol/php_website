<?php

if(!isset($_GET['ideahashtag'])){
    die('Missing Idea Hashtag');
}


//Define the user to fetch their discoveries for this idea:
if(!isset($_GET['playerhandle']) || !strlen($_GET['playerhandle'])){
    $_GET['playerhandle'] = $player_e['playerhandle'];
}


//Generate list & settings:
$list_settings = list_settings($_GET['ideahashtag']);
echo '<h1>' . view__i_title($list_settings['i']) . '</h1>';


foreach($this->Source_cache->fetch(array(
    'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
)) as $e){
    //List the idea:
    print_r(array(
        'find_next' => $this->Mench_ledger->find_next($e['playerid'], $list_settings['i']['ideahashtag'], $list_settings['i'], 0, false),
        'tree_progress' => $this->Mench_ledger->tree_progress($e['playerid'], $list_settings['i']),
    ));
}
