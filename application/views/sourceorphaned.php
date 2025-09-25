<?php

echo '<div class="row justify-content">';
foreach($this->Users->read(array(
    ' NOT EXISTS (SELECT 1 FROM ideachains WHERE userid=chainuseroutput AND chainvoid=0 AND chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')) ' => null,
), 0, 0) as $e) {
    echo user_view(7269, $e, null);
}
echo '</div>';