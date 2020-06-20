<?php
$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
$sources__12467 = $this->config->item('sources__12467'); //MENCH COINS
$load_max = config_var(13206);
$show_max = config_var(11986);
?>
<div class="container">

    <?php

    foreach($this->config->item('sources__13207') as $e__id => $m) {

        echo '<div style="padding-top:34px;">';
        echo '<table class="table table-sm table-striped">';
        echo '<tr></tr>'; //Skip white
        echo '<tr>';
        echo '<td class="MENCHcolumn12 montserrat"><div class="discover-topic"><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'</div></td>';
        echo '<td class="MENCHcolumn3">&nbsp;</td>';
        echo '</tr>';
        echo '</table>';

        echo '<div class="list-group">';

        //TODO: Expand to include x__down for IDEA COINS (Currently only counts x__up)
        foreach($this->DISCOVER_model->fetch(array(

            'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
            ' EXISTS (SELECT 1 FROM mench__x WHERE e__id=x__down AND x__up IN (' . join(',', $this->config->item('sources_id_'.$e__id)) . ') AND x__type IN (' . join(',', $this->config->item('sources_id_4592')) . ') AND x__status IN ('.join(',', $this->config->item('sources_id_7359')) /* PUBLIC */.')) ' => null,

        ), array('x__up'), $load_max, 0, array('totals' => 'DESC'), 'COUNT(x__id) as totals, e__id, e__title, e__icon, e__metadata, e__status, e__weight', 'e__id, e__title, e__icon, e__metadata, e__status, e__weight') as $count=>$source) {

            if($count==$show_max){
                echo '<div class="list-group-item see_more_who'.$e__id.' no-side-padding"><a href="javascript:void(0);" onclick="$(\'.see_more_who'.$e__id.'\').toggleClass(\'hidden\')" class="block"><span class="icon-block"><i class="far fa-plus-circle source"></i></span><b class="montserrat source" style="text-decoration: none !important;">SEE MORE</b></a></div>';
                echo '<div class="list-group-item see_more_who'.$e__id.' no-height"></div>';
            }

            echo view_e($source, false, ( $count<$show_max ? '' : 'see_more_who'.$e__id.' hidden'));

        }
        echo '</div>';
        echo '</div>';
    }

    ?>
</div>