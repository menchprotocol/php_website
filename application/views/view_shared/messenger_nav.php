<div class="p-header">
    <ul class="nav nav-pills nav-pills-primary full-width">
        <?php
        $en_all_7292 = $this->config->item('en_all_7292');
        $navigation = array(
            array(
                'my_url' => '/actionplan',
                'anchor' => $en_all_7292[6138]['m_icon'].' '.$en_all_7292[6138]['m_name'],
            ),
            array(
                'my_url' => '/myaccount',
                'anchor' => $en_all_7292[6137]['m_icon'].' '.$en_all_7292[6137]['m_name'],
            )
        );


        //If miner give access back to platform:
        if(en_auth(array(1308))){
            $en_all_4488 = $this->config->item('en_all_4488');
            array_push($navigation, array(
                'my_url' => '/platform',
                'anchor' => '<span class="micro-image">'.$en_all_4488[7161]['m_icon'].'</span> '.$en_all_4488[7161]['m_name'].' &nbsp;<i class="fas fa-long-arrow-right"></i>',
            ));
        }

        //Add logout:
        array_push($navigation, array(
            'my_url' => '/logout',
            'anchor' => $en_all_7292[7291]['m_icon'].' '.$en_all_7292[7291]['m_name'],
        ));

        //Display all:
        foreach ($navigation as $nav_item) {
            echo '<li><a href="' . $nav_item['my_url'] . '" ' . (isset($current) && $current == $nav_item['my_url'] ? ' style="color:#FFF; background-color:#2f2739 !important;"' : '') . '>' . $nav_item['anchor'] . '</a></li>';
        }

        ?>
    </ul>
</div>