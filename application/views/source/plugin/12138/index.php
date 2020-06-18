<?php

//List all Feature Ideas
echo '<div class="list-group">';
foreach($this->MAP_model->fetch(array(
    'i__status IN (' . join(',', $this->config->item('sources_id_12138')) . ')' => null, //FEATURED
), 0, 0, array('i__weight' => 'DESC')) as $idea){
    echo view_idea($idea);
}
echo '</div>';