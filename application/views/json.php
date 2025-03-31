<?php

//view__json($this->Mench_ledger->tree_full_history($focus_i, $focus_e['e__id']));


$count = 0;
$found = 0;
foreach($this->Source_cache->fetch(array(
    'e__id >' => 0,
), 0, 0, array('e__id' => 'ASC')) as $e){

    $count++;
    //echo $count.') @'.$e['e__handle'].' @'.$e['e__id'].'<hr />';

    $creators = $this->Mench_ledger->fetch(array(
        'link_down' => $e['e__id'],
        'link_type IN (4230,4251)' => null, //Idea References
        'link_void' => 0, //Not Void
    ));

    $found += count($creators) ? 1 : 0;


}

echo $found.'/'.$count.' Found';

/*
$count = 0;
foreach($this->Idea_cache->fetch(array(
    'i__id >' => 0,
), 0) as $i){
    $count++;
    echo $count.') #'.$i['i__id']."<hr />";

    $this->Mench_ledger->create(array(
        'link_type' => 4250,
        'link_player' => $link_player,
        'link_up' => $link_player,
        'link_right' => $add_fields['i__id'],
    ));

}



foreach($this->Mench_ledger->fetch(array(
    'link_id >' => '0',
), array(), 10) as $x){
    echo $x['link_id']."<hr />";
}
*/

//Relations
