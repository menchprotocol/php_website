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
    ?>
</div>