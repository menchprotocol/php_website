<?php

$sources__6177 = $this->config->item('sources__6177'); //Source Status

$source_orphans = $this->SOURCE_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench_interactions WHERE source__id=read__down AND read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ') AND read__status IN ('.join(',', $this->config->item('sources_id_7360')) /* ACTIVE */.')) ' => null,
    'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
));

if(count($source_orphans) > 0){

    //List orphans:
    foreach($source_orphans  as $count => $source_orphan) {

        //Show source:
        echo '<div>'.($count+1).') <span data-toggle="tooltip" data-placement="right" title="'.$sources__6177[$source_orphan['source__status']]['m_name'].': '.$sources__6177[$source_orphan['source__status']]['m_desc'].'">' . $sources__6177[$source_orphan['source__status']]['m_icon'] . '</span> <a href="/@'.$source_orphan['source__id'].'"><b>'.$source_orphan['source__title'].'</b></a>';

        //Do we need to delete?
        if(isset($_GET['take_action']) && $_GET['take_action']=='delete_all'){

            //Delete links:
            $links_deleted = $this->SOURCE_model->unlink($source_orphan['source__id'], $session_source['source__id']);

            //Delete source:
            $this->SOURCE_model->update($source_orphan['source__id'], array(
                'source__status' => 6178, /* Player Deleted */
            ), true, $session_source['source__id']);

            //Show confirmation:
            echo ' [Source + '.$links_deleted.' links Deleted]';

        }

        echo '</div>';

    }

    //Show option to delete all:
    if(!isset($_GET['take_action']) || $_GET['take_action']!='delete_all'){
        echo '<br />';
        echo '<a class="delete-all" href="javascript:void(0);" onclick="$(\'.delete-all\').toggleClass(\'hidden\')">Delete All</a>';
        echo '<div class="delete-all hidden maxout"><b class="read">WARNING</b>: All sources and all their links will be deleted. ONLY do this after reviewing all orphans one-by-one and making sure they cannot become a child of an existing source.<br /><br /></div>';
        echo '<a class="delete-all hidden maxout" href="?take_action=delete_all" onclick="">Confirm: <b>Delete All</b> &raquo;</a>';
    }

} else {
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No orphans found!';
}
