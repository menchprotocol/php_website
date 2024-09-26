<?php

//List orphans:
echo '<div class="row justify-content">';
foreach($this->I_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench_ledger WHERE i__id=x__next AND x__type IN (' . join(',', $this->config->item('n___4486')) . ') AND x__privacy IN ('.join(',', $this->config->item('n___7360')) /* ACTIVE */.')) ' => null,
    'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
), 0, 0, array( 'i__weight' => 'desc' )) as $i) {
    echo view_card_i(7260, $i);

}
echo '</div>';