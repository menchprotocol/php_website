<?php

$deleted_ideas = 0;
$deleted_links = 0;

foreach($this->I_model->fetch(array(
    'i__type NOT IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
)) as $deleted_i){
    $deleted_ideas++;
    $deleted_links += $this->I_model->remove($deleted_i['i__id']);
}

echo 'Deleted '.$deleted_links.' Links from '.$deleted_ideas.' deleted ideas';