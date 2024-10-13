<?php

$e__handle = ( isset($_GET['e__handle']) ? $_GET['e__handle'] : null );
$i__hashtag = ( !$e__handle && isset($_GET['i__hashtag']) ? $_GET['i__hashtag'] : null );
$e___11035 = $this->config->item('e___11035'); //Encyclopedia
$e___42225 = $this->config->item('e___42225'); //MENCH Points
$e___42263 = $this->config->item('e___42263'); //Link Groups


if($e__handle){
    foreach($this->Sources->read(array(
        'LOWER(e__handle)' => strtolower($e__handle),
    )) as $e){
        echo '<h2 class="center"><a href="'.view_memory(42903,42902).$e__handle.'"><span class="icon-block">'.view_cover($e['e__cover']).'</span> <u>' . $e['e__title'] . '</u></a> <a href="'.view_memory(42903,33286).$this->uri->segment(1).'"><i class="far fa-filter-slash"></i></a></h2>';
    }
} elseif($i__hashtag){
    foreach($this->Ideas->read(array(
        'LOWER(i__hashtag)' => strtolower($i__hashtag),
    )) as $i){
        echo '<h2 class="center"><a href="'.view_memory(42903,33286).$i__hashtag.'"><u>' . view_i_title($i, true) . '</u></a> <a href="'.view_memory(42903,33286).$this->uri->segment(1).'"><i class="far fa-filter-slash"></i></a></h2>';
    }
}

//Misc Stats, if any:
echo '<div class="center miscstats hideIfEmpty"></div>';

foreach($this->config->item('e___33292') as $e__id1 => $m1) {

    echo '<h3 class="center centerh advanced-stats hidden main__title" title="@'.$m1['m__handle'].'"><div class="large-cover">'.$m1['m__cover'].'</div><b class="card_count_'.$e__id1.'"><i class="fas fa-yin-yang fa-spin"></i></b> '.$m1['m__title'].':</h3>';

    echo '<div class="row justify-content list-covers">';
    
    foreach($this->config->item('e___'.$e__id1) as $e__id2 => $m2) {

        echo '<div class="card_cover no-padding col-6 '.( !in_array($e__id2, $this->config->item('n___14874')) ? ' advanced-stats hidden ' : '' ).'">';
        echo '<div class="card_frame dropdown_d'.$e__id1.' dropdown_'.$e__id2.'" e__id="'.$e__id2.'">';

        echo '<div title="'.$m2['m__message'].'">';
        echo '<div class="large_cover">'.$m2['m__cover'].'</div>';
        echo '<div class="main__title large_title"><b class="card_count_'.$e__id2.'"><i class="fas fa-yin-yang fa-spin"></i></b></div>';
        echo '<div class="main__title large_title" title="@'.$m2['m__handle'].'">'.$m2['m__title'].'</div>';
        echo '</div>';

        echo '<table class="table table-striped card_subcat card_subcat_'.$e__id2.' hidden" style="width:100%; margin-top:13px;">';

        $focus_link_group = 0;
        $e_pinned = e_pinned($e__id2, true);
        if(!$e_pinned || !is_array($this->config->item('e___'.$e_pinned)) || !count($this->config->item('e___'.$e_pinned)) ){
            continue;
        }
        foreach($this->config->item('e___'.$e_pinned) as $e__id3 => $m3) {

            foreach(array_intersect($m3['m__following'], $this->config->item('n___42263')) as $headline_link){
                if ($headline_link > 0){
                    if(!$focus_link_group || $focus_link_group!=$headline_link){

                        echo '<tr class="mobile-shrink">';
                        echo '<td class="center" colspan="2" title="@'.$e___42263[$headline_link]['m__handle'].'">';

                        //Search for sibling If Has Family:
                        if(in_array($e__id2, $this->config->item('n___42792'))){
                            foreach($this->Ledger->read(array(
                                'x__follower' => $headline_link,
                                'x__type' => 42570, //Family
                                    ), array('x__following'), 1) as $sibling){
                                echo '<a href="'.view_memory(42903,42902).$sibling['e__handle'].'"><span class="icon-block-sm grey">'.view_cover($sibling['e__cover']).'</span><b class="main__title grey"><u>'.$sibling['e__title'].'</u></b></a><b class="main__title grey"> & </b></b>';
                            }
                        }

                        echo '<a href="'.view_memory(42903,42902).$e___42263[$headline_link]['m__handle'].'"><span class="icon-block-sm grey">'.$e___42263[$headline_link]['m__cover'].'</span><b class="main__title grey"><u>'.$e___42263[$headline_link]['m__title'].'</u></a>:</b>';

                        echo '</td>';
                        echo '</tr>';
                        $focus_link_group = $headline_link;
                    }
                }
            }

            echo '<tr class="mobile-shrink" title="'.$m3['m__message'].'" data-toggle="tooltip" data-placement="top">';
            echo '<td style="text-align: left;" title="@'.$m3['m__handle'].'"><a href="'.view_memory(42903,42902).$m3['m__handle'].'"><span class="icon-block-sm">'.$m3['m__cover'].'</span>'.$m3['m__title'].'</a><span class="last-right-col"><b class="card_count_'.$e__id3.'"><i class="fas fa-yin-yang fa-spin"></i></b></span><span class="second-right-col points_frame hidden">'.( isset($e___42225[$e__id3]['m__message']) && intval($e___42225[$e__id3]['m__message'])>0 ? $e___42225[$e__id3]['m__message'].'<span class="icon-block-xs">'.$e___11035[42225]['m__cover'].'</span>' : '' ).'</span></td>';
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

    function x__refresh_gameplay(){
        $.post("/app/x__refresh_gameplay", {
            e__handle: '<?= $e__handle ?>',
            i__hashtag: '<?= $i__hashtag ?>',
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {


            $.each(data.return_array, function (key, val) {
                var formatted = String(val).replace(/(.)(?=(\d{3})+$)/g,'$1,');
                if (formatted != $(".card_count_"+key+":first").text()){
                    $(".card_count_"+key).removeClass('hidden').text(formatted).hide().fadeIn().hide().fadeIn();
                }
            });

            //Load Misc Stats, if any:
            if (data.miscstats != $('.miscstats').html()){
                $('.miscstats').html(data.miscstats).hide().fadeIn().hide().fadeIn();
            }

        });
    }

    $(document).ready(function () {

        $("h1").append('<a class="icon-block" href="javascript:void(0);" onclick="$(\'.advanced-stats\').toggleClass(\'hidden\');"><i class="far fa-search-plus advanced-stats" style="font-size: 0.34em !important;"></i><i class="far fa-search-minus advanced-stats hidden" style="font-size: 0.34em !important;"></i></a>').append('<a class="icon-block-sm advanced-stats hidden" href="javascript:void(0);" onclick="$(\'.points_frame\').toggleClass(\'hidden\');"><span class="points_frame"><?= $e___11035[42225]['m__cover'] ?></span></a>');

        //Load initial stats:
        x__refresh_gameplay();

        //Watch for click to expand:
        $(".card_frame").click(function (e) {
            $('.card_subcat_'+$(this).attr('e__id')).toggleClass('hidden');
        });

        //Update stats live:
        $(function () {
            setInterval(x__refresh_gameplay, js_e___6404[33292]['m__message']);
        });

    });

</script>
