<?php

//List all Feature Ideas
foreach($this->IDEA_model->fetch(array(
    'idea__status IN (' . join(',', $this->config->item('sources_id_12138')) . ')' => null, //FEATURED
), 0, 0, array('idea__weight' => 'DESC')) as $idea){
    echo view_idea($idea);
}
