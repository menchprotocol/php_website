<?php

$playerhandle = (isset($_GET['playerhandle']) ? $_GET['playerhandle'] : null);
$ideahashtag = (!$playerhandle && isset($_GET['ideahashtag']) ? $_GET['ideahashtag'] : null);
$players___11035 = $this->config->item('players___11035'); //Encyclopedia
$players___42263 = $this->config->item('players___42263'); //Link Groups

if ($playerhandle) {
    foreach ($this->Players->read(array(
        'LOWER(playerhandle)' => strtolower($playerhandle),
    )) as $e) {
        echo '<h2 class="center"><a href="' . view_memory(42903, 42902) . $playerhandle . '"><span class="icon-block">' . view_cover($e['playercover']) . '</span> ' . $e['playertext'] . '</a> <a href="' . view_memory(42903, 33286) . $this->uri->segment(1) . '"><i class="far fa-filter-slash"></i></a></h2>';
    }
} elseif ($ideahashtag) {
    foreach ($this->Ideas->read(array(
        'LOWER(ideahashtag)' => strtolower($ideahashtag),
    )) as $i) {
        echo '<h2 class="center"><a href="' . view_memory(42903, 33286) . $ideahashtag . '">' . view_idea_title($i, true) . '</a> <a href="' . view_memory(42903, 33286) . $this->uri->segment(1) . '"><i class="far fa-filter-slash"></i></a></h2>';
    }
}

//Misc Stats, if any:
echo '<div class="center hideIfEmpty"></div>';

