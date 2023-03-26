<?php

foreach($this->config->item('e___33292') as $e__id1 => $m1) {

    echo '<h3 class="center centerh grey">'.$m1['m__title'].':</h3>';

    if($e__id1==14874) {

        echo '<div class="row justify-content list-covers">';
        foreach($this->config->item('e___14874') as $e__id => $m) {
            echo '<div class="card_cover no-padding col-4">';
            echo '<div class="card_frame dropdown_'.$e__id.'">';
            echo '<div class="large_cover">'.$m['m__cover'].'</div>';
            echo '<div class="css__title large_title zq'.$e__id.' "><b class="card_count_'.$e__id.'">'.number_format(count_unique_covers($e__id), 0).'</b></div>';
            echo '<div class="css__title large_title zq'.$e__id.'">'.$m['m__title'].'</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';

    } elseif($e__id1==33293) {

        echo '<div class="row justify-content list-covers">';
        foreach($this->config->item('e___33293') as $e__id => $m) {
            echo '<div class="card_cover no-padding col-6">';
            echo '<div class="card_frame dropdown_'.$e__id.'">';
            echo '<div class="large_cover">'.$m['m__cover'].'</div>';
            echo '<div class="css__title large_title zq'.$e__id.' "><b class="card_count_'.$e__id.'">'.number_format(count_unique_covers($e__id), 0).'</b></div>';
            echo '<div class="css__title large_title zq'.$e__id.'"><a href="/-'.$e__id.'" title="'.( strlen($m['m__message']) ? $m['m__title'] : '' ).'"><u>'.( strlen($m['m__message']) ? $m['m__message'] : $m['m__title'] ).'</u></a></div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';

    }
}



