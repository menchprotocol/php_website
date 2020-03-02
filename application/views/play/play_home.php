<?php
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
?>

<script>
    $(document).ready(function () {
        load_leaderboard();
    });
</script>

<div class="container">
<!-- Top Players -->
<h2 class="montserrat play"><span class="icon-block"><?= $en_all_11035[12437]['m_icon'] ?></span><?= $en_all_11035[12437]['m_name'] ?></h2>
    <div class="one-pix">
        <div id="load_leaderboard"></div>
    </div>
</div>

<?php


//Total Stats
echo echo_mench_stats();


//Link to Account or Login:
if(!$session_en){

    echo '<div style="padding:10px 0 20px;"><a href="/sign?url=/play" class="btn btn-play montserrat">'.$en_all_11035[4269]['m_name'].'<span class="icon-block">'.$en_all_11035[4269]['m_icon'].'</span></a> to start playing.</div>';

}

?>