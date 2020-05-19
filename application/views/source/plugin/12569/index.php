<?php

$obj = ( isset($_GET['obj']) ? $_GET['obj'] : false );

//Update object weight

$stats = array(
    'start_time' => time(),
    'in_scanned' => 0,
    'in_updated' => 0,
    'in_total_weights' => 0,
    'en_scanned' => 0,
    'en_updated' => 0,
);

if(!$obj || $obj=='in'){

    //Update the weights for ideas and sources
    foreach($this->IDEA_model->fetch(array(
        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
    )) as $in) {
        $stats['in_scanned']++;
        $stats['in_updated'] += in_weight_updater($in);
    }

    //Now addup weights starting from primary Idea:
    $stats['in_total_weights'] = $this->IDEA_model->weight(config_var(12156));

}


if(!$obj || $obj=='en'){
    //Update the weights for ideas and sources
    foreach($this->SOURCE_model->fetch(array(
        'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
    )) as $en) {
        $stats['en_scanned']++;
        $stats['en_updated'] += en_weight_updater($en);
    }
}

$stats['end_time'] = time();
$stats['total_seconds'] = $stats['end_time'] - $stats['start_time'];
$stats['total_items'] = $stats['en_scanned'] + $stats['in_scanned'];
if($stats['total_seconds'] > 0){
    $stats['millisecond_speed'] = round(($stats['total_seconds'] / $stats['total_items'] * 1000), 3);
}

//Return results:
echo_json($stats);