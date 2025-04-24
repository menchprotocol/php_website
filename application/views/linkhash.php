<?php

boost_power();
$starting_id = 1; //Will only check currrent hash to ensure its all valid...
$previous = chainprevious($starting_id);


if($starting_id==0){
    $this->db->query("UPDATE ideachain SET chainprevious = NULL, chainhash = NULL WHERE ((chainhash IS NOT NULL) OR (chainprevious IS NOT NULL)) AND chainid >" . $starting_id . ";");
}

$count = 0;
$fixed = 0;
foreach ($this->Links->read(array(
    'chainid >' => $starting_id,
    'chainvoid >=' => 0
), array(), 0, 0, array('chainid' => 'ASC')) as $x) {
    $must_fix = false;
    if($x['chainprevious']!=$previous){
        $x['chainprevious'] = $previous;
        $must_fix = true;
    }
    $hash = chainhash($x);
    if($x['chainhash']!=$hash || $must_fix){
        if($starting_id!=1){
            $this->db->query("UPDATE ideachain SET chainprevious = '" . $previous . "', chainhash = '" . $hash . "' WHERE chainid=" . $x['chainid'] . ";");
        }
        $fixed++;
    }
    $previous = $hash;
    $count++;
}
echo $fixed.'/'.($count+$starting_id) . ' link hashes are out of sync.';

