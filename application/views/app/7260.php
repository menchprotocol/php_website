<?php

//List orphans:
echo '<div class="row justify-content">';
foreach($this->I_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM table__x WHERE i__id=x__right AND x__type IN (' . join(',', $this->config->item('n___4486')) . ') AND x__privacy IN ('.join(',', $this->config->item('n___7360')) /* ACTIVE */.')) ' => null,
    'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //PUBLIC
), 0, 0, array( 'i__spectrum' => 'desc' )) as $i) {
    echo view_i_card(7260, 0, null, $i);

}
echo '</div>';