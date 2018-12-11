<?php

$engagements = $this->Db_model->tr_fetch(array(
    '(tr_in_parent_id=' . $in_id . ' OR tr_in_child_id=' . $in_id . ')' => null,
), array(), (is_dev() ? 20 : 100));

//Fetch objects
echo '<div class="list-group list-grey">';
foreach ($engagements as $e) {
    echo echo_e($e);
}
echo '</div>';

?>
