<?php

foreach($this->config->item('e___33292') as $e__id1 => $m1) {
    
    $total_count = 0;
    $inner_stats = '';
    foreach($this->config->item('e___'.$e__id1) as $e__id => $m) {

        $this_count = count_interactions($e__id);
        $total_count += $this_count;


        $inner_stats .= '<div class="card_cover no-padding col-6">';
        $inner_stats .= '<div class="card_frame dropdown_d'.$e__id1.' dropdown_'.$e__id.'">';
        $inner_stats .= '<div class="large_cover">'.$m['m__cover'].'</div>';
        $inner_stats .= '<div class="main__title large_title"><b class="card_count_'.$e__id.'">'.number_format($this_count, 0).'</b></div>';
        $inner_stats .= '<div class="main__title large_title"><a href="'.( in_array($e__id, $this->config->item('n___6287')) ? '/-' : '/@' ).$e__id.'" title="'.( strlen($m['m__message']) ? $m['m__title'] : '' ).'">'.( strlen($m['m__message']) ? $m['m__message'] : $m['m__title'] ).'</a> <a href="javascript:void(0);" onclick="$(\'.card_subcat_'.$e__id.'\').toggleClass(\'hidden\');"><i class="fas fa-sort"></i></a></div>';

        //Sub Categories
        $cat_id = ( $e__id==12273 ? 4737 /* Idea Type */ : ( $e__id==12274 ? 7358 /* Source Active Access */ : $e__id /* Link It-self */ ) );
        $inner_stats .= '<table class="card_subcat card_subcat_'.$e__id.' hidden">';
        foreach($this->config->item('e___'.$cat_id) as $e__id2 => $m2) {

            if($e__id==12273){

                $sub_counter = $this->I_model->fetch(array(
                    'i__type' => $cat_id,
                    'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                ), 0, 0, array(), 'COUNT(i__id) as totals');


            } elseif($e__id==12274){

                $sub_counter = $this->E_model->fetch(array(
                    'e__access' => $e__id2,
                ), 0, 0, array(), 'COUNT(e__id) as totals');

            } else {

                $list_e_count = $this->X_model->fetch(array(
                    'x__type' => $e__id2,
                    'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

            }

            if($list_e_count[0]['totals'] > 0){
                $inner_stats .= '<tr><td style="text-align: right;" width="34%">'.number_format($list_e_count[0]['totals'], 0).'</td><td style="text-align: left;"><span class="icon-block">'.$m2['m__cover'].'</span>'.$m2['m__title'].'</td></tr>';
            }
        }
        $inner_stats .= '</table>';


        $inner_stats .= '</div>';
        $inner_stats .= '</div>';

    }

    if($inner_stats){
        echo '<h3 class="center centerh grey"><span class="icon-block">'.$m1['m__cover'].'</span>'.number_format($total_count, 0).' '.$m1['m__title'].':</h3>';
        echo '<div class="row justify-content list-covers">';
        echo $inner_stats;
        echo '</div>';
    }


}



