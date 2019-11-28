
<div class="container">

    <div class="row">
        <div class="col-lg-12">
            <?php
            echo '<br />';
            foreach($this->config->item('en_all_2738') as $en_id => $m){
                echo '<h1 class="inline montserrat color'.$en_id.'"><span class="icon-block-lg en-icon">'.echo_en_icon($m['m_icon']).'</span> '.$m['m_name'].'</h1>';
                echo '<p class="inline"> '.$m['m_desc'].'</p>';
                echo '<br />';
            }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php
            echo '<h1 class="inline montserrat"><span class="icon-block-lg en-icon"><i class="far fa-users"></i></span> PLAYERS</h1>';
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