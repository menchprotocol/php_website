
<script src="/application/views/source/source_home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <?php

    $sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION

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


    //Top Sources
    $sources__12467 = $this->config->item('sources__12467'); //MENCH COINS
    echo '<table id="leaderboard" class="table table-sm table-striped" style="margin-bottom: 0;">';
    echo '<tr></tr>'; //Skip white
    echo '<tr>';
    echo '<td class="MENCHcolumn1 montserrat"><div class="read-topic"><span class="icon-block">'.$sources__11035[13202]['m_icon'].'</span>'.$sources__11035[13202]['m_name'].'</div></td>';
    echo '<td class="MENCHcolumn2 idea montserrat"><span class="coin-type">'.$sources__12467[12273]['m_name'].'</span></td>';
    echo '<td class="MENCHcolumn3 read montserrat"><span class="coin-type">'.$sources__12467[6255]['m_name'].'</span></td>';
    echo '</tr>';
    echo '</table>';

    echo '<div id="load_leaderboard"></div>';


    //MENCH COINS
    /*
    $sources__12467 = $this->config->item('sources__12467'); //MENCH COINS
    echo '<table id="leaderboard" class="table table-sm table-striped tablepadded" style="margin-bottom: 0;">';
    echo '<tr></tr>'; //Skip white
    echo '<tr>';
    echo '<td class="MENCHcolumn1 source montserrat" title="'.number_format($this->config->item('cache_count_source'), 0).'"><span class="icon-block">'.$sources__12467[12274]['m_icon'].'</span>'.view_number($this->config->item('cache_count_source')).'<span class="coin-type">'.str_replace(' ','<br />',$sources__12467[12274]['m_name']).'</span></td>';
    echo '<td class="MENCHcolumn2 idea montserrat" title="'.number_format($this->config->item('cache_count_idea'), 0).'"><span class="icon-block">'.$sources__12467[12273]['m_icon'].'</span>'.view_number($this->config->item('cache_count_idea')).'<span class="coin-type">'.str_replace(' ','<br />',$sources__12467[12273]['m_name']).'</span></td>';
    echo '<td class="MENCHcolumn3 read montserrat" title="'.number_format($this->config->item('cache_count_read'), 0).'"><span class="icon-block">'.$sources__12467[6255]['m_icon'].'</span>'.view_number($this->config->item('cache_count_read')).'<span class="coin-type">'.str_replace(' ','<br />',$sources__12467[6255]['m_name']).'</span></td>';
    echo '</tr>';
    echo '</table>';
    */

    ?>
</div>