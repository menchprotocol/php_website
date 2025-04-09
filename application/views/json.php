<?php

$type_count = array();
$_GET['disable_algolia'] = true;

$missing_ideas = array();
foreach($this->Ideas->read(array(), 0) as $idea_fix){
    if(!count($this->Links->read(array('linkid' => $idea_fix['ideaid'])))){
        array_push($missing_ideas, $idea_fix);
        $this->Ideas->create($idea_fix);
    }
}
$missing_players = array();
foreach($this->Players->read(array(), 0) as $player_fix){
    if(!count($this->Links->read(array('linkid' => $player_fix['playerid'])))){
        array_push($missing_players, $player_fix);
        $this->Players->create($player_fix);
    }
}

view_json(array(
    'ideas_missing' => count($missing_ideas),
    'players_missing' => count($missing_players),
    'ideas_list' => $missing_ideas,
    'players_list' => $missing_players,
    //'idea_settings' => idea_settings($focus_i['ideahashtag'], false),
    //'history' => $this->Links->history($focus_i, $focus_e['playerid']),
));
