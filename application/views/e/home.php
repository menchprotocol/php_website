<div class="container">

    <?php

    $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
    echo '<h1 class="big-frame '.extract_icon_color($e___11035[13207]['m_icon']).'">' . $e___11035[13207]['m_title'] . '</h1>';
    echo view_mench_coins();


    $load_max = config_var(13206);
    $show_max = config_var(11986);
    $select = 'COUNT(x__id) as totals, e__id, e__title, e__icon, e__metadata, e__status, e__weight';
    $group_by =                       'e__id, e__title, e__icon, e__metadata, e__status, e__weight';

    //SOURCE LEADERBOARD
    foreach($this->config->item('e___13207') as $e__id => $m) {

        //WITH MOST IDEAS
        $e_list = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            ' EXISTS (SELECT 1 FROM mench__x WHERE e__id=x__down AND x__up IN (' . ( in_array($e__id, $this->config->item('n___4527')) ? join(',', $this->config->item('n___'.$e__id)) : $e__id ) . ') AND x__type IN (' . join(',', $this->config->item('n___4592')) . ') AND x__status IN ('.join(',', $this->config->item('n___7359')) /* PUBLIC */.')) ' => null,
        ), array('x__up'), $load_max, 0, array('totals' => 'DESC'), $select, $group_by);
        //TODO: Expand to include x__down for IDEA COINS (Currently only counts x__up)

        if(!count($e_list)){
            continue;
        }

        echo '<div class="headline"><span class="icon-block">&nbsp;</span>'.$m['m_title'].'</div>';
        echo '<div class="list-group" style="padding-bottom:33px;">';


        foreach($e_list as $count=>$e) {

            if($count==$show_max){
                echo '<div class="list-group-item see_more_who'.$e__id.' no-side-padding"><a href="javascript:void(0);" onclick="$(\'.see_more_who'.$e__id.'\').toggleClass(\'hidden\')" class="block"><span class="icon-block"><i class="far fa-plus-circle source"></i></span><b class="montserrat source" style="text-decoration: none !important;">SEE MORE</b></a></div>';
                echo '<div class="list-group-item see_more_who'.$e__id.' no-height"></div>';
            }

            echo view_e($e, false, ( $count<$show_max ? '' : 'see_more_who'.$e__id.' hidden'), false, true);

        }

        echo '</div>';
    }

    ?>
</div>