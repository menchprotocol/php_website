<?php

$en_all_6177 = $this->config->item('en_all_6177'); //Source Status

$orphan_ens = $this->SOURCE_model->en_fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench_ledger WHERE en_id=ln_portfolio_source_id AND ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ') AND ln_status_source_id IN ('.join(',', $this->config->item('en_ids_7360')) /* Transaction Status Active */.')) ' => null,
    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
));

if(count($orphan_ens) > 0){

    //List orphans:
    foreach ($orphan_ens  as $count => $orphan_en) {

        //Show source:
        echo '<div>'.($count+1).') <span data-toggle="tooltip" data-placement="right" title="'.$en_all_6177[$orphan_en['en_status_source_id']]['m_name'].': '.$en_all_6177[$orphan_en['en_status_source_id']]['m_desc'].'">' . $en_all_6177[$orphan_en['en_status_source_id']]['m_icon'] . '</span> <a href="/source/'.$orphan_en['en_id'].'"><b>'.$orphan_en['en_name'].'</b></a>';

        //Do we need to delete?
        if(isset($_GET['take_action']) && $_GET['take_action']=='delete_all'){

            //Delete links:
            $links_deleted = $this->SOURCE_model->en_unlink($orphan_en['en_id'], $session_en['en_id']);

            //Delete source:
            $this->SOURCE_model->en_update($orphan_en['en_id'], array(
                'en_status_source_id' => 6178, /* Player Deleted */
            ), true, $session_en['en_id']);

            //Show confirmation:
            echo ' [Source + '.$links_deleted.' links Deleted]';

        }

        echo '</div>';

    }

    //Show option to delete all:
    if(!isset($_GET['take_action']) || $_GET['take_action']!='delete_all'){
        echo '<br />';
        echo '<a class="delete-all" href="javascript:void(0);" onclick="$(\'.delete-all\').toggleClass(\'hidden\')">Delete All</a>';
        echo '<div class="delete-all hidden maxout"><b style="color: #FF0000;">WARNING</b>: All sources and all their links will be deleted. ONLY do this after reviewing all orphans one-by-one and making sure they cannot become a child of an existing source.<br /><br /></div>';
        echo '<a class="delete-all hidden maxout" href="?take_action=delete_all" onclick="">Confirm: <b>Delete All</b> &raquo;</a>';
    }

} else {
    echo '<div class="alert alert-success maxout"><span class="icon-block"><i class="fas fa-check-circle"></i></span>No orphans found!</div>';
}
