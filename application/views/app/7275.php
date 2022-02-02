<?php

/*
 *
 * Updates common base metadata for published ideas
 *
 * */

$start_time = time();
$query_filters = array(
    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PRIVATE
);
if(isset($_GET['i__id'])){
    $query_filters['i__id'] = intval($_GET['i__id']);
}

$published_i = $this->I_model->fetch($query_filters);
foreach($published_i as $published_in){
    $i = $this->I_model->metadata_common_base($published_in);
}

$total_time = time() - $start_time;


//Show json:
view_json(array(
    'message' => 'Common Base Metadata updated for '.count($published_i).' published idea'.view__s(count($published_i)).'.',
    'total_time' => view_time_hours($total_time),
    'item_time' => round(($total_time/count($published_i)),1).' Seconds',
    'last_item' => $i,
));