foreach ($this->config->item('players___33292') as $playerid1 => $m1) {

    if($playerid1==1309754){
        echo '<div class="mid-text-line compact-midline"><span>' . $m1['m__cover'] . ' <a target="_blank" href="'.view_app_link(4341).'?linkvoid=1" class="grey card_count_' . $playerid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a> ' . $m1['m__title'] . '</span></div>';
        //Void Links
        continue;
    } elseif($playerid1==28956){
        //Nodes
        echo '<div class="mid-text-line compact-midline"><span>' . $m1['m__cover'] . ' <a target="_blank" href="'.view_app_link(4341).'?linkplayertype=4250,4251&linkvoid=0" class="grey card_count_' . $playerid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a> ' . $m1['m__title'] . ':</span></div>';
    } elseif($playerid1==31770){

        //Legend
        echo '<div class="row">';
        echo '<div class="col-3 appender_32292"><i class="far fa-rotate-left"></i></div>';
        echo '<div class="col-3 appender_4486"><i class="far arrow-right-long"></i></div>';
        echo '<div class="col-3 appender_13550"><i class="far arrow-left-long"></i></div>';
        echo '<div class="col-3 appender_31777"><i class="far fa-rotate-right"></i></div>';
        echo '</div>';

        //Links
        echo '<div class="mid-text-line compact-midline"><span>' . $m1['m__cover'] . ' <a target="_blank" href="'.view_app_link(4341).'?linkvoid=0" class="grey card_count_' . $playerid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a> <a href="javascript:void(0)" onclick="$(\'.headlines\').toggleClass(\'hidden\')" class="grey">' . $m1['m__title'] . '</a>:</span></div>';
    }

    echo '<div class="row justify-content list-covers">';

    foreach ($this->config->item('players___' . $playerid1) as $playerid2 => $m2) {

        $is_link = $playerid2 != 12273 && $playerid2 != 12274;

        echo '<div class="card_cover no-padding col-6">';
        echo '<div class="card_frame dropdown_d' . $playerid1 . ' dropdown_' . $playerid2 . '">';

        echo '<div class="card_header" title="' . $m2['m__message'] . '" playerid="' . $playerid2 . '">';
        echo '<div class="large_cover appender_'.$playerid2.'">' . $m2['m__cover'] . '</div>';
        echo '<div class="main__title large_title"><a target="_blank" href="'.view_app_link(4341).'?linkplayertype='.join(',',( $is_link ? $this->config->item('playerids___' . $playerid2) : array(( $playerid2==12273 ? 4250 : 4251 )) )).'&linkvoid=0" class="card_count_' . $playerid2 . '"><i class="fas fa-yin-yang fa-spin"></i></a></div>';
        echo '<div class="main__title large_title" title="@' . $playerid2 . ' @' . $m2['m__handle'] . '"><a href="'.view_memory(42903,42902).$m2['m__handle'].'">' . $m2['m__title'] . '</a></div>';
        echo '</div>';

        if ($is_link) {
            echo '<table class="table card_subcat card_subcat_' . $playerid2 . ' hidden" style="width:100%; margin-top:13px;">'; //table-striped
            $focus_link_group = 0;
            $player_pinned = player_pinned($playerid2, true);
            if (!$player_pinned || !is_array($this->config->item('players___' . $player_pinned)) || !count($this->config->item('players___' . $player_pinned))) {
                continue;
            }
            foreach ($this->config->item('players___' . $player_pinned) as $playerid3 => $m3) {

                //Determine link group:
                foreach(array_intersect($m3['m__following'], $this->config->item('playerids___42263')) as $headline_link){
                    if ($headline_link > 0){
                        if(!$focus_link_group || $focus_link_group!=$headline_link){

                            echo '<tr class="mobile-shrink headlines hidden">';
                            echo '<td class="center" colspan="2" title="@'.$players___42263[$headline_link]['m__handle'].'">';

                            //Search for sibling if Has Family:
                            if(in_array($playerid2, $this->config->item('playerids___42792'))){
                                foreach($this->Links->read(array(
                                    'linkplayerdown' => $headline_link,
                                    'linkplayertype' => 41011, //Family
                                ), array('linkplayerup'), 1) as $sibling){
                                    echo '<a href="'.view_memory(42903,42902).$sibling['playerhandle'].'"><span class="icon-block-sm grey">'.view_cover($sibling['playercover']).'</span><b class="grey">'.$sibling['playertext'].'</b></a><b class="grey"> & </b></b>';
                                }
                            }

                            echo '<a href="'.view_memory(42903,42902).$players___42263[$headline_link]['m__handle'].'"><span class="icon-block-sm grey">'.$players___42263[$headline_link]['m__cover'].'</span><b class="grey">'.$players___42263[$headline_link]['m__title'].'</a>:</b>';

                            echo '</td>';
                            echo '</tr>';
                            $focus_link_group = $headline_link;
                        }
                    }
                }


                echo '<tr class="main__title mobile-shrink" title="' . $m3['m__message'] . '" data-toggle="tooltip" data-placement="top">';
                echo '<td style="text-align: left;" title="@' . $playerid3 . ' @' . $m3['m__handle'] . '"><a href="' . view_memory(42903, 42902) . $m3['m__handle'] . '"><span class="icon-block-sm">' . $m3['m__cover'] . '</span>' . $m3['m__title'] . '</a><span class="last-right-col"><a target="_blank" href="'.view_app_link(4341).'?linkplayertype='.  $playerid3 . '&linkvoid=0" class="card_count_' . $playerid3 . '"><i class="fas fa-yin-yang fa-spin"></i></a></span></td>';
                echo '</tr>';

            }
            echo '</table>';
        }

        echo '</div>';
        echo '</div>';

    }

    echo '</div>';

}

?>

<script>

    function link_graph() {
        $.post("/controller/link_graph", {
            playerhandle: '<?= $playerhandle ?>',
            ideahashtag: '<?= $ideahashtag ?>',
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {

            $.each(data.return_array, function (key, val) {
                var formatted = String(val).replace(/(.)(?=(\d{3})+$)/g, '$1,');
                if (formatted != $(".card_count_" + key + ":first").text()) {
                    $(".card_count_" + key).removeClass('hidden').text(formatted).hide().fadeIn().hide().fadeIn();
                }
            });

        });
    }

    $(document).ready(function () {

        //Load initial stats:
        link_graph();

        //Watch for click to expand:
        $(".card_header").click(function (e) {
            $('.card_subcat_' + $(this).attr('playerid')).toggleClass('hidden');
        });

        //Update stats live:
        $(function () {
            setInterval(link_graph, js_players___6404[33292]['m__message']);
        });

    });

</script>
