<?php

//SOURCE LIST DUPLICATES

$q = $this->db->query('select en1.* from mench_source en1 where (select count(*) from mench_source en2 where en2.en_name = en1.en_name AND en2.en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')) > 1 AND en1.en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ') ORDER BY en1.en_name ASC');
$duplicates = $q->result_array();

if(count($duplicates) > 0){

    $prev_title = null;
    $en_all_6177 = $this->config->item('en_all_6177'); //Source Status

    foreach ($duplicates as $en) {

        if ($prev_title != $en['en_name']) {
            echo '<hr />';
            $prev_title = $en['en_name'];
        }

        echo '<span data-toggle="tooltip" data-placement="right" title="'.$en_all_6177[$en['en_status_source_id']]['m_name'].': '.$en_all_6177[$en['en_status_source_id']]['m_desc'].'">' . $en_all_6177[$en['en_status_source_id']]['m_icon'] . '</span> <a href="/source/' . $en['en_id'] . '"><b>' . $en['en_name'] . '</b></a> @' . $en['en_id'] . '<br />';
    }

} else {
    echo '<div class="alert alert-success maxout"><span class="icon-block"><i class="fas fa-check-circle"></i></span>No duplicates found!</div>';
}