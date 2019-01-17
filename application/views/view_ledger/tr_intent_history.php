<?php

$filters = array(
    '(tr_in_parent_id=' . $in_id . ' OR tr_in_child_id=' . $in_id . ($tr_id > 0 ? ' OR tr_tr_parent_id=' . $tr_id : '') . ')' => null,
);

//Do we have further limitations on the filter?
if($tr_en_type_id > 0){
    $filters['tr_en_type_id'] = $tr_en_type_id;
}

//Fetch objects
echo '<div class="list-group list-grey">';
foreach ($this->Database_model->fn___tr_fetch($filters, array('in_child','en_type'), (fn___is_dev() ? 30 : 100)) as $tr) {
    echo fn___echo_tr_row($tr);
}
echo '</div>';
?>
