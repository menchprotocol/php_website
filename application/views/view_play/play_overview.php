<?php
$session_en = en_auth();
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
$en_all_10591 = $this->config->item('en_all_10591'); //PLAYER PLAYS
?>
<div class="container">

    <div class="row">
        <div class="col-lg">

            <h1>MENCH IS</h1>
            <ul class="intructions-list double-line-list">
                <li>A learning game where players earn <b class="montserrat">MENCH COINS</b> as they <b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b> and <b class="montserrat blog"><?= $en_all_2738[4535]['m_name'] ?></b></li>
                <li>A publishing platform to collaboratively create interactive content and share with your audience</li>
                <li>A personalized reading experience delivered over the web or Messenger</li>
                <li class="learn_more_start"><a href="javascript:void(0);" onclick="$('.learn_more_start').remove();$('.learn_more').toggleClass('hidden');">LEARN MORE</a></li>
                <li class="learn_more hidden">A non-profit organization on a mission to expand your potential</li>
                <li class="learn_more hidden">An open-source protocol for building and sharing consensus</li>
                <li class="learn_more hidden">A community of creators who share stories and ideas that matter</li>
            </ul>

            <h1 style="margin-top:21px;">HOW TO PLAY</h1>
            <ul class="intructions-list">
                <li>Earn a <?= $en_all_2738[6205]['m_icon'] ?> <b class="montserrat">COIN</b> for each word you <b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b></li>
                <li>Earn a <?= $en_all_2738[4535]['m_icon'] ?> <b class="montserrat">COIN</b> for each word you <b class="montserrat blog"><?= $en_all_2738[4535]['m_name'] ?></b></li>
                <li><b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b> up to <?= config_var(11061) ?> words/month <b class="montserrat">FREE</b></li>
                <li><b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b> unlimited words for <b class="montserrat">$<?= config_var(11162) ?>/month</b></li>
                <li>Earn monthly cash with your <?= $en_all_2738[4535]['m_icon'] ?> <b class="montserrat">COINS</b></li>
            </ul>

            <?php
            echo '<div style="padding-bottom:21px;">';

            //READ or BLOG
            echo '<a href="/read" class="btn btn-read montserrat" style="margin-left: 5px;">'.$en_all_2738[6205]['m_name'].' <i class="fas fa-arrow-right"></i></a>';
            echo '<a href="/blog" class="btn btn-blog montserrat" style="margin-left: 5px;">'.$en_all_2738[4535]['m_name'].' <i class="fas fa-arrow-right"></i></a>';

            echo '</div>';

            ?>

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