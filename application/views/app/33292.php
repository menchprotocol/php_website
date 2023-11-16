<?php

foreach($this->config->item('e___33292') as $e__id1 => $m1) {
    
    $total_count = 0;
    $inner_stats = '';
    foreach($this->config->item('e___'.$e__id1) as $e__id => $m) {


        $this_count = count_interactions($e__id);
        $total_count += $this_count;
        $cat_id = ( $e__id==12273 ? 4737 /* Idea Type */ : ( $e__id==12274 ? 7358 /* Source Active Access */ : $e__id /* Link It-self */ ) );

        $inner_stats .= '<div class="card_cover no-padding col-6">';
        $inner_stats .= '<div class="card_frame dropdown_d'.$e__id1.' dropdown_'.$e__id.'" e__id="'.$e__id.'">';
        $inner_stats .= '<div data-toggle="tooltip" data-placement="top" title="'.( strlen($m['m__message']) ? $m['m__message'].'. ' : '' ).'Click to See Details.">';
        $inner_stats .= '<div class="large_cover">'.$m['m__cover'].'</div>';
        $inner_stats .= '<div class="main__title large_title"><b class="card_count_'.$e__id.'">'.number_format($this_count, 0).'</b></div>';
        $inner_stats .= '<div class="main__title large_title">'.$m['m__title'].'</div>';
        $inner_stats .= '</div>';

        //Sub Categories
        $total_sub_count = 0;
        $inner_stats .= '<table class="card_subcat card_subcat_'.$e__id.' hidden" style="width:100%; margin-top:13px;">';
        foreach($this->config->item('e___'.$cat_id) as $e__id2 => $m2) {

            if($e__id==12273){

                $sub_counter = $this->I_model->fetch(array(
                    'i__type' => $e__id2,
                    'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                ), 0, 0, array(), 'COUNT(i__id) as totals');


            } elseif($e__id==12274){

                $sub_counter = $this->E_model->fetch(array(
                    'e__access' => $e__id2,
                ), 0, 0, array(), 'COUNT(e__id) as totals');

            } else {

                $sub_counter = $this->X_model->fetch(array(
                    'x__type' => $e__id2,
                    'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

            }


            if($sub_counter[0]['totals'] > 0){

                $total_sub_count += $sub_counter[0]['totals'];

                $inner_stats .= '<tr class="mobile-shrink" data-toggle="tooltip" data-placement="top" title="'.$m2['m__message'].' ('.number_format(($sub_counter[0]['totals']/$this_count*100),3).'% Of Total)"><td style="text-align: right;" width="25%">'.number_format($sub_counter[0]['totals'], 0).'</td><td style="text-align: left;"><span class="icon-block-xxs">'.$m2['m__cover'].'</span>'.$m2['m__title'].'</td></tr>';

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
?>

<script>
    $(document).ready(function () {
        $(".card_frame").click(function (e) {
            $('.card_subcat_'+$(this).attr('e__id')).toggleClass('hidden');
        });
    });
</script>
