<?php

if(strlen($_GET['i__id'])){

    foreach($this->I_model->fetch(array(
        'i__id IN (' . $_GET['i__id'] . ')' => null, //SOURCE LINKS
    ), 0, 0, array('i__id' => 'ASC')) as $loaded_i){

        $all_ids = $this->I_model->recursive_down_ids($loaded_i, 'ALL');


        //Main Idea:
        echo '<h2><a href="/i/i_go/'.$loaded_i['i__id'].'">'.$loaded_i['i__title'].'</a> '.count($all_ids).' IDEAS</h2>';

        echo '<div class="row justify-content">';
        foreach($this->I_model->fetch(array(
            'i__id IN (' . join(',', $all_ids) . ')' => null, //Active Member
        ), 0) as $this_i){
            echo view_card_i(12273, 0, null, $this_i);
        }
        echo '</div>';

    }

} else {

    echo 'Missing Idea ID';

}
