<?php
$session_en = en_auth();
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
$en_all_10591 = $this->config->item('en_all_10591'); //PLAYER PLAYS
?>

<div class="container">

    <div class="row">

        <div class="col-lg-6">

            <h1>HOW TO PLAY</h1>
            <ul class="none-list">
                <li><b class="montserrat play"><?= $en_all_2738[4536]['m_icon'] .' '. $en_all_2738[4536]['m_name'] ?></b> coin earned for creating your account</li>
                <li><b class="montserrat blog"><?= $en_all_2738[4535]['m_icon'] .' '. $en_all_2738[4535]['m_name'] ?></b> coin earned for each word you blog</li>
                <li><b class="montserrat read"><?= $en_all_2738[6205]['m_icon'] .' '. $en_all_2738[6205]['m_name'] ?></b> coin earned for each word you read</li>
                <li><b class="montserrat read"><?= $en_all_2738[6205]['m_icon'] .' '. $en_all_2738[6205]['m_name'] ?></b> up to <?= number_format(config_var(11061), 0) ?> words per month for free</li>
                <li><b class="montserrat read"><?= $en_all_2738[6205]['m_icon'] .' '. $en_all_2738[6205]['m_name'] ?></b> unlimited words for $<?= config_var(11162) ?> per month</li>
                <li><b class="montserrat blog"><?= $en_all_2738[4535]['m_icon'] .' '. $en_all_2738[4535]['m_name'] ?></b> coins could earn you cash per month</li>
            </ul>
        </div>


        <div class="col-lg-6">

            <h1>TOP PLAYERS</h1>
            <?php
            echo '<table id="leaderboard" class="table table-sm table-striped">';
            echo '<tbody><tr><td colspan="3"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></td></tr></tbody>';
            echo '</table>';
            ?>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $.post("/play/leaderboard/", { }, function (data) {
            $('#leaderboard tbody').html(data);
            $('[data-toggle="tooltip"]').tooltip();
        });
    });
</script>