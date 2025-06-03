<?php

if(!isset($_GET['ideahashtag'])){
    die('Missing Idea Hashtag');
}


//Define the user to fetch their discoveries for this idea:
if(!isset($_GET['sourcehandle']) || !strlen($_GET['sourcehandle'])){
    $_GET['sourcehandle'] = $source_session['sourcehandle'];
}


//Generate list & settings:
$idea_settings = idea_settings($_GET['ideahashtag']);
echo '<h1>' . view_idea_title($idea_settings['i']) . '</h1>';


foreach($this->Sources->read(array(
    'LOWER(sourcehandle)' => strtolower($_GET['sourcehandle']),
)) as $e){
    //List the idea:
    print_r(array(
        'next_ideas' => $this->Chains->next_ideas($e['sourceid'], $idea_settings['i']['ideahashtag'], $idea_settings['i'], 0, false),
        'progress' => $this->Chains->progress($e['sourceid'], $idea_settings['i']),
    ));
}
