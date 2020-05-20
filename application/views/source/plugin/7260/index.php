<?php

$sources__4737 = $this->config->item('sources__4737'); // Idea Status


$orphan_ins = $this->IDEA_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench_read WHERE idea__id=read__right AND read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ') AND read__status IN ('.join(',', $this->config->item('sources_id_7360')) /* ACTIVE */.')) ' => null,
    'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
    'idea__id !=' => config_var(12156), //Not the Starting Idea
));

if(count($orphan_ins) > 0){

    //List orphans:
    foreach($orphan_ins as $count => $orphan_in) {

        //Show idea:
        echo '<div>'.($count+1).') <span data-toggle="tooltip" data-placement="right" title="'.$sources__4737[$orphan_in['idea__status']]['m_name'].': '.$sources__4737[$orphan_in['idea__status']]['m_desc'].'">' . $sources__4737[$orphan_in['idea__status']]['m_icon'] . '</span> <a href="/idea/go/'.$orphan_in['idea__id'].'"><b>'.$orphan_in['idea__title'].'</b></a>';

        //Do we need to delete?
        if(isset($_GET['take_action']) && $_GET['take_action']=='delete_all'){

            //Delete idea links:
            $links_deleted = $this->IDEA_model->unlink($orphan_in['idea__id'] , $session_en['source__id']);

            //Delete idea:
            $this->IDEA_model->update($orphan_in['idea__id'], array(
                'idea__status' => 6182, /* Idea Deleted */
            ), true, $session_en['source__id']);

            //Show confirmation:
            echo ' [Idea + '.$links_deleted.' links Deleted]';

        }

        //Done showing the idea:
        echo '</div>';
    }

    //Show option to delete all:
    if(!isset($_GET['take_action']) || $_GET['take_action']!='delete_all'){
        echo '<br />';
        echo '<a class="delete-all" href="javascript:void(0);" onclick="$(\'.delete-all\').toggleClass(\'hidden\')">Delete All</a>';
        echo '<div class="delete-all hidden maxout"><b class="read">WARNING</b>: All ideas and all their links will be deleted. ONLY do this after reviewing all orphans one-by-one and making sure they cannot become a child of an existing idea.<br /><br /></div>';
        echo '<a class="delete-all hidden maxout" href="?take_action=delete_all" onclick="">Confirm: <b>Delete All</b> &raquo;</a>';
    }

} else {
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No orphans found!';
}