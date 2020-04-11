<?php
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION
?>

<script>
    $(document).ready(function () {
        load_leaderboard();
    });
</script>

<div class="container">
    <?php
    //Top Players
    echo '<div class="read-topic"><span class="icon-block">'.$en_all_11035[12437]['m_icon'].'</span>'.$en_all_11035[12437]['m_name'].'</div>';
    echo '<div id="load_leaderboard"></div>';

    //Total Stats:
    echo '<div style="padding:21px 0;"><span class="icon-block"><i class="fad fa-info-circle grey"></i></span><div class="title-block">As of <span data-toggle="tooltip" data-placement="top" title="'.date("Y-m-d H:i:s", $this->config->item('cache_timestamp')).' PST" class="montserrat">'.date("F jS", $this->config->item('cache_timestamp')).'</span>, <b class="montserrat">MENCH</b> has indexed <span class="montserrat source inline-block"><span data-toggle="tooltip" data-placement="top" title="'.number_format($this->config->item('cache_source'), 0).' SOURCES (People & Content)">'.echo_number($this->config->item('cache_source')).'</span> <i class="fas fa-circle source"></i> SOURCES</span>, <span class="montserrat note inline-block"><span data-toggle="tooltip" data-placement="top" title="'.number_format($this->config->item('cache_note'), 0).' NOTES">'.echo_number($this->config->item('cache_note')).'</span> <i class="fas fa-circle note"></i> NOTES</span> & <span class="montserrat read inline-block"><span data-toggle="tooltip" data-placement="top" title="'.number_format($this->config->item('cache_read'), 0).'">'.echo_number($this->config->item('cache_read')).'</span> <i class="fas fa-circle read"></i> READS</span></div></div>';

    ?>
</div>