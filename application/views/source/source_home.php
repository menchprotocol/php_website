<script>
    $(document).ready(function () {
        load_leaderboard();
    });
</script>

<div class="container">

    <?php


    //My Sources:
    if($session_en){

        $show_max = config_var(11986);
        echo '<div class="list-group" style="padding-bottom: 34px;">';
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //SOURCE COIN
            'ln_creator_source_id' => $session_en['en_id'],
        ), array('en_child'), config_var(11064), 0, array('en_weight' => 'DESC')) as $count=>$my_en){

            if($count==$show_max){

                echo '<div class="list-group-item montserrat source"><span class="icon-block"><i class="far fa-search-plus source"></i></span><a href="javascript:void(0);" onclick="$(\'.see_more_who\').toggleClass(\'hidden\')"><b style="text-decoration: none !important;">SEE MORE</b></a></div>';

            }

            echo echo_en($my_en,false, ( $count<$show_max ? '' : 'see_more_who hidden'));

        }
        echo '</div>';
    }




    //Top Players
    $en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION
    echo '<div class="read-topic"><span class="icon-block">'.$en_all_11035[12437]['m_icon'].'</span>'.$en_all_11035[12437]['m_name'].'</div>';
    echo '<div id="load_leaderboard"></div>';


    ?>
</div>