<?php

$obj = ( isset($_GET['obj']) ? $_GET['obj'] : false );

//Update object weight

$stats = array(
    'start_time' => time(),
    'post_scanned' => 0,
    'post_updated' => 0,
    'post_total_weights' => 0,
    'user_scanned' => 0,
    'user_changed' => 0,
);

if(!$obj || $obj==12273){

    //Update the weights for active posts
    foreach($this->Posts->read(array()) as $in) {
        $stats['post_scanned']++;
        $stats['post_updated'] += post_number_calculator($in);
    }

}


if(!$obj || $obj==12274){
    //Update the weights for active Users:
    foreach($this->Users->read(array(
        )) as $en) {
        $stats['user_scanned']++;
        $stats['user_changed'] += user_number_calculator($en);
    }
}

$stats['end_time'] = time();
$stats['total_seconds'] = $stats['end_time'] - $stats['start_time'];
$stats['total_items'] = $stats['user_scanned'] + $stats['post_scanned'];
if($stats['total_seconds'] > 0){
    $stats['millisecond_speed'] = round(($stats['total_seconds'] / $stats['total_items'] * 1000), 3);
}

//Return results:
view_json($stats);