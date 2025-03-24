<?php

echo '<div class="row justify-content">';
foreach($this->Source_cache->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench_ledger WHERE e__id=LinkDown AND LinkType IN (' . join(',', $this->config->item('n___32292')) . ') AND LinkPrivacy IN ('.join(',', $this->config->item('n___7360')) /* ACTIVE */.')) ' => null,
    'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
), 0, 0) as $e) {
    echo view__card_e(7269, $e, null);
}
echo '</div>';