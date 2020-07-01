<?php

/*
 *
 * Updates common base metadata for published ideas
 *
 * */

$start_time = time();
$filters = array(
    'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
);
if(isset($_GET['i__id'])){
    $filters['i__id'] = intval($_GET['i__id']);
}

$published_ideas = $this->I_model->fetch($filters);
foreach($published_ideas as $published_in){
    $idea = $this->I_model->metadata_common_base($published_in);
}

$total_time = time() - $start_time;


//Show json:
view_json(array(
    'message' => 'Common Base Metadata updated for '.count($published_ideas).' published idea'.view__s(count($published_ideas)).'.',
    'total_time' => view_time_hours($total_time),
    'item_time' => round(($total_time/count($published_ideas)),1).' Seconds',
    'last_item' => $idea,
));