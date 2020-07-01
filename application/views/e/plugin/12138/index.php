<?php

//List all Feature Ideas
echo '<div class="list-group">';
foreach($this->I_model->fetch(array(
    'i__status IN (' . join(',', $this->config->item('e___n_12138')) . ')' => null, //FEATURED
), 0, 0, array('i__weight' => 'DESC')) as $idea){
    echo view_i($idea);
}
echo '</div>';