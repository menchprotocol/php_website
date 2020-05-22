<?php

//Idea List Duplicates


//Do a query to detect Ideas with the exact same title:
$q = $this->db->query('select in1.* from mench_ideas in1 where (select count(*) from mench_ideas in2 where in2.idea__title = in1.idea__title AND in2.idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')) > 1 AND in1.idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ') ORDER BY in1.idea__title ASC');
$duplicates = $q->result_array();

if(count($duplicates) > 0){

    $prev_title = null;
    $sources__4737 = $this->config->item('sources__4737'); //Idea Status

    foreach($duplicates as $in) {
        if ($prev_title != $in['idea__title']) {
            echo '<hr />';
            $prev_title = $in['idea__title'];
        }

        echo '<div><span data-toggle="tooltip" data-placement="right" title="'.$sources__4737[$in['idea__status']]['m_name'].': '.$sources__4737[$in['idea__status']]['m_desc'].'">' . $sources__4737[$in['idea__status']]['m_icon'] . '</span> <a href="/g' . $in['idea__id'] . '"><b>' . $in['idea__title'] . '</b></a> #' . $in['idea__id'] . '</div>';
    }

} else {

    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No duplicates found!';

}