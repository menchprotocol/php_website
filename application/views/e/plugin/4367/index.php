<?php

$transaction__id = 1;

foreach($this->X_model->fetch(array('x__id' => 1174126), array(), 0, 0, array('x__id' => 'ASC')) as $x){

    //Update ID
    $this->db->where('x__id', $x['x__id']);
    $this->db->update('mench__x', array(
        'x__id' => $transaction__id,
    ));

    //Update ID Reference:
    $this->db->where('x__reference', $x['x__id']);
    $this->db->update('mench__x', array(
        'x__reference' => $transaction__id,
    ));


    $transaction__id++;
    break;
}

echo 'Updated '.$transaction__id.' TRANSACTIONS with a new ID';