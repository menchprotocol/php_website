<?php

/*
 *
 * Updates idea insights (like min/max ideas, time & cost)
 * based on its common and expansion idea.
 *
 * */

$in_id = ( isset($_GET['in_id']) ? intval($_GET['in_id']) : 0 );
$in_id = ( $in_id>0 ? $in_id : config_var(12156) );
$ins = $this->IDEA_model->fetch(array(
    'in_id' => $in_id,
    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
));

if(count($ins)){
    return view_json(array(
        'results' => $this->IDEA_model->metadata_extra_insights( $ins[0] ),
    ));
} else {
    return view_json(array(
        'status' => 0,
        'message' => 'Could not find PUBLIC Idea #'.$in_id,
    ));
}
