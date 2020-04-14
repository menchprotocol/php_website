<script>
    $(document).ready(function () {
        load_leaderboard();
    });
</script>

<div class="container">

    <?php

    //My Sources:
    if($session_en){
        echo '<div class="list-group" style="padding-bottom: 34px;">';
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //SOURCE COIN
            'ln_creator_source_id' => $session_en['en_id'],
        ), array('en_child'), config_var(11064), 0, array('en_weight' => 'DESC')) as $my_en){
            echo echo_en($my_en,false);
        }
        echo '</div>';
    }

    //Top Players
    $en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION
    echo '<div class="read-topic"><span class="icon-block">'.$en_all_11035[12437]['m_icon'].'</span>'.$en_all_11035[12437]['m_name'].'</div>';
    echo '<div id="load_leaderboard"></div>';

    ?>
</div>