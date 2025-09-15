<?php

$handles___11035 = $this->config->item('handles___11035'); //Encyclopedia
$chainhandlecreator = ($handle_session ? $handle_session['handleid'] : 0);
$target_hashtagterm = (count($target_i) && $chainhandlecreator ? $target_i['hashtagterm'] : null);
$at_starting_point = $target_hashtagterm==$focus_i['hashtagterm'];

//Breadcrump for logged in users NOT at the starting point...
$breadcrum_content = null;
if ($chainhandlecreator && !$at_starting_point) {

    $previous = $this->Chains->previoushashtag($chainhandlecreator, $target_hashtagterm, $focus_i['hashtagid']);
    if (count($previous)) {

        $nav_list = array();
        $main_branch = array(intval($focus_i['hashtagid']));
        foreach ($previous as $followings_i) {
            //First add-up the main branch:
            array_push($main_branch, intval($followings_i['hashtagid']));
        }

        $level = 0;
        foreach ($previous as $followings_i) {

            $level++;

            //Does this have a follower list?
            $query_subset = $this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
                'chainhashtaginput' => $followings_i['hashtagid'],
            ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC'), '*', null, true);

            $breadcrum_content .= '<li class="breadcrumb-item">';
            $breadcrum_content .= '<a href="' . view_memory(42903, 30795) . $target_hashtagterm . '/' . ($followings_i['hashtagterm'] == $target_hashtagterm ? 'start' : $followings_i['hashtagterm']) . '">' . view_hashtag_title($followings_i, true) . '</a>';

            //Do we have more sub-items in this branch? Must have more than 1 to show, otherwise the 1 will be included in the main branch:
            if (count($query_subset) >= 2) {
                //Show other branches:
                $breadcrum_content .= '<div class="dropdown inline-block">';
                $breadcrum_content .= '<button type="button" class="btn no-side-padding" style="margin-top:-3px;" id="dropdown_instant_' . $followings_i['hashtagid'] . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $breadcrum_content .= '<span style="padding-left:5px;"><i class="far fa-sharp fa-chevron-square-up rotate180"></i></span>';
                $breadcrum_content .= '</button>';
                $breadcrum_content .= '<div class="dropdown-menu" aria-labelledby="dropdown_instant_' . $followings_i['hashtagid'] . '">';
                foreach ($query_subset as $hashtag_subset) {

                    if (count($this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                        'chainhandlecreator' => $chainhandlecreator,
                        'chainhashtaginput' => $hashtag_subset['hashtagid'],
                    )))) {
                        $breadcrum_content .= '<a href="' . view_memory(42903, 30795) . $target_hashtagterm . '/' . $hashtag_subset['hashtagterm'] . '" class="dropdown-item ' . (in_array($hashtag_subset['hashtagid'], $main_branch) ? ' active ' : '') . '">' . view_hashtag_title($hashtag_subset, true) . '</a>';
                    } else {
                        //Locked
                        $breadcrum_content .= '<div class="dropdown-item is_locked ' . (in_array($hashtag_subset['hashtagid'], $main_branch) ? ' active ' : '') . '" title="' . $handles___11035[43010]['m__title'] . '" data-toggle="tooltip" data-placement="top"><span class="icon-block-sm">' . $handles___11035[43010]['m__cover'] . '</span>' . view_hashtag_title($hashtag_subset, true) . '</div>';
                    }

                }
                $breadcrum_content .= '</div>';
                $breadcrum_content .= '</div>';
            }

            $breadcrum_content .= '</li>';
        }
    }
}
if ($breadcrum_content) {
    //Add blank item to get final arrow:
    $breadcrum_content .= '<li class="breadcrumb-item">&nbsp;</li>';

    echo '<nav aria-label="breadcrumb" style="background-color: #FFFFFF;"><ol class="breadcrumb">';
    echo $breadcrum_content;
    echo '</ol></nav>';
}


