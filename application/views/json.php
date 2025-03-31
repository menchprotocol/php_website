<?php

//view__json($this->Mench_ledger->tree_full_history($focus_i, $focus_e['e__id']));


$count = 0;
$found = 0;
foreach($this->Source_cache->fetch(array(
    'e__id >' => 0,
), 0, 0, array('e__id' => 'ASC')) as $e){

    $count++;
    //echo $count.') @'.$e['e__handle'].' @'.$e['e__id'].'<hr />';

    if(count($this->Mench_ledger->read(array(
        'linkid' => $e['e__id'],
        'linktype' => 4251, //New Source Created
    )))){
       continue;
    }

    $creators = $this->Mench_ledger->fetch(array(
        'link_down' => $e['e__id'],
        'link_type IN (4230,4251)' => null, //Idea References
        'link_void' => 0, //Not Void
    ));

    //Lets log:
    $this->db->insert('menchledger', array(
        'linkid' => $e['e__id'],
        'linkplayer' => ( isset($creators[0]['link_player']) ? $creators[0]['link_player'] : $e['e__id'] ),
        'linktime' => ( isset($creators[0]['link_time']) ? $creators[0]['link_time'] : date("Y-m-d H:i:s", time()) ),
        'linkdomain' => ( isset($creators[0]['link_domain']) ? $creators[0]['link_domain'] : 0 ),
        'linktext' => $e['e__title'],
        'linktype' => 4251, //New Source Created
    ));

    //Add to cache:
    $this->db->insert('cacheplayers', array(
        'playerid' => $e['e__id'],
        'playerexternal' => intval($e['e__external']),
        'playernumber' => intval($e['e__weight']),
        'playerhandle' => $e['e__handle'],
        'playercover' => $e['e__cover'],
        'playertext' => $e['e__title'],
    ));

}

echo $found.'Updated Found';

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
