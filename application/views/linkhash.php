<?php

$previous = null;
$count = 0;
$fixed = 0;
foreach ($this->Links->read(array('linkid >' => 1559000), array(), 0, 0, array('linkid' => 'ASC')) as $x) {
    if(!$previous){
        echo print_r($x);
        $previous = $x['linkhash'];
        continue;
    }
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
echo $fixed.'/'.$count . ' hashes fixed';

