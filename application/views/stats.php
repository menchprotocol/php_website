<?php

$sourcehandle = (isset($_GET['sourcehandle']) ? $_GET['sourcehandle'] : null);
$ideahashtag = (!$sourcehandle && isset($_GET['ideahashtag']) ? $_GET['ideahashtag'] : null);
$sources___11035 = $this->config->item('sources___11035'); //Encyclopedia
$sources___42263 = $this->config->item('sources___42263'); //Chain Groups

if ($sourcehandle) {
    foreach ($this->Sources->read(array(
        'LOWER(sourcehandle)' => strtolower($sourcehandle),
    )) as $e) {
        echo '<h2 class="center"><a href="' . view_memory(42903, 42902) . $sourcehandle . '"><span class="icon-block">' . view_cover($e['sourcecover']) . '</span> ' . $e['sourcevalue'] . '</a> <a href="' . view_memory(42903, 33286) . $this->uri->segment(1) . '"><i class="far fa-filter-slash"></i></a></h2>';
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

foreach ($this->config->item('sources___33292') as $sourceid1 => $m1) {

    if($sourceid1==1309754){
        echo '<div class="mid-text-line compact-midline"><span class="hidden headlines">' . $m1['m__cover'] . ' <a target="_blank" href="'.view_app_chain(4341).'?chainvoid=1" class="grey card_count_' . $sourceid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a> ' . $m1['m__title'] . '</span></div>';
        //Void Chains
        continue;
    } elseif($sourceid1==28956){
        //Nodes
        echo '<div class="mid-text-line compact-midline"><span>' . $m1['m__cover'] . ' <span class="hidden headlines"><a target="_blank" href="'.view_app_chain(4341).'?chainsourcetype=4250,4251&chainvoid=0" class="grey card_count_' . $sourceid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a></span> ' . $m1['m__title'] . ':</span></div>';
    } elseif($sourceid1==31770){

        //Chains
        echo '<div class="mid-text-line compact-midline"><span>' . $m1['m__cover'] . ' <span class="hidden headlines"><a target="_blank" href="'.view_app_chain(4341).'?chainvoid=0" class="grey card_count_' . $sourceid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a></span> <a href="javascript:void(0)" onclick="$(\'.headlines\').toggleClass(\'hidden\')" class="grey">' . $m1['m__title'] . '</a>:</span></div>';
    }

    echo '<div class="row justify-content list-covers">';

    if($sourceid1==28956){
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


    foreach ($this->config->item('sources___' . $sourceid1) as $sourceid2 => $m2) {

        $is_chain = $sourceid2 != 12273 && $sourceid2 != 12274;

        echo '<div class="card_cover no-padding col-6">';
        echo '<div class="card_frame dropdown_d' . $sourceid1 . ' dropdown_' . $sourceid2 . '">';

        echo '<div class="card_header" title="' . $m2['m__message'] . '" sourceid="' . $sourceid2 . '">';
        echo '<div class="'.( $is_chain ? 'medium_cover' : 'large_cover' ).'">' . $m2['m__cover'] . '</div>';
        echo '<div class="main__title large_title"><a target="_blank" href="'.view_app_chain(4341).'?chainsourcetype='.join(',',( $is_chain ? $this->config->item('sourceids___' . $sourceid2) : array(( $sourceid2==12273 ? 4250 : 4251 )) )).'&chainvoid=0" class="card_count_' . $sourceid2 . '"><i class="fas fa-yin-yang fa-spin"></i></a></div>';
        echo '<div class="main__title large_title" title="@' . $sourceid2 . ' @' . $m2['m__handle'] . '"><a href="'.view_memory(42903,42902).$m2['m__handle'].'">' . $m2['m__title'] . '</a></div>';
        echo '</div>';

        if ($is_chain) {
            echo '<table class="table card_subcat card_subcat_' . $sourceid2 . ' hidden" style="width:100%; margin-top:13px;">'; //table-striped
            $focus_chain_group = 0;
            $source_pinned = source_pinned($sourceid2, true);
            if (!$source_pinned || !is_array($this->config->item('sources___' . $source_pinned)) || !count($this->config->item('sources___' . $source_pinned))) {
                continue;
            }
            foreach ($this->config->item('sources___' . $source_pinned) as $sourceid3 => $m3) {

                //Determine chain group:
                foreach(array_intersect($m3['m__following'], $this->config->item('sourceids___42263')) as $headline_chain){
                    if ($headline_chain > 0){
                        if(!$focus_chain_group || $focus_chain_group!=$headline_chain){

                            echo '<tr class="mobile-shrink headlines hidden">';
                            echo '<td class="center" colspan="2" title="@'.$sources___42263[$headline_chain]['m__handle'].'">';

                            //Search for sibling if Has Family:
                            if(in_array($sourceid2, $this->config->item('sourceids___42792'))){
                                foreach($this->Chains->read(array(
                                    'chainsourcedown' => $headline_chain,
                                    'chainsourcetype' => 41011, //Family
                                ), array('chainsourceup'), 1) as $sibling){
                                    echo '<a href="'.view_memory(42903,42902).$sibling['sourcehandle'].'"><span class="icon-block-sm grey">'.view_cover($sibling['sourcecover']).'</span><b class="grey">'.$sibling['sourcevalue'].'</b></a><b class="grey"> & </b></b>';
                                }
                            }

                            echo '<a href="'.view_memory(42903,42902).$sources___42263[$headline_chain]['m__handle'].'"><span class="icon-block-sm grey">'.$sources___42263[$headline_chain]['m__cover'].'</span><b class="grey">'.$sources___42263[$headline_chain]['m__title'].'</a>:</b>';

                            echo '</td>';
                            echo '</tr>';
                            $focus_chain_group = $headline_chain;
                        }
                    }
                }


                echo '<tr class="main__title mobile-shrink" title="' . $m3['m__message'] . '" data-toggle="tooltip" data-placement="top">';
                echo '<td style="text-align: left;" title="@' . $sourceid3 . ' @' . $m3['m__handle'] . '"><a href="' . view_memory(42903, 42902) . $m3['m__handle'] . '"><span class="icon-block-sm">' . $m3['m__cover'] . '</span>' . $m3['m__title'] . '</a><span class="last-right-col"><a target="_blank" href="'.view_app_chain(4341).'?chainsourcetype='.  $sourceid3 . '&chainvoid=0" class="card_count_' . $sourceid3 . '"><i class="fas fa-yin-yang fa-spin"></i></a></span></td>';
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
            sourcehandle: '<?= $sourcehandle ?>',
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
        chain_graph();

        //Watch for click to expand:
        $(".card_header").click(function (e) {
            $('.card_subcat_' + $(this).attr('sourceid')).toggleClass('hidden');
        });

        //Update stats live:
        $(function () {
            setInterval(chain_graph, js_sources___6404[33292]['m__message']);
        });

    });

</script>
