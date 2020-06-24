<?php

//Set activation time so if delete we then redirect here:
$this->session->set_userdata('session_time_7260', time());

$orphan_ideas = $this->MAP_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench__x WHERE i__id=x__right AND x__type IN (' . join(',', $this->config->item('sources_id_4486')) . ') AND x__status IN ('.join(',', $this->config->item('sources_id_7360')) /* ACTIVE */.')) ' => null,
    'i__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
    'i__id NOT IN (' . join(',', array(config_var(13405), config_var(13406))) . ')' => null, //ACTIVE
));

if(count($orphan_ideas) > 0){

    //List orphans:
    echo '<div class="list-group">';
    foreach($orphan_ideas as $idea) {
        echo view_i($idea, 0, false, true);
    }
    echo '</div>';

} else {
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No orphans found!';
}