<?php

//List orphans:
echo '<div class="row justify-content">';
foreach($this->Ideas->read(array(
    ' NOT EXISTS (SELECT 1 FROM menchledger WHERE ideaid=linkidearight AND linkplayertype AND linkvoid=0 IN (' . join(',', $this->config->item('playerids___4486')) . ')) ' => null,
), 0, 0) as $i) {
    echo view_idea(7260, $i);

}
echo '</div>';