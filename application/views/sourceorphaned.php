<?php

echo '<div class="row justify-content">';
foreach($this->Source_cache->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench_ledger WHERE playerid=linkdown AND linkvoid=0 AND linktype IN (' . join(',', $this->config->item('n___32292')) . ')) ' => null,
), 0, 0) as $e) {
    echo view__card_e(7269, $e, null);
}
echo '</div>';