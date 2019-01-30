<?php

//Fetch data:
$trs = $this->Database_model->fn___tr_fetch(array(
    '( tr_en_child_id = ' . $en_id . ' OR tr_en_parent_id = ' . $en_id . ' OR tr_miner_en_id = ' . $en_id . ')' => null,
), array('en_type'), (fn___is_dev() ? 20 : 100));

//Show this data:
//Fetch objects
echo '<div class="list-group list-grey" style="margin:-14px -5px -16px;">';
foreach ($trs as $e) {
    echo fn___echo_tr_row($e);
}
echo '</div>';
?>