<?php
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION


?>

<script>
    $(document).ready(function () {
        load_leaderboard();
    });
</script>


<div class="container">
    <?php
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