<?php

$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
$load_max = view_memory(6404,13206);
$show_max = view_memory(6404,14538);
$select = 'COUNT(x__id) as totals, e__id, e__title, e__icon, e__metadata, e__type, e__spectrum';
$group_by =                       'e__id, e__title, e__icon, e__metadata, e__type, e__spectrum';

//SOURCE LEADERBOARD
foreach($this->config->item('e___13207') as $e__id => $m) {

    if($e__id==2738){
        echo view_mench_coins();
        continue;
    }

    //WITH MOST IDEAS
    $e_list = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
        ' EXISTS (SELECT 1 FROM mench__x WHERE e__id=x__down AND x__up='.$e__id.' AND x__type IN (' . join(',', $this->config->item('n___4592')) . ') AND x__status IN ('.join(',', $this->config->item('n___7359')) /* PUBLIC */.')) ' => null,
    ), array('x__up' /* TODO: Expand to include x__down */), $load_max, 0, array('totals' => 'DESC'), $select, $group_by);

    if(!count($e_list)){
        continue;
    }

    echo '<div class="headline top-margin"><span class="icon-block">'.$m['m__icon'].'</span>'.$m['m__title'].'</div>';
    echo '<div class="list-group" style="padding-bottom:41px;">';


    foreach($e_list as $count=>$e) {

        if($count==$show_max){
            echo '<div class="list-group-item see_more_who'.$e__id.' no-side-padding"><a href="javascript:void(0);" onclick="$(\'.see_more_who'.$e__id.'\').toggleClass(\'hidden\')" class="block"><span class="icon-block">'.$e___11035[14538]['m__icon'].'</span><b class="css__title '.extract_icon_color($e___11035[14538]['m__icon']).'" style="text-decoration: none !important;">'.$e___11035[14538]['m__title'].'</b></a></div>';
            echo '<div class="list-group-item see_more_who'.$e__id.' no-height"></div>';
        }

        echo view_e($e, false, ( $count<$show_max ? '' : ' see_more_who'.$e__id.' hidden '), false, true);

    }

    echo '</div>';
}

?>