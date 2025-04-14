<?php

boost_power();
$previous = linkprevious();
$count = 0;
$fixed = 0;
foreach ($this->Links->read(array(), array(), 0, 0, array('linkid' => 'ASC')) as $x) {
    $must_fix = false;
    if($x['linkprevious']!=$previous){
        $x['linkprevious'] = $previous;
        $must_fix = false;
    }
    $hash = linkhash($x);
    if($x['linkhash']!=$hash || $x['linkprevious']!=$previous){
        $this->db->query("UPDATE menchledger SET linkprevious = '" . $previous . "', linkhash = '" . $hash . "' WHERE linkid=" . $x['linkid'] . ";");
        //echo $x['linkid'].': '.$x['linkhash'].'!='.$hash.' OR '.$x['linkprevious'].'!='.$previous.'<hr />';
        $fixed++;
    }
    $previous = $hash;
    $count++;
}
echo $fixed.'/'.$count . ' hashes';

