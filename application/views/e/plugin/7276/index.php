<?php

/*
 *
 * Updates idea insights (like min/max ideas, time & cost)
 * based on its common and expansion idea.
 *
 * */

if(isset($_GET['i__id'])){
    $query_filters = array(
        'i__id' => intval($_GET['i__id']),
    );
} else {
    //All Orphans:
    $query_filters = array(
        ' NOT EXISTS (SELECT 1 FROM mench__x WHERE i__id=x__right AND x__type IN (' . join(',', $this->config->item('n___4486')) . ') AND x__status IN ('.join(',', $this->config->item('n___7360')) /* ACTIVE */.')) ' => null,
        'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
    );
}

$stats = array(
    'start_time' => time(),
    'i_scanned' => 0,
    'featured_scanned' => 0,
);

$is_scanned = array(); //Keeps track of those
foreach($this->I_model->fetch($query_filters, 0, 0, array('i__weight' => 'DESC')) as $i){

    if(in_array($i['i__id'], $is_scanned)){
        continue;
    }

    $results = $this->I_model->metadata_e_insights($i);
    $is_scanned = array_merge($is_scanned, $results['p___ids']);

    $stats['featured_scanned']++;
    $stats['i_scanned'] += count($results['p___ids']);

}

$stats['end_time'] = time();
$stats['total_seconds'] = $stats['end_time'] - $stats['start_time'];
return view_json($stats);