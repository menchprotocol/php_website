<?php

//SOURCE LIST DUPLICATES

$q = $this->db->query('select en1.* from mench__e en1 where (select count(*) from mench__e en2 where en2.e__title = en1.e__title AND en2.e__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')) > 1 AND en1.e__status IN (' . join(',', $this->config->item('sources_id_7358')) . ') ORDER BY en1.e__title ASC');
$duplicates = $q->result_array();

if(count($duplicates) > 0){

    $prev_title = null;
    $e___6177 = $this->config->item('e___6177'); //Source Status

    foreach($duplicates as $en) {

        if ($prev_title != $en['e__title']) {
            echo '<hr />';
            $prev_title = $en['e__title'];
        }

        echo '<span data-toggle="tooltip" data-placement="right" title="'.$e___6177[$en['e__status']]['m_name'].': '.$e___6177[$en['e__status']]['m_desc'].'">' . $e___6177[$en['e__status']]['m_icon'] . '</span> <a href="/@' . $en['e__id'] . '"><b>' . $en['e__title'] . '</b></a> @' . $en['e__id'] . '<br />';
    }

} else {
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No duplicates found!';
}