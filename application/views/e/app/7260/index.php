<?php

//Set activation time so if delete we then redirect here:
$this->session->set_userdata('session_time_7260', time());

$orphan_i = $this->I_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench__x WHERE i__id=x__right AND x__type IN (' . join(',', $this->config->item('n___4486')) . ') AND x__status IN ('.join(',', $this->config->item('n___7360')) /* ACTIVE */.')) ' => null,
    'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
    'i__id !=' => view_memory(6404,14002),
));

if(count($orphan_i) > 0){

    //List orphans:
    echo '<div class="list-group">';
    foreach($orphan_i as $i) {
        echo view_i_cover(7260, $i);

    }
    echo '</div>';

} else {
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No orphans found!';
}