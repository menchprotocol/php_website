
<script src="/application/views/source/source_home.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

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

                echo '<div class="list-group-item see_more_sources montserrat source"><span class="icon-block"><i class="far fa-search-plus source"></i></span><a href="javascript:void(0);" onclick="$(\'.see_more_sources\').toggleClass(\'hidden\')"><b style="text-decoration: none !important;" class="source">SEE MORE</b></a></div>';

                echo '<div class="see_more_sources"></div>';

            }

            echo echo_en($my_en,false, ( $count<$show_max ? '' : 'see_more_sources hidden'));

        }
        echo '</div>';
    }



    //Top Players
    $en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION
    echo '<div class="read-topic"><span class="icon-block">'.$en_all_11035[12437]['m_icon'].'</span>'.$en_all_11035[12437]['m_name'].'</div>';
    echo '<div id="load_leaderboard"></div>';






    //Total Coins:
    $en_all_2738 = $this->config->item('en_all_2738'); //MENCH
    echo '<table id="leaderboard" class="table table-sm table-striped tablepadded" style="margin-bottom: 0;">';
    echo '<tr></tr>'; //Skip white
    echo '<tr>';
    echo '<td class="source MENCHcolumn1 montserrat" title="'.number_format($this->config->item('cache_count_source'), 0).'"><span class="icon-block">'.$en_all_2738[4536]['m_icon'].'</span>'.echo_number($this->config->item('cache_count_source')).'<span class="coin-type">'.$en_all_2738[4536]['m_name'].'S</span></td>';
    echo '<td class="idea MENCHcolumn2 montserrat" title="'.number_format($this->config->item('cache_count_idea'), 0).'"><span class="icon-block">'.$en_all_2738[4535]['m_icon'].'</span>'.echo_number($this->config->item('cache_count_idea')).'<span class="coin-type">'.$en_all_2738[4535]['m_name'].'S</span></td>';
    echo '<td class="read MENCHcolumn3 montserrat" title="'.number_format($this->config->item('cache_count_read'), 0).'"><span class="icon-block">'.$en_all_2738[6205]['m_icon'].'</span>'.echo_number($this->config->item('cache_count_read')).'<span class="coin-type">'.$en_all_2738[6205]['m_name'].'S</span></td>';
    echo '</tr>';
    echo '</table>';


    ?>
</div>