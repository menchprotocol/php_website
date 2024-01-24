<?php

echo '<div class="main_item row justify-content">';
foreach($this->X_model->fetch(array(
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__up' => 13450,
), array('x__down'), 0, 0, array('e__weight' => 'DESC')) as $e){
    echo view_card_e(12274, $e);
}
echo '</div>';