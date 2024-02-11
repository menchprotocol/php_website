<?php

$e__handle = ( isset($_GET['e__handle']) ? $_GET['e__handle'] : null );
$i__hashtag = ( !$e__handle && isset($_GET['i__hashtag']) ? $_GET['i__hashtag'] : null );
$e___11035 = $this->config->item('e___11035'); //Summary
$e___42225 = $this->config->item('e___42225'); //MENCH Points
$e___42263 = $this->config->item('e___42263'); //Link Groups


if($e__handle){
    foreach($this->E_model->fetch(array(
        'LOWER(e__handle)' => strtolower($e__handle),
    )) as $e){
        echo '<h2><a href="/@'.$e__handle.'"><span class="icon-block">'.view_cover($e['e__cover']).'</span> <u>' . $e['e__title'] . '</u></a></h2>';
    }
} elseif($i__hashtag){
    foreach($this->I_model->fetch(array(
        'LOWER(i__hashtag)' => strtolower($i__hashtag),
    )) as $i){
        echo '<h2><a href="/'.$i__hashtag.'"><u>' . view_i_title($i, true) . '</u></a></h2>';
    }
}

//Misc Stats, if any:
echo '<div class="center miscstats hideIfEmpty"></div>';

foreach($this->config->item('e___33292') as $e__id1 => $m1) {

    echo '<h3 class="center centerh advanced-stats hidden main__title" title="@'.$m1['m__handle'].'"><div class="large-cover">'.$m1['m__cover'].'</div><b class="card_count_'.$e__id1.'"><i class="far fa-yin-yang fa-spin"></i></b> '.$m1['m__title'].':</h3>';

    echo '<div class="row justify-content list-covers">';
    
    foreach($this->config->item('e___'.$e__id1) as $e__id2 => $m2) {

        echo '<div class="card_cover no-padding col-6 '.( !in_array($e__id2, $this->config->item('n___14874')) ? ' advanced-stats hidden ' : '' ).'">';
        echo '<div class="card_frame dropdown_d'.$e__id1.' dropdown_'.$e__id2.'" e__id="'.$e__id2.'">';

        echo '<div title="'.$m2['m__message'].'">';
        echo '<div class="large_cover">'.$m2['m__cover'].'</div>';
        echo '<div class="main__title large_title"><b class="card_count_'.$e__id2.'"><i class="far fa-yin-yang fa-spin"></i></b></div>';
        echo '<div class="main__title large_title" title="@'.$m2['m__handle'].'">'.$m2['m__title'].'</div>';
        echo '</div>';

        echo '<table class="table table-striped card_subcat card_subcat_'.$e__id2.' hidden" style="width:100%; margin-top:13px;">';

        $focus_link_group = 0;
        foreach($this->config->item('e___'.map_primary_links($e__id2)) as $e__id3 => $m3) {

            foreach(array_intersect($m3['m__following'], $this->config->item('n___42263')) as $found_link_group){
                if ($found_link_group > 0){
                    if(!$focus_link_group || $focus_link_group!=$found_link_group){

                        echo '<tr class="mobile-shrink">';
                        echo '<td class="center" colspan="2" title="@'.$e___42263[$found_link_group]['m__handle'].'">';

                        //Search for sibling if Has Family:
                        if(in_array($e__id2, $this->config->item('n___42792'))){
                            foreach($this->X_model->fetch(array(
                                'x__follower' => $found_link_group,
                                'x__type' => 42570, //Family
                                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            ), array('x__following'), 1) as $sibling){
                                echo '<a href="/@'.$sibling['e__handle'].'"><span class="icon-block-xs">'.view_cover($sibling['e__cover']).'</span><b class="main__title grey"><u>'.$sibling['e__title'].'</u></b></a><b class="main__title grey"> & </b></b>';
                            }
                        }

                        echo '<a href="/@'.$e___42263[$found_link_group]['m__handle'].'"><span class="icon-block-xs">'.$e___42263[$found_link_group]['m__cover'].'</span><b class="main__title grey"><u>'.$e___42263[$found_link_group]['m__title'].'</u></a>:</b>';

                        echo '</td>';
                        echo '</tr>';
                        $focus_link_group = $found_link_group;
                    }
                }
            }

            echo '<tr class="mobile-shrink" title="'.$m3['m__message'].'" data-toggle="tooltip" data-placement="top">';
            echo '<td style="text-align: left;" title="@'.$m3['m__handle'].'"><a href="/@'.$m3['m__handle'].'"><span class="icon-block-xs">'.$m3['m__cover'].'</span>'.$m3['m__title'].'</a><span class="last-right-col"><b class="card_count_'.$e__id3.'"><i class="far fa-yin-yang fa-spin"></i></b></span><span class="second-right-col points_frame hidden">'.( isset($e___42225[$e__id3]['m__message']) && intval($e___42225[$e__id3]['m__message'])>0 ? $e___42225[$e__id3]['m__message'].'<span class="icon-block-xx">'.$e___11035[42225]['m__cover'].'</span>' : '' ).'</span></td>';
            echo '</tr>';

        }


        echo '</table>';

        echo '</div>';
        echo '</div>';

    }
    
    echo '</div>';

}

?>

<script>

    function x_33292(){
        $.post("/ajax/x_33292", {
            e__handle: '<?= $e__handle ?>',
            i__hashtag: '<?= $i__hashtag ?>',
        }, function (data) {

            //Update stats numbers:
            data.return_array.forEach(function(item) {
                if (item.sub_counter != $(".card_count_"+item.sub_id+":first").text()){
                    $(".card_count_"+item.sub_id).removeClass('hidden').text(item.sub_counter).hide().fadeIn().hide().fadeIn();
                }
            });

            //Load Misc Stats, if any:
            if (data.miscstats != $('.miscstats').html()){
                $('.miscstats').html(data.miscstats).hide().fadeIn().hide().fadeIn();
            }

        });
    }

    $(document).ready(function () {

        $("h1").append('<a class="icon-block" href="javascript:void(0);" onclick="$(\'.advanced-stats\').toggleClass(\'hidden\');"><i class="fas fa-search-plus advanced-stats" style="font-size: 0.34em !important;"></i><i class="fas fa-search-minus advanced-stats hidden" style="font-size: 0.34em !important;"></i></a>').append('<a class="icon-block-xs advanced-stats hidden" href="javascript:void(0);" onclick="$(\'.points_frame\').toggleClass(\'hidden\');"><span class="points_frame"><i class="far fa-hexagon-plus fa-spin-slow zq6255" aria-hidden="true"></i></span><span class="points_frame hidden"><i class="far fa-hexagon-minus fa-spin-slow zq6255" aria-hidden="true"></i></span></a>');

        //Load initial stats:
        x_33292();

        //Watch for click to expand:
        $(".card_frame").click(function (e) {
            $('.card_subcat_'+$(this).attr('e__id')).toggleClass('hidden');
        });

        //Update stats live:
        $(function () {
            setInterval(x_33292, js_e___6404[33292]['m__message']);
        });

    });

</script>
