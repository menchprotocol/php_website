<?php

$obj = ( isset($_GET['obj']) ? $_GET['obj'] : false );

//Update object weight

$stats = array(
    'start_time' => time(),
    'hashtag_scanned' => 0,
    'hashtag_updated' => 0,
    'hashtag_total_weights' => 0,
    'handle_scanned' => 0,
    'handle_changed' => 0,
);

if(!$obj || $obj==12273){

    //Update the weights for active hashtags
    foreach($this->Hashtags->read(array()) as $in) {
        $stats['hashtag_scanned']++;
        $stats['hashtag_updated'] += hashtag_number_calculator($in);
    }

}


if(!$obj || $obj==12274){
    //Update the weights for active Handles:
    foreach($this->Handles->read(array(
        )) as $en) {
        $stats['handle_scanned']++;
        $stats['handle_changed'] += handle_number_calculator($en);
    }
}

$stats['end_time'] = time();
$stats['total_seconds'] = $stats['end_time'] - $stats['start_time'];
$stats['total_items'] = $stats['handle_scanned'] + $stats['hashtag_scanned'];
if($stats['total_seconds'] > 0){
    $stats['millisecond_speed'] = round(($stats['total_seconds'] / $stats['total_items'] * 1000), 3);
}

//Return results:
view_json($stats);