<?php

if(!isset($_GET['ideahashtag'])){
    die('Missing Idea Hashtag');
}


//Define the user to fetch their discoveries for this idea:
if(!isset($_GET['playerhandle']) || !strlen($_GET['playerhandle'])){
    $_GET['playerhandle'] = $player_session['playerhandle'];
}


//Generate list & settings:
$idea_settings = idea_settings($_GET['ideahashtag']);
echo '<h1>' . view_idea_title($idea_settings['i']) . '</h1>';


foreach($this->Players->read(array(
    'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
)) as $e){
    //List the idea:
    print_r(array(
        'idea_next' => $this->Links->idea_next($e['playerid'], $idea_settings['i']['ideahashtag'], $idea_settings['i'], 0, false),
        'progress' => $this->Links->progress($e['playerid'], $idea_settings['i']),
    ));
}
