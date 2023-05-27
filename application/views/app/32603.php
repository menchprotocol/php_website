<?php

$found_idea = false;
foreach($this->I_model->fetch(array(
    'i__id' => ( isset($_GET['i__id']) && $_GET['i__id'] > 0 ? $_GET['i__id'] : 0 ),
    'i__type' => 32603, //Sign Agreement
    'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
)) as $i_sign){
    $found_idea = true;

    //Allow user to sign instantly:
    echo '<h1 class="msg-frame" style="text-align: center; padding: 21px 0 !important; font-size:2.1em;">'.$i_sign['i__title'].'</h1>';
    echo view_i__cache($i_sign);
    echo view_sign($i_sign);

}


if(!$found_idea){
    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missign Agreement Idea ID</div>';
}