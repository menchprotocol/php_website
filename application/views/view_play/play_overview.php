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
                <li>A game of words where players earn coins as they <b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b> and <b class="montserrat blog"><?= $en_all_2738[4535]['m_name'] ?></b></li>
                <li>A publishing platform for creating and sharing interactive content</li>
                <li>A personalized reading experience delivered over the web or Messenger</li>
                <li class="learn_more hidden">A non-profit and open-source project for building and sharing consensus</li>
                <li class="learn_more hidden">A community of thinkers and doers who share stories and ideas that matter</li>
            </ul>
            <div class="learn_more"><span class="parent-icon icon-block-sm"><i class="fas fa-search-plus"></i></span><a href="javascript:void(0);" onclick="$('.learn_more').toggleClass('hidden')">LEARN MORE</a></div>


            <h1 style="margin-top:21px;">HOW TO PLAY</h1>
            <ul class="intructions-list">
                <li>Earn a <?= $en_all_2738[6205]['m_icon'] ?> coin for each word you <b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b></li>
                <li>Earn a <?= $en_all_2738[4535]['m_icon'] ?> coin for each word you <b class="montserrat blog"><?= $en_all_2738[4535]['m_name'] ?></b></li>
                <li><b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b> up to <?= config_var(11061) ?> words/month FREE</li>
                <li><b class="montserrat read"><?= $en_all_2738[6205]['m_name'] ?></b> unlimited words for $<?= config_var(11162) ?>/month</li>
                <li>Earn monthly cash with your <?= $en_all_2738[4535]['m_icon'] ?> coins</li>
            </ul>

            <?php
            echo '<div style="padding-bottom:21px;">';

            if ($session_en) {
                echo '<a href="/play/'.$session_en['en_id'].'" class="btn btn-play montserrat">My Profile <i class="fas fa-arrow-right"></i></a>';
            } else {
                echo '<a href="/sign" class="btn btn-play montserrat">'.$en_all_11035[4269]['m_name'].' <i class="fas fa-arrow-right"></i></a>';
            }

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