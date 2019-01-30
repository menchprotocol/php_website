<?php

$filters = array(
    '(tr_in_parent_id=' . $in_id . ' OR tr_in_child_id=' . $in_id . ($tr_id > 0 ? ' OR tr_tr_id=' . $tr_id : '') . ')' => null,
);

//Do we have further limitations on the filter?
if($tr_type_en_id > 0){
    $filters['tr_type_en_id'] = $tr_type_en_id;
}

//Fetch objects
echo '<div class="title" style="margin:-15px 0 5px 0; padding: 0;"><h4 style="margin:0px; padding: 0; font-size:0.8em;"><i
                                        class="fas fa-history"></i> Intent History</h4></div>';
echo '<div class="list-group list-grey">';
foreach ($this->Database_model->fn___tr_fetch($filters, array('in_child','en_type'), (fn___is_dev() ? 30 : 100)) as $tr) {
    echo fn___echo_tr_row($tr);
}
echo '</div>';
?>
