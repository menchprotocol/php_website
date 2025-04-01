<?php

//view__json($this->Menchledger->tree_full_history($focus_i, $focus_e['playerid']));

$count = 0;
foreach($this->Cacheideas->fetchold(array(
    'i__id >' => 0,
), 0) as $i){

    $count++;
    //echo $count.') #'.$i['i__type']."<hr />";

    //Lets log:
    $new_i_id = intval($i['i__id'])+100000;

    $this->db->query("UPDATE cacheideas SET ideatype=".$i['i__type']." WHERE ideaid=".$new_i_id.";");

}

echo $count.' TOTAL';