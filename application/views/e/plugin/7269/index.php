<?php

//Set activation time so if delete we then redirect here:
$this->session->set_userdata('session_time_7269', time());

$e_orphans = $this->E_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench__x WHERE e__id=x__down AND x__type IN (' . join(',', $this->config->item('n___4592')) . ') AND x__status IN ('.join(',', $this->config->item('n___7360')) /* ACTIVE */.')) ' => null,
    'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
));

if(count($e_orphans) > 0){

    echo '<div class="list-group">';
    foreach($e_orphans as $e) {
        echo view_e($e, false, null, true, true);
    }
    echo '</div>';

} else {
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No orphans found!';
}
