<?php

$deleted_i = 0;
$links_removed = 0;

if($player_e){
    foreach($this->I_model->fetch(array(
        'i__privacy NOT IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    )) as $deleted_i){
        $deleted_i++;
        $links_removed += $this->I_model->remove($deleted_i['i__id'], $player_e['e__id']);
    }
}


echo 'Deleted '.$links_removed.' Links from '.$deleted_i.' deleted nodes';