<?php

foreach($this->config->item('e___33292') as $e__id1 => $m1) {
    
    echo '<h3 class="center centerh grey"><span class="icon-block">'.$m1['m__cover'].'</span>'.$m1['m__title'].'</h3>';
    echo '<div class="row justify-content list-covers">';
    foreach($this->config->item('e___'.$e__id1) as $e__id => $m) {
        echo '<div class="card_cover no-padding col-6">';
        echo '<div class="card_frame dropdown_d'.$e__id1.' dropdown_'.$e__id.'">';
        echo '<div class="large_cover">'.$m['m__cover'].'</div>';
        echo '<div class="main__title large_title zq'.$e__id.' "><b class="card_count_'.$e__id.'">'.number_format(count_interactions($e__id), 0).'</b></div>';
        echo '<div class="main__title large_title"><a href="'.( in_array($e__id, $this->config->item('n___6287')) ? '/-' : '/@' ).$e__id.'" title="'.( strlen($m['m__message']) ? $m['m__title'] : '' ).'"><u>'.( strlen($m['m__message']) ? $m['m__message'] : $m['m__title'] ).'</u></a></div>';

        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
}



