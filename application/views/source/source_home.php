<?php

$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
$sources__12467 = $this->config->item('sources__12467'); //MENCH COINS
$load_max = config_var(11064);
$show_max = config_var(11986);
$start_date = null; //All-Time
$top_players = array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
    'read__up >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    player_filter() => null,
);
$top_content = array(
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
    'read__up >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    content_filter() => null,
);
/*
if(1){ //Weekly

    //Week always starts on Monday:
    if(date('D') === 'Mon'){
        //Today is Monday:
        $start_date = date("Y-m-d");
    } else {
        $start_date = date("Y-m-d", strtotime('previous monday'));
    }
    $top_players['read__time >='] = $start_date.' 00:00:00'; //From beginning of the day
}
*/
?>
<div class="container">

    <?php


    //My Sources:
    if($session_source){

        echo '<div class="read-topic"><span class="icon-block">'.$sources__11035[12205]['m_icon'].'</span>'.$sources__11035[12205]['m_name'].'</div>';

        echo '<div class="list-group" style="margin-bottom:34px;">';
        foreach($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_12274')) . ')' => null, //SOURCE COIN
            'read__source' => $session_source['source__id'],
            'source__id' => $session_source['source__id'],
        ), array('read__down')) as $my_source){
            echo view_source($my_source);
        }
        echo '</div>';
    }


    //Top Players
    echo '<table id="leaderboard" class="table table-sm table-striped" style="margin-bottom: 0;">';
    echo '<tr></tr>'; //Skip white
    echo '<tr>';
    echo '<td class="MENCHcolumn1 montserrat"><div class="read-topic"><span class="icon-block">'.$sources__11035[13202]['m_icon'].'</span>'.$sources__11035[13202]['m_name'].'</div></td>';
    echo '<td class="MENCHcolumn2 idea montserrat"><span style="padding-left: 9px;">'.$sources__12467[12273]['m_name'].'</span></td>';
    echo '<td class="MENCHcolumn3 read montserrat"><span style="padding-left: 9px;">'.$sources__12467[6255]['m_name'].'</span></td>';
    echo '</tr>';
    echo '</table>';

    echo '<div class="list-group" style="margin-bottom:34px;">';
    foreach($this->READ_model->fetch($top_players, array('read__up'), $load_max, 0, array('totals' => 'DESC'), 'COUNT(read__id) as totals, source__id, source__title, source__icon, source__metadata, source__status, source__weight', 'source__id, source__title, source__icon, source__metadata, source__status, source__weight') as $count=>$source) {

        if($count==$show_max){

            echo '<div class="list-group-item see_more_who no-side-padding"><a href="javascript:void(0);" onclick="$(\'.see_more_who\').toggleClass(\'hidden\')" class="block"><span class="icon-block"><i class="far fa-plus-circle source"></i></span><b class="montserrat source" style="text-decoration: none !important;">SEE MORE</b></a></div>';

            echo '<div class="list-group-item see_more_who no-height"></div>';

        }

        echo view_source($source, false, ( $count<$show_max ? '' : 'see_more_who hidden'));

    }
    echo '</div>';






    //Top Content
    echo '<table id="leaderboard" class="table table-sm table-striped" style="margin-bottom: 0;">';
    echo '<tr></tr>'; //Skip white
    echo '<tr>';
    echo '<td class="MENCHcolumn1 montserrat"><div class="read-topic"><span class="icon-block">'.$sources__11035[13203]['m_icon'].'</span>'.$sources__11035[13203]['m_name'].'</div></td>';
    echo '<td class="MENCHcolumn2 idea montserrat"><span style="padding-left: 9px;">'.$sources__12467[12273]['m_name'].'</span></td>';
    echo '<td class="MENCHcolumn3 read montserrat"><span style="padding-left: 9px;">'.$sources__12467[6255]['m_name'].'</span></td>';
    echo '</tr>';
    echo '</table>';

    echo '<div class="list-group" style="margin-bottom:34px;">';
    foreach($this->READ_model->fetch($top_content, array('read__up'), $load_max, 0, array('totals' => 'DESC'), 'COUNT(read__id) as totals, source__id, source__title, source__icon, source__metadata, source__status, source__weight', 'source__id, source__title, source__icon, source__metadata, source__status, source__weight') as $count=>$source) {

        if($count==$show_max){

            echo '<div class="list-group-item see_more_who no-side-padding"><a href="javascript:void(0);" onclick="$(\'.see_more_who\').toggleClass(\'hidden\')" class="block"><span class="icon-block"><i class="far fa-plus-circle source"></i></span><b class="montserrat source" style="text-decoration: none !important;">SEE MORE</b></a></div>';

            echo '<div class="list-group-item see_more_who no-height"></div>';

        }

        echo view_source($source, false, ( $count<$show_max ? '' : 'see_more_who hidden'));

    }
    echo '</div>';




    ?>
</div>