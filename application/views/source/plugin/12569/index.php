<?php

$obj = ( isset($_GET['obj']) ? $_GET['obj'] : false );

//Update object weight

$stats = array(
    'start_time' => time(),
    'idea_scanned' => 0,
    'idea_updated' => 0,
    'idea_total_weights' => 0,
    'source_scanned' => 0,
    'source_updated' => 0,
);

if(!$obj || $obj==4535){

    //Update the weights for ideas and sources
    foreach($this->IDEA_model->fetch(array(
        'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
    )) as $in) {
        $stats['idea_scanned']++;
        $stats['idea_updated'] += idea__weight_calculator($in);
    }

    //Now addup weights starting from primary Idea:
    $stats['idea_total_weights'] = $this->IDEA_model->weight($this->config->item('featured_idea__id'));

}


if(!$obj || $obj==4536){
    //Update the weights for ideas and sources
    foreach($this->SOURCE_model->fetch(array(
        'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
    )) as $en) {
        $stats['source_scanned']++;
        $stats['source_updated'] += source__weight_calculator($en);
    }
}

$stats['end_time'] = time();
$stats['total_seconds'] = $stats['end_time'] - $stats['start_time'];
$stats['total_items'] = $stats['source_scanned'] + $stats['idea_scanned'];
if($stats['total_seconds'] > 0){
    $stats['millisecond_speed'] = round(($stats['total_seconds'] / $stats['total_items'] * 1000), 3);
}

//Return results:
view_json($stats);