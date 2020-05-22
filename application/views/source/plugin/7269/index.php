<?php

$source_orphans = $this->SOURCE_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench_interactions WHERE source__id=read__down AND read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ') AND read__status IN ('.join(',', $this->config->item('sources_id_7360')) /* ACTIVE */.')) ' => null,
    'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
));

if(count($source_orphans) > 0){

    echo '<div class="list-group">';
    foreach($source_orphans as $source) {
        echo view_source($source, false, null, true, true);
    }
    echo '</div>';


    //Show option to delete all:
    if(!isset($_GET['take_action']) || $_GET['take_action']!='delete_all'){
        echo '<br />';
        echo '<br />';
        echo '<a class="delete-all" href="javascript:void(0);" onclick="$(\'.delete-all\').toggleClass(\'hidden\')">Delete All</a>';
        echo '<div class="delete-all hidden maxout"><b class="read">WARNING</b>: All sources and all their links will be deleted. ONLY do this after reviewing all orphans one-by-one and making sure they cannot become a child of an existing source.<br /><br /></div>';
        echo '<a class="delete-all hidden maxout" href="?take_action=delete_all" onclick="">Confirm: <b>Delete All</b> &raquo;</a>';
    }

} else {
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No orphans found!';
}
