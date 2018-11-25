<?php

//Fetch most recent subscriptions:
$ws = $this->Db_model->w_fetch(array(), array('in','en','u_x','w_stats'), array(
    'w_id' => 'DESC',
), (is_dev() ? 10 : 100));
?>

<div class="row" style="padding-bottom:50px;">
    <div class="col-xs-6 cols">
        <?php
        echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-comment-plus"></i> Action Plans</h5>';
        echo '<div class="list-group list-grey">';
        foreach($ws as $w){
            echo echo_w_console($w);
        }
        echo '</div>';
        ?>
    </div>
    <div class="col-xs-6 cols">
        <?php $this->load->view('actionplans/actionplan_right_col'); ?>
    </div>
</div>