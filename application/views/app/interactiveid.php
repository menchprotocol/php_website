<?php

die('Retired for now');

$transaction__id = 0;

foreach($this->Mench_ledger->fetch(array(), array(), 0, 0, array('LinkId' => 'ASC')) as $x){

    //Update ID
    $transaction__id++;
    $this->db->where('LinkId', $x['LinkId']);
    $this->db->update('mench_ledger', array(
        'LinkId' => $transaction__id,
    ));

    //Update ID Reference:
    $this->db->where('LinkReference', $x['LinkId']);
    $this->db->update('mench_ledger', array(
        'LinkReference' => $transaction__id,
    ));

}

echo 'SUCCESS: Updated '.$transaction__id.' TRANSACTIONS with a new ID';