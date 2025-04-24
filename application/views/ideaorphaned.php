<?php

//List orphans:
echo '<div class="row justify-content">';
foreach($this->Ideas->read(array(
    ' NOT EXISTS (SELECT 1 FROM ideachain WHERE ideaid=chainidearight AND chainplayertype AND chainvoid=0 IN (' . join(',', $this->config->item('playerids___4486')) . ')) ' => null,
), 0, 0) as $i) {
    echo idea_view(7260, $i);

}
echo '</div>';