<?php

$handlestring = (isset($_GET['handlestring']) ? $_GET['handlestring'] : null);
$hashtagstring = (!$handlestring && isset($_GET['hashtagstring']) ? $_GET['hashtagstring'] : null);
$handles___11035 = $this->config->item('handles___11035'); //Encyclopedia

if ($handlestring) {
    foreach ($this->Handles->read(array(
        'LOWER(handlestring)' => strtolower($handlestring),
    )) as $e) {
        echo '<h2 class="center"><a href="' . view_memory(42903, 42902) . $handlestring . '"><span class="icon-block">' . view_cover($e['handlecover']) . '</span> ' . $e['handlevalue'] . '</a> <a href="' . view_memory(42903, 33286) . $this->uri->segment(1) . '"><i class="far fa-filter-slash"></i></a></h2>';
    }
} elseif ($hashtagstring) {
    foreach ($this->Hashtags->read(array(
        'LOWER(hashtagstring)' => strtolower($hashtagstring),
    )) as $i) {
        echo '<h2 class="center"><a href="' . view_memory(42903, 33286) . $hashtagstring . '">' . view_hashtag_title($i, true) . '</a> <a href="' . view_memory(42903, 33286) . $this->uri->segment(1) . '"><i class="far fa-filter-slash"></i></a></h2>';
    }
}

//Misc Stats, if any:
echo '<div class="center hideIfEmpty"></div>';

foreach ($this->config->item('handles___33292') as $handleid1 => $m1) {

    if($handleid1==1309754){
        echo '<div class="mid-text-line compact-midline"><span class="hidden headlines">' . $m1['m__cover'] . ' <a target="_blank" href="'.view_app_chain(4341).'?chainvoid=1" class="grey card_count_' . $handleid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a> ' . $m1['m__title'] . '</span></div>';
        //Void Chains
        continue;
    } elseif($handleid1==28956){
        //Nodes
        echo '<div class="mid-text-line compact-midline"><span>' . $m1['m__cover'] . ' <span class="hidden headlines"><a target="_blank" href="'.view_app_chain(4341).'?chainhandletype=12273,12274&chainvoid=0" class="grey card_count_' . $handleid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a></span> ' . $m1['m__title'] . ':</span></div>';
    } elseif($handleid1==31770){

        //Chains
        echo '<div class="mid-text-line compact-midline"><span>' . $m1['m__cover'] . ' <span class="hidden headlines"><a target="_blank" href="'.view_app_chain(4341).'?chainvoid=0" class="grey card_count_' . $handleid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a></span> <a href="javascript:void(0)" onclick="$(\'.headlines\').toggleClass(\'hidden\')" class="grey">' . $m1['m__title'] . '</a>:</span></div>';
    }

    echo '<div class="row justify-content list-covers">';

    if($handleid1==28956){
        //Legend of how nodes connect:
        echo '<table class="table table-sm maxout center" style="width: 100%; table-layout: fixed; margin-bottom: -144px; margin-top:44px; font-size:1.4em;"><tr>';
        echo '<td style="width: 16.66%; text-align: center;">&nbsp;</td>';
        echo '<td style="width: 16.66%; text-align: center;">&nbsp;</td>';
        echo '<td style="width: 16.66%; text-align: center;"><i class="fas fa-rotate-left appender_32292"></i></td>';
        echo '<td style="width: 16.66%; text-align: center;"><i class="fas fa-rotate-right appender_4486"></i></td>';
        echo '<td style="width: 16.66%; text-align: center;">&nbsp;</td>';
        echo '<td style="width: 16.66%; text-align: center;">&nbsp;</td>';
        echo '</tr><tr>';
        echo '<td style="width: 16.66%; text-align: center;">&nbsp;</td>';
        echo '<td style="width: 16.66%; text-align: center;">&nbsp;</td>';
        echo '<td style="width: 16.66%; text-align: center;"><i class="fas fa-arrow-right appender_13550"></i></td>';
        echo '<td style="width: 16.66%; text-align: center;"><i class="fas fa-arrow-left appender_31777"></i></td>';
        echo '<td style="width: 16.66%; text-align: center;">&nbsp;</td>';
        echo '<td style="width: 16.66%; text-align: center;">&nbsp;</td>';
        echo '</tr></table>';
    }


    foreach ($this->config->item('handles___' . $handleid1) as $handleid2 => $m2) {

        $is_chain = $handleid2 != 12273 && $handleid2 != 12274;

        echo '<div class="card_cover no-padding col-6">';
        echo '<div class="card_frame dropdown_d' . $handleid1 . ' dropdown_' . $handleid2 . '">';

        echo '<div class="card_header" title="' . $m2['m__message'] . '" handleid="' . $handleid2 . '">';
        echo '<div class="'.( $is_chain ? 'medium_cover' : 'large_cover' ).'">' . $m2['m__cover'] . '</div>';
        echo '<div class="main__title large_title"><a target="_blank" href="'.view_app_chain(4341).'?chainhandletype='.join(',',( $is_chain ? $this->config->item('handleids___' . $handleid2) : array(( $handleid2==12273 ? 12273 : 12274 )) )).'&chainvoid=0" class="card_count_' . $handleid2 . '"><i class="fas fa-yin-yang fa-spin"></i></a></div>';
        echo '<div class="main__title large_title" title="@' . $handleid2 . ' @' . $m2['m__handle'] . '"><a href="'.view_memory(42903,42902).$m2['m__handle'].'">' . $m2['m__title'] . '</a></div>';
        echo '</div>';

        if ($is_chain) {
            echo '<table class="table card_subcat card_subcat_' . $handleid2 . ' hidden" style="width:100%; margin-top:13px;">'; //table-striped
            $focus_chain_group = 0;
            $handle_pinned = handle_pinned($handleid2, true);
            if (!$handle_pinned || !is_array($this->config->item('handles___' . $handle_pinned)) || !count($this->config->item('handles___' . $handle_pinned))) {
                continue;
            }
            foreach ($this->config->item('handles___' . $handle_pinned) as $handleid3 => $m3) {
                echo '<tr class="main__title mobile-shrink" title="' . $m3['m__message'] . '" data-toggle="tooltip" data-placement="top">';
                echo '<td style="text-align: left;" title="@' . $handleid3 . ' @' . $m3['m__handle'] . '"><a href="' . view_memory(42903, 42902) . $m3['m__handle'] . '"><span class="icon-block-sm">' . $m3['m__cover'] . '</span>' . $m3['m__title'] . '</a><span class="last-right-col"><a target="_blank" href="'.view_app_chain(4341).'?chainhandletype='.  $handleid3 . '&chainvoid=0" class="card_count_' . $handleid3 . '"><i class="fas fa-yin-yang fa-spin"></i></a></span></td>';
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

    function chain_graph() {
        $.post("/controller/chain_graph", {
            handlestring: '<?= $handlestring ?>',
            hashtagstring: '<?= $hashtagstring ?>',
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
        chain_graph();

        //Watch for click to expand:
        $(".card_header").click(function (e) {
            $('.card_subcat_' + $(this).attr('handleid')).toggleClass('hidden');
        });

        //Update stats live:
        $(function () {
            setInterval(chain_graph, js_handles___6404[33292]['m__message']);
        });

    });

</script>
