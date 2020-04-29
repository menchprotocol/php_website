<?php

//Idea List Duplicates


//Do a query to detect Ideas with the exact same title:
$q = $this->db->query('select in1.* from mench_idea in1 where (select count(*) from mench_idea in2 where in2.in_title = in1.in_title AND in2.in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')) > 1 AND in1.in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ') ORDER BY in1.in_title ASC');
$duplicates = $q->result_array();

if(count($duplicates) > 0){

    $prev_title = null;
    $en_all_4737 = $this->config->item('en_all_4737'); //Idea Status

    foreach($duplicates as $in) {
        if ($prev_title != $in['in_title']) {
            echo '<hr />';
            $prev_title = $in['in_title'];
        }

        echo '<div><span data-toggle="tooltip" data-placement="right" title="'.$en_all_4737[$in['in_status_source_id']]['m_name'].': '.$en_all_4737[$in['in_status_source_id']]['m_desc'].'">' . $en_all_4737[$in['in_status_source_id']]['m_icon'] . '</span> <a href="/idea/go/' . $in['in_id'] . '"><b>' . $in['in_title'] . '</b></a> #' . $in['in_id'] . '</div>';
    }

} else {

    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No duplicates found!';

}