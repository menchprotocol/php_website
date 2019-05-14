<div class="p-header">
    <ul class="nav nav-pills nav-pills-primary full-width">
        <?php
        $en_all_2738 = $this->config->item('en_all_2738');
        $navigation = array(
            array(
                'my_url' => '/messenger/actionplan',
                'anchor' => $en_all_2738[6138]['m_icon'].' '.$en_all_2738[6138]['m_name'],
            ),
            array(
                'my_url' => '/messenger/myaccount',
                'anchor' => $en_all_2738[6137]['m_icon'].' '.$en_all_2738[6137]['m_name'],
            ),
        );

        //If miner give access back to platform:
        if(en_auth(array(1308))){
            array_push($navigation, array(
                'my_url' => '/platform',
                'anchor' => '<span class="micro-image">'.$en_all_2738[4488]['m_icon'].'</span> '.$en_all_2738[4488]['m_name'].' &nbsp;<i class="fas fa-long-arrow-right"></i>',
            ));
        }

        foreach ($navigation as $nav_item) {
            echo '<li><a href="' . $nav_item['my_url'] . '" ' . (isset($current) && $current == $nav_item['my_url'] ? ' style="color:#FFF; background-color:#2f2739 !important;"' : '') . '>' . $nav_item['anchor'] . '</a></li>';
        }

        //User Logout button:
        echo '<li><a href="/logout"><i class="fas fa-power-off"></i> Logout</a></li>';

        ?>
    </ul>
</div>