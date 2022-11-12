<?php

$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$community_list = get_domain_setting(13207);

if(intval($community_list) && is_array($this->config->item('e___'.$community_list))){

    //Community
    //echo view_coins();
    echo '<ul class="nav nav-pills nav12274"></ul>';


    $is_open = true;
    $list_count = 0;
    foreach($this->config->item('e___'.$community_list) as $x__type => $m) {

        if(!$is_open){
            continue;
        }

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

        $e_list = $this->X_model->fetch(array(
            'x__up' => $x__type,
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array('x__down'), 100, 0, array('x__id' => 'DESC'));

        if(!count($e_list)){
            continue;
        }
        $ui = '<div class="row justify-content">';
        //Children:
        foreach($e_list as $count=>$e) {
            $ui .= view_e(13207, $e, null, true);
        }

        $ui .= '</div>';

        echo view_pill($x__type, view_coins_e(11029, $x__type, 0, false), $m, $ui, $is_open);

        $is_open = false;
        $list_count++;

    }


} else {

    echo 'Community settings not yet setup for your website';

}



?>