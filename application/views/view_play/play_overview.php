<?php
$session_en = en_auth();
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
$en_all_10591 = $this->config->item('en_all_10591'); //PLAYER PLAYS
?>
<div class="container">

    <div class="row">
        <div class="col-lg">

            <h1>ABOUT US</h1>
            <p><b class="montserrat">MENCH</b> is an interactive blogging platform that allows anyone to share stories and ideas that matter.</p>
            <p>Creators use our simple publishing tools to make interactive blogs that can be read over the web or Messenger.</p>
            <p>We're non-profit, open-source and on a mission to expand your potential by building and sharing consensus.</p>


            <h1 style="margin-top:21px;">HOW TO PLAY</h1>
            <ul class="intructions-list">
                <li>Get a <?= $en_all_2738[6205]['m_icon'] ?> coin for each word you <b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b></li>
                <li>Get a <?= $en_all_2738[4535]['m_icon'] ?> coin for each word you <b class="montserrat blog"><?= $en_all_2738[4535]['m_name'] ?></b></li>
                <li><b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b> up to <?= config_var(11061) ?> words/month FREE</li>
                <li><b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b> unlimited words for $<?= config_var(11162) ?>/month</li>
                <li>Earn monthly cash with your <?= $en_all_2738[4535]['m_icon'] ?> coins</li>
            </ul>


            <div>
                <a href="/read" class="btn btn-read montserrat">START READING <i class="fas fa-angle-right"></i></a>
                <a href="/blog" class="btn btn-blog montserrat">START BLOGGING <i class="fas fa-angle-right"></i></a>
            </div>

        </div>
        <div class="col-lg">

            <h1 style="margin-bottom: 0;">TOP PLAYERS</h1>
            <?php
            echo '<table id="leaderboard" class="table table-sm table-striped">';
            echo '<thead>';
            echo '<tr style="padding:0;">';
            echo '<td style="width: 34%">&nbsp;</td>';
            echo '<td style="width: 33%"><span style="padding-right: 2px;">'.$en_all_2738[6205]['m_icon'].'</span><b class="montserrat read">'.$en_all_2738[6205]['m_name'].'</b></td>';
            echo '<td style="width: 33%"><span style="padding-right: 2px;">'.$en_all_2738[4535]['m_icon'].'</span><b class="montserrat blog">'.$en_all_2738[4535]['m_name'].'</b></td>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody><tr><td colspan="3"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></td></tr></tbody>';
            echo '</table>';
            ?>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $.post("/play/leaderboard/", {}, function (data) {
                $('#leaderboard tbody').html(data);
                $('[data-toggle="tooltip"]').tooltip();
            });
        });
    </script>

</div>