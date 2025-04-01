<?php

//List orphans:
echo '<div class="row justify-content">';
foreach($this->Cacheideas->fetch(array(
    ' NOT EXISTS (SELECT 1 FROM menchledger WHERE ideaid=linkright AND linktype AND linkvoid=0 IN (' . join(',', $this->config->item('n___4486')) . ')) ' => null,
), 0, 0) as $i) {
    echo view__card_i(7260, $i);

}
echo '</div>';