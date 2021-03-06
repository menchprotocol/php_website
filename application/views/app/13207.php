<?php

$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$load_max = view_memory(6404,13206);
$show_max_14538 = view_memory(6404,14538);
$group_by = 'e__id, e__title, e__cover, e__metadata, e__type, e__spectrum';

//SOURCE LEADERBOARD
foreach($this->config->item('e___13207') as $e__id => $m) {

    if($e__id==14874){
        echo view_coins();
        continue;
    }

    //WITH MOST IDEAS
    $e_list = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
        ' EXISTS (SELECT 1 FROM table__x WHERE e__id=x__down AND x__up='.$e__id.' AND x__type IN (' . join(',', $this->config->item('n___4592')) . ') AND x__status IN ('.join(',', $this->config->item('n___7359')) /* PUBLIC */.')) ' => null,
    ), array('x__up'), $load_max, 0, array('totals' => 'DESC'), 'COUNT(x__id) as totals, '.$group_by, $group_by);

    if(!count($e_list)){
        continue;
    }

    echo '<div class="headline top-margin"><span class="icon-block">'.$m['m__cover'].'</span>'.$m['m__title'].'</div>';
    echo '<div class="row" style="padding-bottom:41px;">';


    foreach($e_list as $count=>$e) {
        if($count==$show_max_14538){
            echo view_show_more('.see_more_who'.$e__id, (count($e_list)-$show_max_14538));
        }
        echo view_e(13207, $e, ( $count<$show_max_14538 ? '' : ' see_more_who'.$e__id.' hidden '), true);
    }

    echo '</div>';
}

?>