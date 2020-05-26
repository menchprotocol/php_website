<?php
$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
$sources__12467 = $this->config->item('sources__12467'); //MENCH COINS
$load_max = config_var(13206);
$show_max = config_var(11986);
?>
<div class="container">

    <?php


    //My Sources:
    if($session_source){

        echo '<div class="read-topic" style="margin-top:21px;"><span class="icon-block">'.$sources__11035[12205]['m_icon'].'</span>'.$sources__11035[12205]['m_name'].'</div>';

        echo '<div class="list-group">';
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



    foreach($this->config->item('sources__13207') as $source__id => $m) {

        echo '<table class="table table-sm table-striped" style="margin-top:34px;">';
        echo '<tr></tr>'; //Skip white
        echo '<tr>';
        echo '<td class="MENCHcolumn1 montserrat"><div class="read-topic"><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'</div></td>';
        echo '<td class="MENCHcolumn2 idea montserrat"><span style="padding-left: 9px;">'.$sources__12467[12273]['m_name'].'</span></td>';
        echo '<td class="MENCHcolumn3 read montserrat"><span style="padding-left: 9px;">'.$sources__12467[6255]['m_name'].'</span></td>';
        echo '</tr>';
        echo '</table>';

        echo '<div class="list-group">';
        foreach($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
            ' EXISTS (SELECT 1 FROM mench_interactions WHERE source__id=read__down AND read__up IN (' . join(',', $this->config->item('sources_id_'.$source__id)) . ') AND read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ') AND read__status IN ('.join(',', $this->config->item('sources_id_7359')) /* PUBLIC */.')) ' => null,
        ), array('read__up'), $load_max, 0, array('totals' => 'DESC'), 'COUNT(read__id) as totals, source__id, source__title, source__icon, source__metadata, source__status, source__weight', 'source__id, source__title, source__icon, source__metadata, source__status, source__weight') as $count=>$source) {

            if($count==$show_max){
                echo '<div class="list-group-item see_more_who'.$source__id.' no-side-padding"><a href="javascript:void(0);" onclick="$(\'.see_more_who'.$source__id.'\').toggleClass(\'hidden\')" class="block"><span class="icon-block"><i class="far fa-plus-circle source"></i></span><b class="montserrat source" style="text-decoration: none !important;">SEE MORE</b></a></div>';
                echo '<div class="list-group-item see_more_who'.$source__id.' no-height"></div>';
            }
            echo view_source($source, false, ( $count<$show_max ? '' : 'see_more_who'.$source__id.' hidden'));

        }
        echo '</div>';
    }

    ?>
</div>