<?php

/*
 *
 * Updates idea insights (like min/max ideas, time & cost)
 * based on its common and expansion idea.
 *
 * */

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
