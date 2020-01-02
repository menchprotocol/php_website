
<?php
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
?>

<div class="container">
    <h1 class="montserrat blue"><span class="icon-block-xlg icon_photo"><?= $en_all_11035[11087]['m_icon'] ?></span><?= $en_all_11035[11087]['m_name'] ?></h1>
    <div id="load_top_players"></div>
</div>

<script>
    $(document).ready(function () {
        load_leaderboard();
    });
</script>