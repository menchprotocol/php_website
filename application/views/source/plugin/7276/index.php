<?php

/*
 *
 * Updates idea insights (like min/max ideas, time & cost)
 * based on its common and expansion idea.
 *
 * */


if(isset($_GET['idea__id'])){
    $match_columns = array(
        'idea__id' => $idea__id,
    );
} else {
    //All Featured:
    $match_columns = array(
        'idea__status IN (' . join(',', $this->config->item('sources_id_12138')) . ')' => null, //FEATURED
    );
}

$already_scanned = array(); //Keeps track of those
$completed = 0;
foreach($this->IDEA_model->fetch($match_columns) as $idea){

    $completed++;

    return view_json(array(
        'results' => $this->IDEA_model->metadata_extra_insights( $idea ),
    ));
    break;
}


return view_json(array(
    'status' => 0,
    'message' => 'Updated '.$completed.' Ideas',
));

/*

$idea__id = ( isset($_GET['idea__id']) ? intval($_GET['idea__id']) : 0 );
$idea__id = ( $idea__id>0 ? $idea__id : config_var(12156) );
$ideas = $this->IDEA_model->fetch(array(
    'idea__id' => $idea__id,
    'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
));

if(count($ideas)){
    return view_json(array(
        'results' => $this->IDEA_model->metadata_extra_insights( $ideas[0] ),
    ));
} else {
    return view_json(array(
        'status' => 0,
        'message' => 'Could not find PUBLIC Idea #'.$idea__id,
    ));
}

*/