<?php

echo '<div class="row justify-content">';
foreach($this->Cacheplayers->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM menchledger WHERE playerid=linkdown AND linkvoid=0 AND linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')) ' => null,
), 0, 0) as $e) {
    echo view_card_player(7269, $e, null);
}
echo '</div>';