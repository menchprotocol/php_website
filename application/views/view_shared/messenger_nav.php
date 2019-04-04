<div class="p-header">
    <ul class="nav nav-pills nav-pills-primary full-width">
        <?php
        $navigation = array(
            array(
                'my_url' => 'actionplan',
                'anchor' => '<i class="fas fa-flag yellow"></i> Action Plan',
            ),
            array(
                'my_url' => 'myaccount',
                'anchor' => '<i class="fas fa-user blue"></i> My Account',
            ),
        );
        foreach ($navigation as $nav_item) {
            echo '<li><a href="/messenger/' . $nav_item['my_url'] . '" ' . (isset($current) && $current == $nav_item['my_url'] ? ' style="color:#FFF; background-color:#2f2739 !important;"' : '') . '>' . $nav_item['anchor'] . '</a></li>';
        }
        if(en_auth()){
            //Student Logout button:
            echo '<li><a href="/logout"><i class="fas fa-power-off"></i> Logout</a></li>';
        }
        ?>
    </ul>
</div>