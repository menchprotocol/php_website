<?php

$displayed = array();

foreach($this->config->item('e___33292') as $e__id1 => $m1) {

    echo '<h3 class="center centerh grey">'.$m1['m__title'].'</h3>';

    if($e__id1==14874) {

        //Mench Cards
        echo '<div class="row justify-content list-covers">';
        foreach($this->config->item('e___14874') as $e__id => $m) {
            if(in_array($e__id, $displayed)){
                continue;
            }
            array_push($displayed, $e__id);
            echo '<div class="card_cover no-padding col-4">';
            echo '<div class="card_frame dropdown_d14874 dropdown_'.$e__id.'">';
            echo '<div class="large_cover">'.$m['m__cover'].'</div>';
            echo '<div class="main__title large_title zq'.$e__id.' "><b class="card_count_'.$e__id.'">'.number_format(count_unique_covers($e__id), 0).'</b></div>';
            echo '<div class="main__title large_title"><a href="'.( in_array($e__id, $this->config->item('n___6287')) ? '/-' : '/@' ).$e__id.'" title="'.( strlen($m['m__message']) ? $m['m__title'] : '' ).'" class="zq'.$e__id.'"><u>'.( strlen($m['m__message']) ? $m['m__message'] : $m['m__title'] ).'</u></a></div>';

            echo '</div>';
            echo '</div>';
        }
        echo '</div>';

    } elseif($e__id1==31770) {

        //Mench Links
        echo '<div class="row justify-content list-covers">';
        foreach($this->config->item('e___31770') as $e__id => $m) {
            if(in_array($e__id, $displayed)){
                //continue;
            }
            array_push($displayed, $e__id);

            //Count Links:
            $list_e_count = $this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___'.$e__id)) . ')' => null, //All these link types
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

            echo '<div class="card_cover no-padding col-4">';
            echo '<div class="card_frame dropdown_d33293 dropdown_'.$e__id.'">';
            echo '<div class="large_cover">'.$m['m__cover'].'</div>';
            echo '<div class="main__title large_title zq'.$e__id.' "><b class="card_count_'.$e__id.'">'.number_format($list_e_count[0]['totals'], 0).'</b></div>';
            echo '<div class="main__title large_title"><a href="'.( in_array($e__id, $this->config->item('n___6287')) ? '/-' : '/@' ).$e__id.'" title="'.( strlen($m['m__message']) ? $m['m__title'] : '' ).'" class="zq'.$e__id.'"><u>'.( strlen($m['m__message']) ? $m['m__message'] : $m['m__title'] ).'</u></a></div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';

    } elseif($e__id1==33293) {

        //Mench Core
        echo '<div class="row justify-content list-covers">';
        foreach($this->config->item('e___33293') as $e__id => $m) {
            if(in_array($e__id, $displayed)){
                continue;
            }
            array_push($displayed, $e__id);
            echo '<div class="card_cover no-padding col-4">';
            echo '<div class="card_frame dropdown_d33293 dropdown_'.$e__id.'">';
            echo '<div class="large_cover">'.$m['m__cover'].'</div>';
            echo '<div class="main__title large_title zq'.$e__id.' "><b class="card_count_'.$e__id.'">'.number_format(count_unique_covers($e__id), 0).'</b></div>';
            echo '<div class="main__title large_title"><a href="'.( in_array($e__id, $this->config->item('n___6287')) ? '/-' : '/@' ).$e__id.'" title="'.( strlen($m['m__message']) ? $m['m__title'] : '' ).'" class="zq'.$e__id.'"><u>'.( strlen($m['m__message']) ? $m['m__message'] : $m['m__title'] ).'</u></a></div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';

    }
}



