<?php

//Idea List Duplicates


//Do a query to detect ideas with the exact same title:
$q = $this->db->query('select in1.* from cache_ideas in1 where (select count(*) from cache_ideas in2 where in2.i__message = in1.i__message AND in2.i__privacy IN (' . join(',', dynamic_privacy_i(null, 0)) . ')) > 1 AND in1.i__privacy IN (' . join(',', dynamic_privacy_i(null, 0)) . ') ORDER BY in1.i__message ASC');
$duplicates = $q->result_array();

if(count($duplicates) > 0){

    $prev_title = null;
    $e___4737 = $this->config->item('e___4737'); //Idea Status

    foreach($duplicates as $in) {
        if ($prev_title != $in['i__message']) {
            echo '<hr />';
            $prev_title = $in['i__message'];
        }

        echo '<div><span data-toggle="tooltip" data-placement="right" title="'.$e___4737[$in['i__type']]['m__title'].': '.$e___4737[$in['i__type']]['m__message'].'">' . $e___4737[$in['i__type']]['m__cover'] . '</span> <a href="' . view_memory(42903,33286). $in['i__hashtag'] . '"><b>' . $in['i__message'] . '</b></a> #' . $in['i__id'] . '</div>';
    }

} else {

    echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>No duplicates found!';

}