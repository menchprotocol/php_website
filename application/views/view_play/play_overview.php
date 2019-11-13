<?php
$session_en = en_auth();
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
$en_all_10591 = $this->config->item('en_all_10591'); //PLAYER PLAYS
?>

<div class="container">

    <div class="row">
        <?php
        $en_all_4527 = $this->config->item('en_all_4527'); //Platform Cache
        echo '<h1 class="'.( $session_en ? ' learn_more hidden ' : '' ).'">'.$en_all_4527[11968]['m_name'].'</h1>';

        foreach($this->config->item('en_all_11968') as $en_id => $m){
            echo '<div class="col-lg-4" style="text-align: center;"><div style="font-size:100px !important;">'.$m['m_icon'].'</div></div>';
        }
        ?>
    </div>


    <div class="row">
        <div class="col-lg">

        <div class="learn_more"><span class="icon-block-sm"><i class="fas fa-search-plus"></i></span> <a href="javascript:void(0);" onclick="$('.learn_more').toggleClass('hidden');" style="text-decoration: underline;">LEARN MORE</a></div>


        <div class="learn_more hidden">
            <h1>HOW TO PLAY</h1>
            <ul class="none-list">
                <li><b class="montserrat play"><?= $en_all_2738[4536]['m_icon'] .' '. $en_all_2738[4536]['m_name'] ?></b> coin awarded as a new player avatar</li>
                <li><b class="montserrat blog"><?= $en_all_2738[4535]['m_icon'] .' '. $en_all_2738[4535]['m_name'] ?></b> coin earned for each word blogged</li>
                <li><b class="montserrat read"><?= $en_all_2738[6205]['m_icon'] .' '. $en_all_2738[6205]['m_name'] ?></b> coin earned for each word read</li>
                <li><b class="montserrat read"><?= $en_all_2738[6205]['m_icon'] .' '.$en_all_2738[6205]['m_name'] ?></b> up to <?= config_var(11061) ?> words per month free</li>
                <li><b class="montserrat read"><?= $en_all_2738[6205]['m_icon'] .' '.$en_all_2738[6205]['m_name'] ?></b> unlimited words for $<?= config_var(11162) ?> per month</li>
                <li><b class="montserrat blog"><?= $en_all_2738[4535]['m_icon'] .' '. $en_all_2738[4535]['m_name'] ?></b> coins earn cash income per month</li>
            </ul>
        </div>



        <div class="<?= ( $session_en ? '' : ' learn_more hidden ' ) ?>">
            <h1>TOP PLAYERS</h1>
            <?php
            echo '<table id="leaderboard" class="table table-sm table-striped">';
            echo '<tbody><tr><td colspan="3"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></td></tr></tbody>';
            echo '</table>';
            ?>
        </div>


        <?php

        //Actually count PLAYERS:
        $en_count = $this->PLAY_model->en_fetch(array(
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
        ), array(), 0, 0, array(), 'COUNT(en_id) as total_public_entities');

        //COUNT WORDS BLOG/READ:
        $words_blog = $this->READ_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_10589')) . ')' => null, //BLOGGERS
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        ), array(), 0, 0, array(), 'SUM(ln_words) as total_words');

        $words_read = $this->READ_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_10590')) . ')' => null, //READERS
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        ), array(), 0, 0, array(), 'SUM(ln_words) as total_words');

        $item_count = array(
            6205 => echo_number(abs($words_read[0]['total_words'])),
            4536 => echo_number($en_count[0]['total_public_entities']),
            4535 => echo_number($words_blog[0]['total_words']),
        );

        echo '<div class="container learn_more hidden table-striped" style="margin-bottom:30px;">
            <div class="row">
                <table class="three-menus">
                    <tr>';

        foreach($this->config->item('en_all_2738') as $en_id => $m){
            $handle = strtolower($m['m_name']);
            echo '<td valign="bottom" style="width:'.( $en_id==4536 ? 46 : 27 ).'%"><span class="'.$handle.' border-'.$handle.'"><span class="parent-icon icon-block-sm">' . $m['m_icon'] . '</span><span class="montserrat current_count">'.$item_count[$en_id].'</span> <span class="montserrat">' . $m['m_desc'] . '</span></span></td>';
        }

        echo '</tr>
                </table>
            </div>
        </div>';

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