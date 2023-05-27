<?php

$found_idea = false;
foreach($this->I_model->fetch(array(
    'i__id' => ( isset($_GET['i__id']) && $_GET['i__id'] > 0 ? $_GET['i__id'] : 0 ),
    'i__type' => 32603, //Sign Agreement
    'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
)) as $i_sign){
    $found_idea = true;
    //Allow user to sign instantly:
    $this->load->view('x_layout', array(
        'top_i__id' => $i_sign['i__id'],
        'i' => $i_sign,
        'member_e' => superpower_unlocked(),
    ));
}


if(!$found_idea){
    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missign Agreement Idea ID</div>';
}