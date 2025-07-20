<?php

//Hashtag List Duplicates


//Do a query to detect Hashtags with the exact same title:
$q = $this->db->query('select in1.* from ideachainhashtags in1 where (select count(*) from ideachainhashtags in2 where in2.hashtagtext = in1.hashtagtext ORDER BY in1.hashtagtext ASC');
$duplicates = $q->result_array();

if(count($duplicates) > 0){

    $prev_title = null;

    foreach($duplicates as $in) {
        if ($prev_title != $in['hashtagtext']) {
            echo '<hr />';
            $prev_title = $in['hashtagtext'];
        }

        echo '<div><a href="' . view_memory(42903,33286). $in['hashtagterm'] . '"><b>' . $in['hashtagtext'] . '</b></a> #' . $in['hashtagid'] . '</div>';
    }

} else {

    echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>No duplicates found!';

}