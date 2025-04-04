<?php

$playerhandle = ( isset($_GET['playerhandle']) ? $_GET['playerhandle'] : null );
$ideahashtag = ( !$playerhandle && isset($_GET['ideahashtag']) ? $_GET['ideahashtag'] : null );
$players___11035 = $this->config->item('players___11035'); //Encyclopedia
$players___42263 = $this->config->item('players___42263'); //Link Groups


if($playerhandle){
    foreach($this->Players->read(array(
        'LOWER(playerhandle)' => strtolower($playerhandle),
    )) as $e){
        echo '<h2 class="center"><a href="'.view_memory(42903,42902).$playerhandle.'"><span class="icon-block">'.view_cover($e['playercover']).'</span> <u>' . $e['playertext'] . '</u></a> <a href="'.view_memory(42903,33286).$this->uri->segment(1).'"><i class="far fa-filter-slash"></i></a></h2>';
    }
} elseif($ideahashtag){
    foreach($this->Ideas->read(array(
        'LOWER(ideahashtag)' => strtolower($ideahashtag),
    )) as $i){
        echo '<h2 class="center"><a href="'.view_memory(42903,33286).$ideahashtag.'"><u>' . view_idea_title($i, true) . '</u></a> <a href="'.view_memory(42903,33286).$this->uri->segment(1).'"><i class="far fa-filter-slash"></i></a></h2>';
    }
}

//Misc Stats, if any:
echo '<div class="center miscstats hideIfEmpty"></div>';

foreach($this->config->item('players___33292') as $playerid1 => $m1) {

    echo '<div class="mid-text-line compact-midline"><span><b class="card_count_'.$playerid1.'"><i class="fas fa-yin-yang fa-spin"></i></b><span class="icon-block">'.$m1['m__cover'].'</span> '.$m1['m__title'].':</span></div>';

    echo '<div class="row justify-content list-covers">';
    
    foreach($this->config->item('players___'.$playerid1) as $playerid2 => $m2) {

        echo '<div class="card_cover no-padding col-6">';
        echo '<div class="card_frame dropdown_d'.$playerid1.' dropdown_'.$playerid2.'" playerid="'.$playerid2.'">';

        echo '<div title="'.$m2['m__message'].'">';
        echo '<div class="large_cover">'.$m2['m__cover'].'</div>';
        echo '<div class="main__title large_title"><b class="card_count_'.$playerid2.'"><i class="fas fa-yin-yang fa-spin"></i></b></div>';
        echo '<div class="main__title large_title" title="@'.$playerid2.' @'.$m2['m__handle'].'">'.$m2['m__title'].'</div>';
        echo '</div>';


        if($playerid2!=12273 && $playerid2!=12274){
            echo '<table class="table table-striped card_subcat card_subcat_'.$playerid2.' hidden" style="width:100%; margin-top:13px;">';
            $focus_link_group = 0;
            $player_pinned = player_pinned($playerid2, true);
            if(!$player_pinned || !is_array($this->config->item('players___'.$player_pinned)) || !count($this->config->item('players___'.$player_pinned)) ){
                continue;
            }
            foreach($this->config->item('players___'.$player_pinned) as $playerid3 => $m3) {

                echo '<tr class="mobile-shrink" title="'.$m3['m__message'].'" data-toggle="tooltip" data-placement="top">';
                echo '<td style="text-align: left;" title="@'.$playerid3.' @'.$m3['m__handle'].'"><a href="'.view_memory(42903,42902).$m3['m__handle'].'"><span class="icon-block-sm">'.$m3['m__cover'].'</span>'.$m3['m__title'].'</a><span class="last-right-col"><b class="card_count_'.$playerid3.'"><i class="fas fa-yin-yang fa-spin"></i></b></span></td>';
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

    function refresh_wallet(){
        $.post("/controller/refresh_wallet", {
            playerhandle: '<?= $playerhandle ?>',
            ideahashtag: '<?= $ideahashtag ?>',
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

        //Load initial stats:
        refresh_wallet();

        //Watch for click to expand:
        $(".card_frame").click(function (e) {
            $('.card_subcat_'+$(this).attr('playerid')).toggleClass('hidden');
        });



        //Update stats live:
        $(function () {
            setInterval(refresh_wallet, js_players___6404[33292]['m__message']);
        });

    });

</script>
