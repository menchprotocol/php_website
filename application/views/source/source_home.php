
<script src="/application/views/source/source_home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <?php

    $en_all_11035 = $this->config->item('en_all_11035'); //SOURCE
    echo '<div class="read-topic"><span class="icon-block">'.$en_all_11035[12437]['m_icon'].'</span>'.$en_all_11035[12437]['m_name'].'</div>';
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