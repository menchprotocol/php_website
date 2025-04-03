<?php

echo '<div class="row justify-content">';
foreach($this->Players->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM menchledger WHERE playerid=linkplayerdown AND linkvoid=0 AND linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')) ' => null,
), 0, 0) as $e) {
    echo view_player(7269, $e, null);
}
echo '</div>';