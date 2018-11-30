<?php
$udata = auth(array(1308)); //Is Trainers
?>
<div class="p-header <?= ($udata ? 'hidden' : '') ?>">
    <ul class="nav nav-pills nav-pills-primary full-width">
        <?php
        $navigation = array(
            array(
                'my_url' => 'actionplan',
                'anchor' => '🚩 Action Plan',
            ),
            //TODO Add my account here...
        );
        foreach ($navigation as $nav_item) {
            echo '<li><a href="/my/' . $nav_item['my_url'] . '" ' . (isset($current) && $current == $nav_item['my_url'] ? ' style="color:#FFF; background-color:#2f2739 !important;"' : '') . '>' . $nav_item['anchor'] . '</a></li>';
        }
        //Student Logout button:
        echo '<li class="pull-right"><a href="/logout">Logout <i class="fas fa-power-off"></i></a></li>';
        ?>
    </ul>
</div>