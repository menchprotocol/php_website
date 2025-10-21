<?php

$userhandle = (isset($_GET['userhandle']) ? $_GET['userhandle'] : null);
$posthashtag = (!$userhandle && isset($_GET['posthashtag']) ? $_GET['posthashtag'] : null);
$users___11035 = $this->config->item('users___11035'); //Encyclopedia

echo '<h2><a href="'.view_app_chain(3445693).'?chainvoid=0"><span class="card_count_4341" style="min-width:111px; display: inline-block; text-align: center;"><i class="fas fa-yin-yang fa-spin"></i></span></a> <a href="/@'.$users___11035[4341]['m__handle'].'">'.$users___11035[4341]['m__cover'].' '.$users___11035[4341]['m__name'].'</a>:</h2>';

if ($userhandle) {
    foreach ($this->Users->read(array(
        'LOWER(userhandle)' => strtolower($userhandle),
    )) as $e) {
        echo '<h2 class="center"><a href="' . view_memory(42903, 42902) . $userhandle . '"><span class="icon-block">' . view_cover($e['usercover']) . '</span> ' . $e['username'] . '</a> <a href="' . view_memory(42903, 33286) . $this->uri->segment(1) . '"><i class="far fa-filter-slash"></i></a></h2>';
    }
} elseif ($posthashtag) {
    foreach ($this->Posts->read(array(
        'LOWER(posthashtag)' => strtolower($posthashtag),
    )) as $i) {
        echo '<h2 class="center"><a href="' . view_memory(42903, 33286) . $posthashtag . '">' . view_post_title($i, true) . '</a> <a href="' . view_memory(42903, 33286) . $this->uri->segment(1) . '"><i class="far fa-filter-slash"></i></a></h2>';
    }
}

//Misc Stats, if any:
echo '<div class="center hideIfEmpty"></div>';

foreach ($this->config->item('users___33292') as $userid1 => $m1) {

    if($userid1==1309754){
        //Voided
        echo '<div class="mid-text-line compact-midline voidstats hidden"><span class="grey"><a href="'.view_app_chain(3445693).'?chainvoid=1" class="card_count_' . $userid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a> <a href="/@'.$m1['m__handle'].'">' . $m1['m__cover'] . ' ' . $m1['m__name'] . '</a></span></div>';
        continue;
    } elseif($userid1==28956){
        //Nodes
        echo '<div class="mid-text-line compact-midline"><span class="grey"><a href="'.view_app_chain(3445693).'?chainusertype=12273,12274&chainvoid=0" class="card_count_' . $userid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a> <a href="/@'.$m1['m__handle'].'">' . $m1['m__cover'] . ' ' . $m1['m__name'] . '</a>:</span></div>';
    } elseif($userid1==31770){
        //Links
        echo '<div class="mid-text-line compact-midline"><span class="grey"><a target="_blank" href="'.view_app_chain(3445693).'?chainusertype='.join(',',$this->config->item('userids___2123863')).'&chainvoid=0" class="card_count_' . $userid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a> <a href="/@'.$m1['m__handle'].'">' . $m1['m__cover'] . ' ' . $m1['m__name'] . '</a>:</span></div>';
    }

    echo '<div class="row justify-content list-covers">';

    foreach ($this->config->item('users___' . $userid1) as $userid2 => $m2) {

        $is_chain = $userid2 != 12273 && $userid2 != 12274;
        $chain_link = view_app_chain(3445693).'?chainusertype='.join(',',( $is_chain ? $this->config->item('userids___' . $userid2) : array(( $userid2==12273 ? 12273 : 12274 )) )).'&chainvoid=0';

        echo '<div class="card_cover no-padding col-6">';
        echo '<div class="card_frame dropdown_d' . $userid1 . ' dropdown_' . $userid2 . '">';

        echo '<div>';

        if ($is_chain) {
            echo '<div class="medium_cover card_header" userid="' . $userid2 . '">' . $m2['m__cover'] . '</div>';
        } else {
            echo '<a href="'.$chain_link.'" class="large_cover" style="cursor: pointer !important;">' . $m2['m__cover'] . '</a>';
        }

        echo '<div class="main__title large_title"><a href="'.$chain_link.'" class="card_count_' . $userid2 . '"><i class="fas fa-yin-yang fa-spin"></i></a></div>';
        echo '<div class="main__title large_title "><a href="'.view_memory(42903,42902).$m2['m__handle'].'" class="dotted_under" data-toggle="tooltip" data-placement="top" title="' . $m2['m__message'] . '">' . $m2['m__name'] . '</a>'.( $is_chain ? '<span class="info-box card_header" userid="' . $userid2 . '"><i class="far fa-plus-circle grey card_subcat_' . $userid2 . '"></i><i class="far fa-minus-circle grey hidden card_subcat_' . $userid2 . '"></i></span>' : '' ).'</div>';
        echo '</div>';

        if ($is_chain) {
            echo '<table class="table card_subcat card_subcat_' . $userid2 . ' hidden" style="width:100%; margin-top:13px;">';
            foreach ($this->config->item('users___' . $userid2) as $userid3 => $m3) {
                echo '<tr class="main__title mobile-shrink">';
                echo '<td style="text-align: left;"><a href="' . view_memory(42903, 42902) . $m3['m__handle'] . '"><span class="icon-block-sm">' . $m3['m__cover'] . '</span><span class="dotted_under" data-toggle="tooltip" data-placement="top" title="' . $m3['m__message'] . '">' . $m3['m__name'] . '</span></a><span class="last-right-col"><a href="'.view_app_chain(3445693).'?chainusertype='.  $userid3 . '&chainvoid=0" class="card_count_' . $userid3 . '"><i class="fas fa-yin-yang fa-spin"></i></a></span></td>';
                echo '</tr>';

            }
            echo '</table>';
        }

        echo '</div>';
        echo '</div>';

    }

    echo '</div>';

}


echo '<a href="javascript:void(0);" onclick="$(\'.voidstats\').toggleClass(\'hidden\')" style="color: #FFFFFF;">SHOW VOID</a>';

?>

<script>

    function chain_stats() {
        $.post("/controller/chain_stats", {
            userhandle: '<?= $userhandle ?>',
            posthashtag: '<?= $posthashtag ?>',
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {

            $.each(data.return_array, function (key, val) {
                var formatted = String(val).replace(/(.)(?=(\d{3})+$)/g, '$1,');
                if (formatted != $(".card_count_" + key + ":first").text()) {
                    $(".card_count_" + key).removeClass('hidden').text(formatted);
                }
            });

        });
    }

    $(document).ready(function () {

        //Load initial stats:
        chain_stats();

        //Watch for click to expand:
        $(".card_header").click(function (e) {
            $('.card_subcat_' + $(this).attr('userid')).toggleClass('hidden');
        });

        //Update stats live:
        $(function () {
            setInterval(chain_stats, js_users___6404[33292]['m__message']);
        });

    });

</script>
