<?php

if(!isset($_GET['ideahashtag'])){
    die('Missing Idea Hashtag');
}


//Define the user to fetch their discoveries for this idea:
if(!isset($_GET['playerhandle']) || !strlen($_GET['playerhandle'])){
    $_GET['playerhandle'] = $player_e['playerhandle'];
}


//Generate list & settings:
$idea_settings = idea_settings($_GET['ideahashtag']);
echo '<h1>' . view_idea_title($idea_settings['i']) . '</h1>';


foreach($this->Players->fetch(array(
    'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
)) as $e){
    //List the idea:
    print_r(array(
        'find_next' => $this->Ledger->find_next($e['playerid'], $idea_settings['i']['ideahashtag'], $idea_settings['i'], 0, false),
        'tree_progress' => $this->Ledger->tree_progress($e['playerid'], $idea_settings['i']),
    ));
}
