<?php

echo '<div class="row justify-content">';
foreach($this->E_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM table__x WHERE e__id=x__down AND x__type IN (' . join(',', $this->config->item('n___32292')) . ') AND x__access IN ('.join(',', $this->config->item('n___7360')) /* ACTIVE */.')) ' => null,
    'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
), 0, 0, array()) as $e) {
    echo view_card_e(7269, $e, null);
}
echo '</div>';