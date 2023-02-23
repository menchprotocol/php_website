<?php

$deleted_ideas = 0;
$deleted_links = 0;

if($member_e){
    foreach($this->I_model->fetch(array(
        'i__access NOT IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    )) as $deleted_i){
        $deleted_ideas++;
        $deleted_links += $this->I_model->remove($deleted_i['i__id'], $member_e['e__id'], 0);
    }
}


echo 'Deleted '.$deleted_links.' Links from '.$deleted_ideas.' deleted ideas';