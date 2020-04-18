<?php

$en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION

//List Plugins:
echo '<div class="container">';

echo '<h1 style="padding-top:5px;"><span class="icon-block">'.echo_en_icon($en_all_11035[6287]['m_icon']).'</span>'.$en_all_11035[6287]['m_name'].'</h1>';

echo '<div class="list-group">';
foreach($this->config->item('en_all_6287') as $en_id => $m) {
    echo echo_basic_list_link($m, '/plugin/'.$en_id);
}
echo '</div>';
echo '</div>';