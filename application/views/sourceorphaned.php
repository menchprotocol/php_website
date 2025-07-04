<?php

echo '<div class="row justify-content">';
foreach($this->Handles->read(array(
    ' NOT EXISTS (SELECT 1 FROM ideachain WHERE handleid=chainhandleoutput AND chainvoid=0 AND chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')) ' => null,
), 0, 0) as $e) {
    echo handle_view(7269, $e, null);
}
echo '</div>';