<?php

//Fetch most recent subscriptions:
$all_subscriptions = $this->Db_model->w_fetch(array(), array('c','u','u_x','w_stats'), array(
    'w_id' => 'DESC',
), (is_dev() ? 10 : 100));
?>

<div class="row" style="padding-bottom:50px;">
    <div class="col-xs-6 cols">
        <?php
        echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-comment-plus"></i> Subscriptions Browser</h5>';
        echo '<div class="list-group list-grey">';
        foreach($all_subscriptions as $w){
            echo echo_w_console($w);
        }
        echo '</div>';
        ?>
    </div>
    <div class="col-xs-6 cols">
        <?php $this->load->view('console/subscription_views'); ?>
    </div>
</div>
