<?php

$count = 0;
$previous = '1111111111111111111111111111111111111111';
foreach($this->Links->read(array('(linkhash IS NULL) OR (linkprevioushash IS NULL)' => NULL), array(), 0, 0, array('linkid' => 'DESC')) as $x){
    $hash = linkhash($x);
    $this->db->query("UPDATE menchledger SET linkprevioushash = '".$previous."', linkhash = '".$hash."' WHERE linkid=".$x['linkid'].";");
    $previous = $hash;
    $count++;
}
echo $count.' hashes synced';

