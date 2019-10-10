<?php
$session_en = $this->session->userdata('user');
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
$en_all_7305 = $this->config->item('en_all_7305'); //MENCH PLATFORM
$en_all_10591 = $this->config->item('en_all_10591'); //PLAYER PLAYS

?>
<div class="container">

    <h1>RULES</h1>
    <ul style="list-style: decimal;">
        <li><b class="montserrat"><?= $en_all_2738[4536]['m_name'] ?></b> starts when you <b class="montserrat"><?= $en_all_2738[6205]['m_name'] ?></b> or <b class="montserrat"><?= $en_all_2738[4535]['m_name'] ?></b>.</li>
        <li><b class="montserrat"><?= $en_all_2738[6205]['m_name'] ?></b> <?= number_format($this->config->item('read_word_limit_monthly'), 0) ?> monthly words FREE or subscribe for $5.</li>
        <li><b class="montserrat"><?= $en_all_2738[4535]['m_name'] ?></b> to earn $$$ relative to your monthly <b class="montserrat"><?= $en_all_2738[6205]['m_name'] ?></b>s.</li>
    </ul>


    <div style="padding-bottom:21px;">
        <?php
        if (!isset($session_en['en_id'])) {
            echo '<a href="/sign" class="btn btn-play montserrat">'.$en_all_7305[4269]['m_name'].'</a>';
        }
        ?>
        <a href="/read" class="btn btn-read montserrat"><?= $en_all_2738[6205]['m_name'] ?></a>
        <a href="/blog" class="btn btn-blog montserrat"><?= $en_all_2738[4535]['m_name'] ?></a>
    </div>


    <h1>TOP PLAYERS</h1>
    <div class="row">
        <div class="col-md">
            <?php
            echo '<table class="table table-sm table-striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="icon-block">'.$en_all_10591[10590]['m_icon'].'</span><h2 class="inline-block">'.$en_all_10591[10590]['m_name'].'</h2></td>';
            echo '<td style="text-align: right;"><h2>WORDS</h2></td>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody id="content_10590"><tr><td colspan="2"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></td></tr></tbody>';
            echo '</table>';
            ?>
        </div>
        <div class="col-md offset-md-1">
            <?php
            echo '<table class="table table-sm table-striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="icon-block">'.$en_all_10591[10589]['m_icon'].'</span><h2 class="inline-block">'.$en_all_10591[10589]['m_name'].'</h2></td>';
            echo '<td style="text-align: right;"><h2>WORDS</h2></td>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody id="content_10589"><tr><td colspan="2"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></td></tr></tbody>';
            echo '</table>';
            ?>
        </div>
    </div>


    <script>


        $(document).ready(function () {
            setTimeout(function () {
                load_leaderboard(10589);
                load_leaderboard(10590);
            }, 377);
        });

        function load_leaderboard(choose_10591){
            $.post("/play/leaderboard/"+choose_10591, {}, function (data) {
                $('#content_' + choose_10591).html(data);
                $('[data-toggle="tooltip"]').tooltip();
            });
        }

    </script>


</div>