<?php

//List orphans:
echo '<div class="row justify-content">';
foreach($this->Idea_cache->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM mench_ledger WHERE i__id=link_right AND link_type AND link_void=0 IN (' . join(',', $this->config->item('n___4486')) . ')) ' => null,
), 0, 0) as $i) {
    echo view__card_i(7260, $i);

}
echo '</div>';