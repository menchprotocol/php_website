<?php

//Fetch most recent subscriptions:
$all_subscriptions = $this->Db_model->w_fetch(array(), array('c','u','u_x','w_stats'), array(
    'w_id' => 'DESC',
), (is_dev() ? 10 : 100));
?>




<div class="row" style="padding-bottom:50px;">
    <div class="col-xs-6 cols">
        <?php
        echo '<div class="list-group">';
        foreach($all_subscriptions as $w){
            echo echo_w_console($w);
        }
        echo '</div>';
        ?>
    </div>
    <div class="col-xs-6 cols">
        <div class="alert alert-info" role="alert" id="mobile-no" style="display:none; margin-top:30px;">Choose a subscription to load details here...</div>

    </div>
</div>