//Progress?
if ($handle_session) {
    $progress = $this->Chains->progress($chainhandlecreator, $target_i);
    $target_completed = $progress['fixed_completed_percentage'] >= 100;

    if($target_completed && !count($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $focus_i['hashtagid'],
            'chainhandleinput IN (' . join(',', $this->config->item('handleids___43050')) . ')' => null, //Direct Input Ideas
        )))){
        //Hide next navigation and allow them to browse the tree:
        echo '<script> $(document).ready(function () { setTimeout(function () { $(\'.fixed-bottom .card_cards\').addClass(\'hidden\'); }, 233); }); </script>';
    }

    if ($target_completed && $at_starting_point) {
        echo '<div class="alert alert-success" role="alert" title="' . $progress['fixed_total'] . '/' . $progress['fixed_discovered'] . ' ' . $progress['fixed_completed_percentage'] . '% ' . $progress['fixed_discovered'] . ': ' . join(',', $progress['list_discovered']) . '"><span class="icon-block"><i class="far fa-check-circle"></i></span>100% Complete</div>';
    } else {
        echo '<div class="progress">
<div class="progress-bar bg31777" role="progressbar" data-toggle="tooltip" data-placement="top" title="' . $progress['fixed_discovered'] . '/' . $progress['fixed_total'] . ' Hashtags discovered ' . $progress['fixed_completed_percentage'] . '%" style="width: ' . $progress['fixed_completed_percentage'] . '%" aria-valuenow="' . $progress['fixed_completed_percentage'] . '" aria-valuemin="0" aria-valuemax="100"></div>
</div>';
    }
}

$x_completes = array();
if ($handle_session) {
    $x_completes = $this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
        'chainhandlecreator' => $chainhandlecreator,
        'chainhashtaginput' => $focus_i['hashtagid'],
    ), array('chainhashtagoutput'));
}


//Focus Discovery:
echo '<div class="row justify-content">';
echo hashtag_view(43007, $focus_i, null, null, 0, $x_completes);
echo '</div>';


//Main Navigation
if ($handle_session || isset($_GET['open'])) {
    echo view_hashtag_nav(true, $focus_i, $x_completes);
}

//Fetch Hashtag Types:
$focus_hashtag_types = array();
foreach($this->Chains->read(array(
    'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
    'chainhashtagoutput' => $focus_i['hashtagid'],
    'chainhandleinput IN (' . join(',', $this->config->item('handleids___4737')) . ')' => null, //Hashtag Types
)) as $mention) {
    array_push($focus_hashtag_types, intval($mention['chainhandleinput']));
}

?>

<script>

    var total_discoveries = <?= count($x_completes) ?>;
    var focus_hashtag_types = [<?= join(',',$focus_hashtag_types) ?>];

    $(document).ready(function () {


        load_hashtag_menu('Next');

        set_autosize($('.x_write'));

        //Show percentage progress on next button:
        if (parseInt($('.progress-bar').attr('aria-valuenow')) > 0 && parseInt($('.progress-bar').attr('aria-valuenow')) < 100) {
            $('.discovered_btn').append(' <span title="' + $('.progress-bar').attr('aria-valuenow') + '% Completed" class="small_font inline-block">[' + $('.progress-bar').attr('aria-valuenow') + '% Done]</span>');
        }

        //Detect if no scroll bar, load instantly:
        var scroll_buffer = 233;
        setTimeout(function () {

            if (total_discoveries) {
                $(".fixed-bottom").removeClass('hidden');
            }

            if ( focus_hashtag_types.includes(43758) ) {
                invoice_update();
                $(".fixed-bottom").removeClass('hidden');
            } else {
                if (($(window).height() + scroll_buffer) > $(document).height()) {
                    $(".fixed-bottom").removeClass('hidden');
                } else {
                    //Detect if scroll bar:
                    $(window).scroll(function () {
                        if (($(window).scrollTop() + $(window).height() + scroll_buffer) >= $(document).height()) {
                            $(".fixed-bottom").removeClass('hidden');
                        }
                    });
                }
            }


        }, 1597);

        //Check again just in case:
        setTimeout(function () {
            if (($(window).height() + scroll_buffer) > $(document).height()) {
                $(".fixed-bottom").removeClass('hidden');
            }
        }, 4181);

    });

</script>