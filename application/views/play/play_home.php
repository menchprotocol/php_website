<?php
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
?>

<script>
    $(document).ready(function () {
        load_leaderboard();
    });
</script>

<div class="container">

    <?php
    //Total Stats
    $en_all_2738 = $this->config->item('en_all_2738'); //MENCH
    $en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION


    //MENCH COINS
    $read_coins = $this->READ_model->ln_fetch(array(
        'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
    ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
    $idea_coins = $this->READ_model->ln_fetch(array(
        'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'ln_type_play_id' => 4250, //UNIQUE IDEAS
    ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
    $play_coins = $this->READ_model->ln_fetch(array(
        'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //PLAY COIN
    ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');



    echo '<h2 class="montserrat '.extract_icon_color($en_all_11035[12358]['m_icon']).'"><span class="icon-block">'.$en_all_11035[12358]['m_icon'].'</span>'.$en_all_11035[12358]['m_name'].'</h2>';


    echo '<table class="table table-sm table-striped dotransparent tablepadded" style="margin-bottom:50px;">';


    echo '<tr></tr>';

    echo '<tr>';


    echo '<tr>';
    echo '<td class="play fixedColumns MENCHcolumn1"><span class="play"><span class="icon-block">' . $en_all_2738[4536]['m_icon'] . '</span><span class="montserrat" title="'.number_format($play_coins[0]['total_coins'], 0).'">'.echo_number($play_coins[0]['total_coins']).'</span><b class="block"><span class="icon-block show-max">&nbsp;</span>PLAYERS</b></span></td>';
    echo '<td class="read fixedColumns MENCHcolumn2"><span class="read"><span class="icon-block">' . $en_all_2738[6205]['m_icon'] . '</span><span class="montserrat" title="'.number_format($read_coins[0]['total_coins'], 0).'">'.echo_number($read_coins[0]['total_coins']).'</span><b class="block"><span class="icon-block show-max">&nbsp;</span>READ</b></span></td>';
    echo '<td class="idea fixedColumns MENCHcolumn3"><span class="idea"><span class="icon-block">' . $en_all_2738[4535]['m_icon'] . '</span><span class="montserrat" title="'.number_format($idea_coins[0]['total_coins'], 0).'">'.echo_number($idea_coins[0]['total_coins']).'</span><b class="block"><span class="icon-block show-max">&nbsp;</span>IDEAS</b></span></td>';
    echo '</tr>';

    echo '</table>';

    ?>

    <!-- Top Players -->
    <h2 class="montserrat play"><span class="icon-block"><?= $en_all_11035[12437]['m_icon'] ?></span><?= $en_all_11035[12437]['m_name'] ?></h2>

    <div id="load_leaderboard"></div>

</div>

<?php

//Link to Account or Login:
if(!$session_en){

    echo '<div style="padding:10px 0 20px;"><a href="/sign?url=/play" class="btn btn-play montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to start playing.</div>';

}

?>