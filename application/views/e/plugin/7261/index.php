<?php

//Idea List Duplicates


//Do a query to detect Ideas with the exact same title:
$q = $this->db->query('select in1.* from mench__i in1 where (select count(*) from mench__i in2 where in2.i__title = in1.i__title AND in2.i__status IN (' . join(',', $this->config->item('e___n_7356')) . ')) > 1 AND in1.i__status IN (' . join(',', $this->config->item('e___n_7356')) . ') ORDER BY in1.i__title ASC');
$duplicates = $q->result_array();

if(count($duplicates) > 0){

    $prev_title = null;
    $e___4737 = $this->config->item('e___4737'); //Idea Status

    foreach($duplicates as $in) {
        if ($prev_title != $in['i__title']) {
            echo '<hr />';
            $prev_title = $in['i__title'];
        }

        echo '<div><span data-toggle="tooltip" data-placement="right" title="'.$e___4737[$in['i__status']]['m_name'].': '.$e___4737[$in['i__status']]['m_desc'].'">' . $e___4737[$in['i__status']]['m_icon'] . '</span> <a href="/i/i_go/' . $in['i__id'] . '"><b>' . $in['i__title'] . '</b></a> #' . $in['i__id'] . '</div>';
    }

} else {

    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No duplicates found!';

}