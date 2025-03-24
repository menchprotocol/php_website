<?php

//Idea List Duplicates


//Do a query to detect Ideas with the exact same title:
$q = $this->db->query('select in1.* from cache_ideas in1 where (select count(*) from cache_ideas in2 where in2.i__message = in1.i__message ORDER BY in1.i__message ASC');
$duplicates = $q->result_array();

if(count($duplicates) > 0){

    $prev_title = null;

    foreach($duplicates as $in) {
        if ($prev_title != $in['i__message']) {
            echo '<hr />';
            $prev_title = $in['i__message'];
        }

        echo '<div><a href="' . view__memory(42903,33286). $in['i__hashtag'] . '"><b>' . $in['i__message'] . '</b></a> #' . $in['i__id'] . '</div>';
    }

} else {

    echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>No duplicates found!';

}