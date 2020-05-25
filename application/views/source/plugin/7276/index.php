<?php

/*
 *
 * Updates idea insights (like min/max ideas, time & cost)
 * based on its common and expansion idea.
 *
 * */

if(isset($_GET['idea__id'])){
    $match_columns = array(
        'idea__id' => intval($_GET['idea__id']),
    );
} else {
    //All Featured:
    $match_columns = array(
        'idea__status IN (' . join(',', $this->config->item('sources_id_12138')) . ')' => null, //FEATURED
    );
}

$stats = array(
    'start_time' => time(),
    'idea_scanned' => 0,
    'featured_scanned' => 0,
);

$already_scanned = array(); //Keeps track of those
foreach($this->IDEA_model->fetch($match_columns, 0, 0, array('idea__weight' => 'DESC')) as $idea){

    if(in_array($idea['idea__id'], $already_scanned)){
        continue;
    }

    $results = $this->IDEA_model->metadata_extra_insights($idea);
    $already_scanned = array_merge($already_scanned, $results['__idea___ids']);

    $stats['featured_scanned']++;
    $stats['idea_scanned'] += count($results['__idea___ids']);

}

$stats['end_time'] = time();
$stats['total_seconds'] = $stats['end_time'] - $stats['start_time'];
return view_json($stats);