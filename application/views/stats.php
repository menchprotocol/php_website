<?php

$userhandle = (isset($_GET['userhandle']) ? $_GET['userhandle'] : null);
$posthashtag = (!$userhandle && isset($_GET['posthashtag']) ? $_GET['posthashtag'] : null);
$users___11035 = $this->config->item('users___11035'); //Encyclopedia
$users___3470452 = $this->config->item('users___3470452');

echo '<h2><a href="' . view_app_chain(3445693) . '?chainvoid=0"><span class="card_count_4341" style="min-width:111px; display: inline-block; text-align: center;"><i class="fas fa-yin-yang fa-spin"></i></span></a> <a href="/@' . $users___11035[4341]['m__handle'] . '">' . $users___11035[4341]['m__cover'] . ' <span class="dotted_under" data-toggle="tooltip" data-placement="top" title="' . $users___11035[4341]['m__message'] . '">' . $users___11035[4341]['m__name'] . '</span></a>:</h2>';


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

    if ($userid1 == 1309754) {
        //Voided
        echo '<div class="mid-text-line compact-midline voidstats grey"><span><a href="' . view_app_chain(3445693) . '?chainvoid=1" class="card_count_' . $userid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a> <a href="/@' . $m1['m__handle'] . '">' . $m1['m__cover'] . ' <span class="dotted_under" data-toggle="tooltip" data-placement="top" title="' . $m1['m__message'] . '">' . $m1['m__name'] . '</span></a></span></div>';
        continue;
    } elseif ($userid1 == 28956) {
        //Nodes
        echo '<div class="mid-text-line compact-midline"><span class="grey"><a href="' . view_app_chain(3445693) . '?chainusertype=12273,12274&chainvoid=0" class="card_count_' . $userid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a> <a href="/@' . $m1['m__handle'] . '">' . $m1['m__cover'] . ' <span class="dotted_under" data-toggle="tooltip" data-placement="top" title="' . $m1['m__message'] . '">' . $m1['m__name'] . '</span></a>:</span></div>';
    } elseif ($userid1 == 31770) {
        //Links
        echo '<div class="mid-text-line compact-midline"><span class="grey"><a target="_blank" href="' . view_app_chain(3445693) . '?chainusertype=' . join(',', $this->config->item('userids___2123863')) . '&chainvoid=0" class="card_count_' . $userid1 . '"><i class="fas fa-yin-yang fa-spin"></i></a> <a href="/@' . $m1['m__handle'] . '">' . $m1['m__cover'] . ' <span class="dotted_under" data-toggle="tooltip" data-placement="top" title="' . $m1['m__message'] . '">' . $m1['m__name'] . '</span></a>:</span></div>';
    }

    echo '<div class="row justify-content list-covers">';

    foreach ($this->config->item('users___' . $userid1) as $userid2 => $m2) {

        $is_chain = $userid2 != 12273 && $userid2 != 12274;
        $chain_link = view_app_chain(3445693) . '?chainusertype=' . join(',', ($is_chain ? $this->config->item('userids___' . $userid2) : array(($userid2 == 12273 ? 12273 : 12274)))) . '&chainvoid=0';

        if ($userid2 == 12273) {
            //Post
            $focus_list = 4737;
            $focus_id = 12273;
        } elseif ($userid2 == 12274) {
            //User
            $focus_list = 3465306;
            $focus_id = 12274;
        } else {
            //Link
            $focus_list = $userid2;
            $focus_id = $userid2;
        }

        echo '<div class="card_cover no-padding col-6">';
        echo '<div class="card_frame dropdown_d' . $userid1 . ' dropdown_' . $focus_id . '">';

        echo '<div>';

        echo '<div class="' . (!$is_chain ? 'large_cover' : 'medium_cover') . ' card_header" userid="' . $focus_id . '">' . $m2['m__cover'] . '</div>';

        echo '<div class="main__title large_title ' . ($is_chain ? 'grey' : '') . '"><a href="' . $chain_link . '" class="card_count_' . $focus_id . '"><i class="fas fa-yin-yang fa-spin"></i></a></div>';
        echo '<div class="main__title large_title ' . ($is_chain ? 'grey' : '') . '"><a href="' . view_memory(42903, 42902) . $m2['m__handle'] . '" class="dotted_under" data-toggle="tooltip" data-placement="top" title="' . $m2['m__message'] . '">' . $m2['m__name'] . '</a><span class="info-box card_header" userid="' . $focus_id . '"><i class="far fa-plus-circle grey card_subcat_' . $focus_id . '"></i><i class="far fa-minus-circle grey hidden card_subcat_' . $focus_id . '"></i></span></div>';
        echo '</div>';


        echo '<table class="table card_subcat card_subcat_' . $focus_id . ' hidden" style="width:100%; margin-top:13px;">';
        if(!$is_chain){
            //Print Menu:
            echo '<tr class="mobile-shrink voidstats">';
            echo '<td class="grey headline_menu"><a href="' . view_memory(42903, 42902) . $users___11035[$focus_list]['m__handle'] . '" class="dotted_under grey" data-toggle="tooltip" data-placement="top" title="' . $users___11035[$focus_list]['m__message'] . '"><span class="icon-block grey">' . $users___11035[$focus_list]['m__cover'] . '</span>' . $users___11035[$focus_list]['m__name'] . '</a>:</td>';
            echo '</tr>';
        }
        $last_group_displayed = 0;
        foreach ($this->config->item('users___' . $focus_list) as $userid3 => $m3) {

            if ($userid2 == 12273) {
                //Post
                $chainhref = '?chainusertype=' . join(',', $this->config->item('userids___42252')) . '&chainuserinput=' . $userid3 . '&chainvoid=0';
                $chainname = '@' . $m3['m__handle'];
            } elseif ($userid2 == 12274) {
                //User
                $chainhref = '?chainusertype=' . join(',', $this->config->item('userids___13548')) . '&chainuserinput=' . join(',', $this->config->item('userids___' . $userid3)) . '&chainvoid=0';
                $chainname = $m3['m__name'];
            } else {
                //Link
                $chainhref = '?chainusertype=' . $userid3 . '&chainvoid=0';
                $chainname = $m3['m__name'];
            }


            if ($is_chain) {

                //Determine Chain Group:
                $focus_chain_group = 0;
                foreach ($users___3470452 as $userid4 => $m4) {
                    if(in_array($userid3, $this->config->item('userids___'.$userid4))){
                        $focus_chain_group = $userid4;
                        break; //Found it!
                    }
                }


                if($focus_chain_group>0 && (!$last_group_displayed || $last_group_displayed!=$focus_chain_group)){

                    //Update Menu:
                    $last_group_displayed = $focus_chain_group;

                    //Print Menu:
                    echo '<tr class="mobile-shrink voidstats">';
                    echo '<td class="grey headline_menu"><a href="' . view_app_chain(3445693) . '?chainusertype=' . join(',', $this->config->item('userids___'.$last_group_displayed)) . '&chainvoid=0'  . '" class="card_count_' . $focus_chain_group . ' grey"><i class="fas fa-yin-yang fa-spin"></i></a><a href="' . view_memory(42903, 42902) . $users___3470452[$last_group_displayed]['m__handle'] . '"><span class="icon-block grey">' . $users___3470452[$last_group_displayed]['m__cover'] . '</span></a><a href="' . view_memory(42903, 42902) . $users___3470452[$last_group_displayed]['m__handle'] . '" class="dotted_under grey" data-toggle="tooltip" data-placement="top" title="' . $users___3470452[$last_group_displayed]['m__message'] . '">' . $users___3470452[$last_group_displayed]['m__name'] . '</a>:</td>';
                    echo '</tr>';

                }
            }

            echo '<tr class="main__title mobile-shrink ' . (!$is_chain ? 'grey' : '') . '">';
            echo '<td style="text-align: left;"><a href="' . view_memory(42903, 42902) . $m3['m__handle'] . '"><span class="icon-block-sm">' . $m3['m__cover'] . '</span><span class="dotted_under" data-toggle="tooltip" data-placement="top" title="' . $m3['m__message'] . '">' . $chainname . '</span></a><span class="last-right-col"><a href="' . view_app_chain(3445693) . $chainhref . '" class="card_count_' . $userid3 . (!$is_chain ? '_nochain' : '') . '"><i class="fas fa-yin-yang fa-spin"></i></a></span></td>';
            echo '</tr>';

        }
        echo '</table>';

        echo '</div>';
        echo '</div>';

    }

    echo '</div>';

}


echo '<div class="center hidden" style="padding-top: 13px;"><a href="javascript:void(0);" onclick="$(\'.voidstats\').toggleClass(\'hidden\')" style="color: #FFFFFF;">SHOW VOID</a></div>';

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
<style>
    .dotted_under { border-bottom: none !important;}
</style>