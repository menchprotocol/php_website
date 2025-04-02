<?php

echo '<div class="row justify-content">';
foreach($this->Nodeplayers->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM menchledger WHERE playerid=linkplayerdown AND linkvoid=0 AND linkplayertype IN (' . join(',', $this->config->item('playerids___32292')) . ')) ' => null,
), 0, 0) as $e) {
    echo view_card_player(7269, $e, null);
}
echo '</div>';