<?php

boost_power();
$starting_id = 0; //1580000
$previous = linkprevious($starting_id);


if($starting_id==0){
    $this->db->query("UPDATE menchledger SET linkprevious = NULL, linkhash = NULL WHERE ((linkhash IS NOT NULL) OR (linkprevious IS NOT NULL)) AND linkid >" . $starting_id . ";");
}

$count = 0;
$fixed = 0;
foreach ($this->Links->read(array(
    'linkid >' => $starting_id,
    'linkvoid >=' => 0
), array(), 0, 0, array('linkid' => 'ASC')) as $x) {
    $must_fix = false;
    if($x['linkprevious']!=$previous){
        $x['linkprevious'] = $previous;
        $must_fix = true;
    }
    $hash = linkhash($x);
    if($x['linkhash']!=$hash || $must_fix){
        $this->db->query("UPDATE menchledger SET linkprevious = '" . $previous . "', linkhash = '" . $hash . "' WHERE linkid=" . $x['linkid'] . ";");
        $fixed++;
    }
    $previous = $hash;
    $count++;
}
echo $fixed.'/'.$count . ' hashes';

