<?php

echo '<div class="row justify-content">';
foreach($this->Sources->read(array(
    ' NOT EXISTS (SELECT 1 FROM ideachain WHERE sourceid=chainsourcedown AND chainvoid=0 AND chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')) ' => null,
), 0, 0) as $e) {
    echo source_view(7269, $e, null);
}
echo '</div>';