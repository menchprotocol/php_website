<?php

//SOURCE LIST DUPLICATES

$q = $this->db->query('select en1.* from mench_sources en1 where (select count(*) from mench_sources en2 where en2.source__title = en1.source__title AND en2.source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')) > 1 AND en1.source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ') ORDER BY en1.source__title ASC');
$duplicates = $q->result_array();

if(count($duplicates) > 0){

    $prev_title = null;
    $sources__6177 = $this->config->item('sources__6177'); //Source Status

    foreach($duplicates as $en) {

        if ($prev_title != $en['source__title']) {
            echo '<hr />';
            $prev_title = $en['source__title'];
        }

        echo '<span data-toggle="tooltip" data-placement="right" title="'.$sources__6177[$en['source__status']]['m_name'].': '.$sources__6177[$en['source__status']]['m_desc'].'">' . $sources__6177[$en['source__status']]['m_icon'] . '</span> <a href="/source/' . $en['source__id'] . '"><b>' . $en['source__title'] . '</b></a> @' . $en['source__id'] . '<br />';
    }

} else {
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No duplicates found!';
}