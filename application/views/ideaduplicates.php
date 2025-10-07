<?php

//Post List Duplicates


//Do a query to detect Posts with the exact same title:
$q = $this->db->query('select in1.* from posts in1 where (select count(*) from posts in2 where in2.postmessage = in1.postmessage ORDER BY in1.postmessage ASC');
$duplicates = $q->result_array();

if(count($duplicates) > 0){

    $prev_title = null;

    foreach($duplicates as $in) {
        if ($prev_title != $in['postmessage']) {
            echo '<hr />';
            $prev_title = $in['postmessage'];
        }

        echo '<div><a href="' . view_memory(42903,33286). $in['posthashtag'] . '"><b>' . $in['postmessage'] . '</b></a> #' . $in['postid'] . '</div>';
    }

} else {

    echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>No duplicates found!';

}