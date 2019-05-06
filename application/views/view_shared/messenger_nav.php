<div class="p-header">
    <ul class="nav nav-pills nav-pills-primary full-width">
        <?php
        $en_all_2738 = $this->config->item('en_all_2738');
        $navigation = array(
            array(
                'my_url' => 'actionplan',
                'anchor' => $en_all_2738[6138]['m_icon'].' '.$en_all_2738[6138]['m_name'],
            ),
            array(
                'my_url' => 'myaccount',
                'anchor' => $en_all_2738[6137]['m_icon'].' '.$en_all_2738[6137]['m_name'],
            ),
        );

        foreach ($navigation as $nav_item) {
            echo '<li><a href="/messenger/' . $nav_item['my_url'] . '" ' . (isset($current) && $current == $nav_item['my_url'] ? ' style="color:#FFF; background-color:#2f2739 !important;"' : '') . '>' . $nav_item['anchor'] . '</a></li>';
        }

        //Student Logout button:
        echo '<li><a href="/logout"><i class="fas fa-power-off"></i> Logout</a></li>';

        ?>
    </ul>
</div>