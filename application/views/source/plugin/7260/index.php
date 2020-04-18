<?php

$en_all_4737 = $this->config->item('en_all_4737'); // Idea Status


$orphan_ins = $this->IDEA_model->in_fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench_ledger WHERE in_id=ln_next_idea_id AND ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ') AND ln_status_source_id IN ('.join(',', $this->config->item('en_ids_7360')) /* Transaction Status Active */.')) ' => null,
    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
    'in_id !=' => config_var(12156), //Not the Starting Idea
));

if(count($orphan_ins) > 0){

    //List orphans:
    foreach ($orphan_ins as $count => $orphan_in) {

        //Show idea:
        echo '<div>'.($count+1).') <span data-toggle="tooltip" data-placement="right" title="'.$en_all_4737[$orphan_in['in_status_source_id']]['m_name'].': '.$en_all_4737[$orphan_in['in_status_source_id']]['m_desc'].'">' . $en_all_4737[$orphan_in['in_status_source_id']]['m_icon'] . '</span> <a href="/idea/'.$orphan_in['in_id'].'"><b>'.$orphan_in['in_title'].'</b></a>';

        //Do we need to delete?
        if(isset($_GET['take_action']) && $_GET['take_action']=='delete_all'){

            //Delete idea links:
            $links_deleted = $this->IDEA_model->in_unlink($orphan_in['in_id'] , $session_en['en_id']);

            //Delete idea:
            $this->IDEA_model->in_update($orphan_in['in_id'], array(
                'in_status_source_id' => 6182, /* Idea Deleted */
            ), true, $session_en['en_id']);

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
        echo '<div class="delete-all hidden maxout"><b style="color: #FF0000;">WARNING</b>: All ideas and all their links will be deleted. ONLY do this after reviewing all orphans one-by-one and making sure they cannot become a child of an existing idea.<br /><br /></div>';
        echo '<a class="delete-all hidden maxout" href="?take_action=delete_all" onclick="">Confirm: <b>Delete All</b> &raquo;</a>';
    }

} else {
    echo '<div class="alert alert-success maxout"><span class="icon-block"><i class="fas fa-check-circle"></i></span>No orphans found!</div>';
}