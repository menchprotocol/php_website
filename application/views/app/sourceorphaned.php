<?php

echo '<div class="row justify-content">';
foreach($this->Sources->read(array(
    ' NOT EXISTS (SELECT 1 FROM mench_ledger WHERE e__id=x__follower AND x__type IN (' . njoin(32292) . ') AND x__privacy IN ('.njoin(7360) /* ACTIVE */.')) ' => null,
), 0, 0) as $e) {
    echo view_card_e(7269, $e, null);
}
echo '</div>';