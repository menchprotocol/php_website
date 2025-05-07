<?php

//Idea List Duplicates


//Do a query to detect Ideas with the exact same title:
$q = $this->db->query('select in1.* from cacheideas in1 where (select count(*) from cacheideas in2 where in2.ideavalue = in1.ideavalue ORDER BY in1.ideavalue ASC');
$duplicates = $q->result_array();

if(count($duplicates) > 0){

    $prev_title = null;

    foreach($duplicates as $in) {
        if ($prev_title != $in['ideavalue']) {
            echo '<hr />';
            $prev_title = $in['ideavalue'];
        }

        echo '<div><a href="' . view_memory(42903,33286). $in['ideahashtag'] . '"><b>' . $in['ideavalue'] . '</b></a> #' . $in['ideaid'] . '</div>';
    }

} else {

    echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>No duplicates found!';

}