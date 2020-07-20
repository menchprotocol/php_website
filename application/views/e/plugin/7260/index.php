<?php

//Set activation time so if delete we then redirect here:
$this->session->set_userdata('session_time_7260', time());

$orphan_i = $this->I_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench__x WHERE i__id=x__right AND x__type IN (' . join(',', $this->config->item('n___4486')) . ') AND x__status IN ('.join(',', $this->config->item('n___7360')) /* ACTIVE */.')) ' => null,
    'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
    'i__id NOT IN (' . join(',', array(config_var(12137), config_var(13406))) . ')' => null, //ACTIVE
));

if(count($orphan_i) > 0){

    //List orphans:
    echo '<div class="list-group">';
    foreach($orphan_i as $i) {
        echo view_i($i, 0, false, true);
    }
    echo '</div>';

} else {
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No orphans found!';
}