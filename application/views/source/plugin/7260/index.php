<?php

$orphan_ideas = $this->IDEA_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench_interactions WHERE idea__id=read__right AND read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ') AND read__status IN ('.join(',', $this->config->item('sources_id_7360')) /* ACTIVE */.')) ' => null,
    'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
    'idea__id !=' => config_var(12156), //Not the Starting Idea
));

if(count($orphan_ideas) > 0){

    //List orphans:
    echo '<div class="list-group">';
    foreach($orphan_ideas as $idea) {
        echo view_idea($idea, 0, false, true);
    }
    echo '</div>';


    //Show option to delete all:
    if(!isset($_GET['take_action']) || $_GET['take_action']!='delete_all'){
        echo '<br />';
        echo '<br />';
        echo '<a class="delete-all" href="javascript:void(0);" onclick="$(\'.delete-all\').toggleClass(\'hidden\')">Delete All</a>';
        echo '<div class="delete-all hidden maxout"><b class="read">WARNING</b>: All ideas and all their links will be deleted. ONLY do this after reviewing all orphans one-by-one and making sure they cannot become a child of an existing idea.<br /><br /></div>';
        echo '<a class="delete-all hidden maxout" href="?take_action=delete_all" onclick="">Confirm: <b>Delete All</b> &raquo;</a>';
    }

} else {
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>No orphans found!';
}