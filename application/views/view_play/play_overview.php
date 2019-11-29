
<div class="container">

    <div class="row">
        <div class="col-lg-12">
            <?php
            $navigation = array(
                4536 => '/play/signin', //PLAY
                6205 => '/read', //READ
                4535 => '/blog', //BLOG
            );
            echo '<br />';
            foreach($this->config->item('en_all_2738') as $en_id => $m){
                echo '<a href="'.$navigation[$en_id].'" style="text-decoration:none;">';
                echo '<h2 class="inline montserrat color'.$en_id.'"><span class="icon-block-lg en-icon">'.echo_en_icon($m['m_icon']).'</span> '.$m['m_name'].'</h2>';
                echo '<p class="inline"> '.$m['m_desc'].'<span class="inline-block"><i class="fas fa-arrow-right color'.$en_id.'"></i></span></p>';
                echo '</a>';
                echo '<br />';
            }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php
            echo '<br />';
            echo '<h2 class="inline montserrat blue"><span class="icon-block-lg en-icon"><i class="far fa-users blue"></i></span> PLAYERS</h2>';
            echo '<table id="leaderboard" class="table table-sm table-striped">';
            echo '<tbody><tr><td colspan="3"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></td></tr></tbody>';
            echo '</table>';
            ?>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        load_leaderboard();
    });
</script>