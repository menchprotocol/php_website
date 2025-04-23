<?php

echo '<div class="row justify-content">';
foreach($this->Players->read(array(
    ' NOT EXISTS (SELECT 1 FROM ideachain WHERE playerid=chainplayerdown AND unchain=0 AND chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')) ' => null,
), 0, 0) as $e) {
    echo player_view(7269, $e, null);
}
echo '</div>';