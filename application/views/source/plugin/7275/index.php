<?php

/*
 *
 * Updates common base metadata for published ideas
 *
 * */

$start_time = time();
$filters = array(
    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
);
if(isset($_GET['in_id'])){
    $filters['in_id'] = intval($_GET['in_id']);
}

$published_ins = $this->IDEA_model->fetch($filters);
foreach($published_ins as $published_in){
    $idea = $this->IDEA_model->metadata_common_base($published_in);
}

$total_time = time() - $start_time;


//Show json:
echo_json(array(
    'message' => 'Common Base Metadata updated for '.count($published_ins).' published idea'.echo__s(count($published_ins)).'.',
    'total_time' => echo_time_hours($total_time),
    'item_time' => round(($total_time/count($published_ins)),1).' Seconds',
    'last_item' => $idea,
));