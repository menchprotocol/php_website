<?php
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION

//COUNT COINS
$read_coins = $this->READ_model->ln_fetch(array(
    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
$blog_coins = $this->READ_model->ln_fetch(array(
    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_play_id' => 4250, //UNIQUE BLOGS
), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
$play_coins = $this->READ_model->ln_fetch(array(
    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //PLAY COIN
), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');

?>

<script>
    $(document).ready(function () {
        load_leaderboard();
    });
</script>

<div class="container">

    <?php


    //MENCH COINS
    echo '<div class="read-topic"><span class="icon-block">'.$en_all_11035[12467]['m_icon'].'</span>'.$en_all_11035[12467]['m_name'].'</div>';
    echo '<table class="table table-sm table-striped dotransparent tablepadded">';

    echo '<tr>';
    echo '<td class="play fixedColumns MENCHcolumn1"><span class="play"><span class="icon-block">' . $en_all_2738[4536]['m_icon'] . '</span><span class="montserrat" title="'.number_format($play_coins[0]['total_coins'], 0).'">'.echo_number($play_coins[0]['total_coins']).'</span><b class="block montserrat" style="padding-left: 8px;">'.$en_all_2738[4536]['m_name'].'</b></span></td>';
    echo '<td class="blog fixedColumns MENCHcolumn3"><span class="blog"><span class="icon-block">' . $en_all_2738[4535]['m_icon'] . '</span><span class="montserrat" title="'.number_format($blog_coins[0]['total_coins'], 0).'">'.echo_number($blog_coins[0]['total_coins']).'</span><b class="block montserrat" style="padding-left: 8px;">'.$en_all_2738[4535]['m_name'].'</b></span></td>';
    echo '<td class="read fixedColumns MENCHcolumn2"><span class="read"><span class="icon-block">' . $en_all_2738[6205]['m_icon'] . '</span><span class="montserrat" title="'.number_format($read_coins[0]['total_coins'], 0).'">'.echo_number($read_coins[0]['total_coins']).'</span><b class="block montserrat" style="padding-left: 8px;">'.$en_all_2738[6205]['m_name'].'</b></span></td>';

    echo '</tr>';
    echo '</table>';



    //Top Players
    echo '<div class="read-topic sequal-topic"><span class="icon-block">'.$en_all_11035[12437]['m_icon'].'</span>'.$en_all_11035[12437]['m_name'].'</div>';
    echo '<div id="load_leaderboard"></div>';

    ?>

</div>

<?php

//Link to Account or Login:
if(!$session_en){

    echo '<div style="padding:10px 0 20px;"><a href="/sign?url=/play" class="btn btn-play montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to start playing.</div>';

}

?>