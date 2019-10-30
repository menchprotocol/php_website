<?php
$session_en = en_auth();
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
$en_all_10591 = $this->config->item('en_all_10591'); //PLAYER PLAYS
?>

<div class="container">
    <div class="row">
        <div class="col-lg">


        <?php
        $en_all_4527 = $this->config->item('en_all_4527'); //Platform Cache
        echo '<h1 class="'.( $session_en ? ' learn_more hidden ' : '' ).'">'.$en_all_4527[11968]['m_name'].'</h1>';
        echo '<ul class="none-list">';
        foreach($this->config->item('en_all_11968') as $en_id => $m){
            echo '<li class="'.( in_array(11982 , $m['m_parents']) && !$session_en ? '' : ' learn_more hidden ' ).'"><span class="icon-block-sm">'.$m['m_icon'].'</span> <b class="montserrat">'.$m['m_name'].'</b> '.$m['m_desc'].'</li>';
        }
        echo '<li class="learn_more"><span class="icon-block-sm"><i class="fas fa-search-plus"></i></span> <a href="javascript:void(0);" onclick="$(\'.learn_more\').toggleClass(\'hidden\');update_basic_stats(0);" style="text-decoration: underline;">LEARN MORE</a></li>';
        echo '</ul>';
        ?>


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
        if($this->uri->segment(1) != 'play'){

            echo '<div class="container learn_more hidden table-striped" style="margin-bottom:30px;">
            <div class="row">
                <table class="three-menus">
                    <tr>';



            foreach($this->config->item('en_all_2738') as $en_id => $m){
                $handle = strtolower($m['m_name']);
                echo '<td valign="bottom" style="width:'.( $en_id==4536 ? 46 : 27 ).'%"><span class="'.$handle.' border-'.$handle.'"><span class="parent-icon icon-block-sm">' . $m['m_icon'] . '</span><span class="montserrat current_count"><i class="far fa-yin-yang fa-spin"></i></span><div class="montserrat">' . $m['m_desc'] . '</div></span></td>';
            }

            echo '</tr>
                </table>
            </div>
        </div>';

        }
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