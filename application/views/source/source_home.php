
<script src="/application/views/source/source_home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <?php

    $en_all_11035 = $this->config->item('en_all_11035'); //NAVIGATION


    //My Sources:
    if($session_en){
        echo '<div class="list-group" style="padding-bottom:34px;">';
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //SOURCE COIN
            'ln_creator_source_id' => $session_en['en_id'],
            'en_id' => $session_en['en_id'],
        ), array('en_portfolio')) as $my_en){
            echo echo_en($my_en);
        }
        echo '</div>';
    }

    echo '<div id="load_leaderboard"></div>';

    //MENCH COINS
    $en_all_12467 = $this->config->item('en_all_12467'); //MENCH
    echo '<table id="leaderboard" class="table table-sm table-striped tablepadded" style="margin-bottom: 0;">';
    echo '<tr></tr>'; //Skip white
    echo '<tr>';
    echo '<td class="MENCHcolumn1 source montserrat" title="'.number_format($this->config->item('cache_count_source'), 0).'"><span class="icon-block">'.$en_all_12467[12274]['m_icon'].'</span>'.echo_number($this->config->item('cache_count_source')).'<span class="coin-type">'.str_replace(' ','<br />',$en_all_12467[12274]['m_name']).'</span></td>';
    echo '<td class="MENCHcolumn2 idea montserrat" title="'.number_format($this->config->item('cache_count_idea'), 0).'"><span class="icon-block">'.$en_all_12467[12273]['m_icon'].'</span>'.echo_number($this->config->item('cache_count_idea')).'<span class="coin-type">'.str_replace(' ','<br />',$en_all_12467[12273]['m_name']).'</span></td>';
    echo '<td class="MENCHcolumn3 read montserrat" title="'.number_format($this->config->item('cache_count_read'), 0).'"><span class="icon-block">'.$en_all_12467[6255]['m_icon'].'</span>'.echo_number($this->config->item('cache_count_read')).'<span class="coin-type">'.str_replace(' ','<br />',$en_all_12467[6255]['m_name']).'</span></td>';
    echo '</tr>';
    echo '</table>';

    ?>
</div>