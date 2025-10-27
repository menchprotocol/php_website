<?php

//Post List Duplicates


//Do a query to detect Posts with the exact same title:
$q = $this->db->query('select in1.* from posts in1 where (select count(*) from posts in2 where in2.postmessageraw = in1.postmessageraw ORDER BY in1.postmessageraw ASC');
$duplicates = $q->result_array();

if(count($duplicates) > 0){

    $prev_title = null;

    foreach($duplicates as $in) {
        if ($prev_title != $in['postmessageraw']) {
            echo '<hr />';
            $prev_title = $in['postmessageraw'];
        }

        echo '<div><a href="' . view_memory(42903,33286). $in['posthashtag'] . '"><b>' . $in['postmessageraw'] . '</b></a> #' . $in['postid'] . '</div>';
    }

} else {

    echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>No duplicates found!';

}