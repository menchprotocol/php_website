<?php

//Fetch data:
$engagements = $this->Db_model->tr_fetch(array(
    '( tr_en_child_id = ' . $u_id . ' OR tr_en_parent_id = ' . $u_id . ')' => null,
    '(tr_en_type_id NOT IN (' . join(',', $this->config->item('exclude_es')) . '))' => null,
), (is_dev() ? 20 : 100));

//Show this data:
//Fetch objects
echo '<div class="list-group list-grey" style="margin:-14px -5px -16px;">';
foreach ($engagements as $e) {
    echo echo_e($e);
}
echo '</div>';
?>