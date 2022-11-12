<?php

$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$community_list = get_domain_setting(13207);

if(intval($community_list) && is_array($this->config->item('e___'.$community_list))){

    //Community
    //echo view_coins();
    echo '<ul class="nav nav-pills nav12274"></ul>';


    $is_open = true;
    foreach($this->config->item('e___'.$community_list) as $x__type => $m) {

        //WITH MOST IDEAS
        /*
        $group_by = 'e__id, e__title, e__cover, e__metadata, e__type, e__spectrum';
        $e_list = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__up >' => 0,
            ' EXISTS (SELECT 1 FROM table__x WHERE e__id=x__down AND x__up='.$x__type.' AND x__type IN (' . join(',', $this->config->item('n___4592')) . ') AND x__status IN ('.join(',', $this->config->item('n___7359')).')) ' => null,
        ), array('x__up'), 0, 0, array('totals' => 'DESC'), 'COUNT(x__id) as totals, '.$group_by, $group_by);
        */

        $total_count = view_coins_e(11029, $x__type, 0, false);
        if($total_count){

            $ui = '<div class="row justify-content">';
            foreach(view_coins_e(11029, $x__type, 1, false) as $count=>$e) {
                $ui .= view_e(13207, $e, null, true);
            }
            $ui .= '</div>';

            echo view_pill($x__type, $total_count, $m, $ui, $is_open);

            $is_open = false;
        }

    }


} else {

    echo 'Community settings not yet setup for your website';

}



?>