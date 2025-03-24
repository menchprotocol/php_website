<?php

die('Retired for now');

$transaction__id = 0;

foreach($this->Mench_ledger->fetch(array(), array(), 0, 0, array('link_id' => 'ASC')) as $x){

    //Update ID
    $transaction__id++;
    $this->db->where('link_id', $x['link_id']);
    $this->db->update('mench_ledger', array(
        'link_id' => $transaction__id,
    ));

    //Update ID Reference:
    $this->db->where('link_reference', $x['link_id']);
    $this->db->update('mench_ledger', array(
        'link_reference' => $transaction__id,
    ));

}

echo 'SUCCESS: Updated '.$transaction__id.' TRANSACTIONS with a new ID';