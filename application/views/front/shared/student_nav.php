<?php
$navigation = array(
    array(
        'my_url' => 'actionplan',
        'anchor' => '🚩 Action Plan',
    ),
    array(
        'my_url' => 'leaderboard',
        'anchor' => '🏆 Leaderboard',
    ),
);
?>
<div style="clear:both; margin-bottom:15px; padding-bottom:10px; border-bottom:1px solid #000;">
    <ul class="nav nav-pills nav-pills-primary full-width">
        <?php
        foreach($navigation as $nav_item){
            echo '<li><a href="/my/'.$nav_item['my_url'].'" '.( isset($current) && $current==$nav_item['my_url'] ? ' style="color:#FFF; background-color:#000 !important;"' : '' ).'>'.$nav_item['anchor'].'</a></li>';
        }

        //Logout button:
        echo '<li class="pull-right"><a href="/api_v1/logout">Logout <i class="fa fa-power-off" aria-hidden="true"></i></a></li>';

        //Is this a logged-in admin?
        $udata = $this->session->userdata('user');
        if(isset($udata) && count($udata)>0){
            echo '<li class="pull-right"><a href="/console">Console <img src="/img/bp_128.png" style="width:20px; height:20px; margin-top:-5px;"></a></li>';
        }
        ?>
    </ul>
</div>