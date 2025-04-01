<?php

//view__json($this->Menchledger->tree_full_history($focus_i, $focus_e['playerid']));

$count = 0;
foreach($this->Cacheideas->fetchold(array(
    'i__id >' => 0,
), 0) as $i){

    $count++;
    echo $count.') #'.$i['i__type']."<hr />";

    //Lets log:
    $new_i_id = intval($i['i__id'])+100000;

    //$this->db->query("UPDATE menchledger SET linkleft=".$migrate_s__id." WHERE linkleft=".$ideaid.";");


}