<?php

//List orphans:
echo '<div class="row justify-content">';
foreach($this->Ideas->read(array(
    ' NOT EXISTS (SELECT 1 FROM mench_ledger WHERE i__id=x__next AND x__type IN (' . njoin(4486) . ') AND x__privacy IN ('.njoin(7360) /* ACTIVE */.')) ' => null,
), 0, 0, array( 'i__weight' => 'desc' )) as $i) {
    echo view_card_i(7260, $i);

}
echo '</div>';