<?php

//Set activation time so if delete we then redirect here:
$this->session->set_userdata('session_time_7269', time());

$source_orphans = $this->SOURCE_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench_interactions WHERE source__id=read__down AND read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ') AND read__status IN ('.join(',', $this->config->item('sources_id_7360')) /* ACTIVE */.')) ' => null,
    'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
));

if(count($source_orphans) > 0){

    echo '<div class="list-group">';
    foreach($source_orphans as $source) {
        echo view_source($source, false, null, true, true);
    }
    echo '</div>';

} else {
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No orphans found!';
}
