<?php
$navigation = array(
    array(
        'my_url' => 'actionplan',
        'anchor' => '🚩 Action Plan',
    ),
    //TODO Add my account here...
);
?>
<div style="clear:both; margin-bottom:15px; padding-bottom:10px; border-bottom:1px solid #2f2739;">
    <ul class="nav nav-pills nav-pills-primary full-width">
        <?php
        foreach($navigation as $nav_item){
            echo '<li><a href="/my/'.$nav_item['my_url'].'" '.( isset($current) && $current==$nav_item['my_url'] ? ' style="color:#FFF; background-color:#2f2739 !important;"' : '' ).'>'.$nav_item['anchor'].'</a></li>';
        }

        //Is this a logged-in admin?
        $udata = $this->session->userdata('user');
        if(isset($udata) && count($udata)>0){
            echo '<li class="pull-right"><a href="/intents">Console <i class="fas fa-chevron-circle-right"></i></a></li>';
        }

        //Logout button:
        echo '<li class="pull-right"><a href="/logout">Logout <i class="fas fa-power-off"></i></a></li>';
        ?>
    </ul>
</div>