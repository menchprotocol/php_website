<?php

echo '<div class="row justify-content">';
foreach($this->E_model->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench_ledger WHERE e__id=x__follower AND x__type IN (' . join(',', $this->config->item('n___32292')) . ') AND x__privacy IN ('.join(',', $this->config->item('n___7360')) /* ACTIVE */.')) ' => null,
    'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
), 0, 0) as $e) {
    echo view_card_e(7269, $e, null);
}
echo '</div>';