<?php

$obj = ( isset($_GET['obj']) ? $_GET['obj'] : false );

//Update object weight

$stats = array(
    'start_time' => time(),
    'idea_scanned' => 0,
    'idea_updated' => 0,
    'idea_total_weights' => 0,
    'player_scanned' => 0,
    'player_changed' => 0,
);

if(!$obj || $obj==12273){

    //Update the weights for active ideas
    foreach($this->Ideas->read(array()) as $in) {
        $stats['idea_scanned']++;
        $stats['idea_updated'] += idea_number_calculator($in);
    }

}


if(!$obj || $obj==12274){
    //Update the weights for active Players:
    foreach($this->Players->read(array(
        )) as $en) {
        $stats['player_scanned']++;
        $stats['player_changed'] += player_number_calculator($en);
    }
}

$stats['end_time'] = time();
$stats['total_seconds'] = $stats['end_time'] - $stats['start_time'];
$stats['total_items'] = $stats['player_scanned'] + $stats['idea_scanned'];
if($stats['total_seconds'] > 0){
    $stats['millisecond_speed'] = round(($stats['total_seconds'] / $stats['total_items'] * 1000), 3);
}

//Return results:
view_json($stats);