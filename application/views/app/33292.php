<?php

foreach($this->config->item('e___33292') as $e__id1 => $m1) {
    if($e__id1==14874) {

        echo '<h2>'.$m1['m__title'].'</h2>';
        echo '<div class="row justify-content list-covers">';
        $count = 0;
        foreach($this->config->item('e___14874') as $e__id => $m) {
            $count++;
            echo '<div class="card_cover no-padding col-12 col-md-4">';
            echo '<div class="card_frame dropdown_'.$e__id.'">';
            echo '<div class="large_cover">'.$m['m__cover'].'</div>';
            echo '<div class="css__title large_title zq'.$e__id.' "><b class="card_count_'.$e__id.'">'.number_format(count_unique_covers($e__id), 0).'</b></div>';
            echo '<div class="css__title large_title zq'.$e__id.'">'.$m['m__title'].'</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';

    } elseif($e__id1==4341) {

        //Mench Ledger
        $query = $this->X_model->fetch(array(), array(), 1, 0, array(), 'COUNT(x__id) as totals');
        echo '<div class="row justify-content list-covers" style="font-size: 1.8em; padding-top: 55px; line-height: 130%;"><div style="min-height:40px; width: 100%; text-align: center;"><b class="card_count_x css__title">'.number_format($query[0]['totals'], 0).'</b></div><div class="css__title" style="text-align: center;"><a href="/-'.$e__id1.'" title="'.( strlen($m1['m__message']) ? $m1['m__title'] : '' ).'">'.( strlen($m1['m__message']) ? $m1['m__message'] : $m1['m__title'] ).'</a></div></div>';

    } elseif($e__id1==6287) {

        //Mench Ledger
        echo '<div class="row justify-content list-covers" style="font-size: 1.8em; padding-top: 55px; line-height: 130%;"><div style="min-height:40px; width: 100%; text-align: center;"><b class="card_count_x css__title">'.number_format(count($this->config->item('n___6287')), 0).'</b></div><div class="css__title" style="text-align: center;"><a href="/-'.$e__id1.'" title="'.( strlen($m1['m__message']) ? $m1['m__title'] : '' ).'">'.( strlen($m1['m__message']) ? $m1['m__message'] : $m1['m__title'] ).'</a></div></div>';

    }
}



