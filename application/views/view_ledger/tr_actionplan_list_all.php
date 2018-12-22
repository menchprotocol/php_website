<?php

//Fetch most recent Action Plans:
$trs = $this->Database_model->tr_fetch(array(
    'tr_en_type_id' => 4235, //Action Plan Intent
    'tr_in_parent_id' => 0, //Top-level Action Plan intents only...
    'tr_status >=' => 0, //New+
), array('in_child', 'en_parent'), (fn___is_dev() ? 10 : 100));

?>

<div class="row" style="padding-bottom:50px;">
    <div class="col-xs-6 cols">
        <?php
        echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-comment-plus"></i> Action Plans</h5>';
        echo '<div class="list-group list-grey">';
        foreach ($trs as $tr) {
            echo echo_w_matrix($tr);
        }
        echo '</div>';
        ?>
    </div>
    <div class="col-xs-6 cols">
        <?php $this->load->view('view_ledger/tr_actionplan_right_column'); ?>
    </div>
</div>