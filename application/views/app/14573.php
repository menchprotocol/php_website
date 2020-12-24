<?php

$user_e = superpower_unlocked();
$deleted_ideas = 0;
$deleted_links = 0;

if($user_e){
    foreach($this->I_model->fetch(array(
        'i__type NOT IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
    )) as $deleted_i){
        $deleted_ideas++;
        $this_deleted = $this->I_model->remove($deleted_i['i__id'], $user_e['e__id']);
        echo '#'.$deleted_i['i__id'].' : '.$this_deleted.' Deleted<br />';
        $deleted_links += $this_deleted;
    }
}


echo '<br />Deleted '.$deleted_links.' Links from '.$deleted_ideas.' deleted ideas';