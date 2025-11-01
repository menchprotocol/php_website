<?php

$users___11035 = $this->config->item('users___11035'); //Encyclopedia
$chainusercreator = ($user_session ? $user_session['userid'] : 14068);
$target_posthashtag = (count($target_post) && $chainusercreator ? $target_post['posthashtag'] : null);
$at_starting_point = $target_posthashtag==$focus_post['posthashtag'];

//Breadcrump for logged in users NOT at the starting point
$breadcrum_content = null;
if ($chainusercreator && !$at_starting_point) {

    $previous = $this->Chains->previouspost($chainusercreator, $target_posthashtag, $focus_post['postid']);
    if (count($previous)) {

        $nav_list = array();
        $main_branch = array(intval($focus_post['postid']));
        foreach ($previous as $followings_i) {
            //First add-up the main branch:
            array_push($main_branch, intval($followings_i['postid']));
        }

        $level = 0;
        foreach ($previous as $followings_i) {

            $level++;

            //Does this have a follower list?
            $query_subset = $this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___3470452')) . ')' => null, //Post Sequences
                'chainpostinput' => $followings_i['postid'],
            ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC'), '*', null, true);

            $breadcrum_content .= '<li class="breadcrumb-item">';
            $breadcrum_content .= '<a href="' . view_memory(42903, 30795) . $target_posthashtag . '/' . ($followings_i['posthashtag'] == $target_posthashtag ? 'start' : $followings_i['posthashtag']) . '">' . view_post_title($followings_i, true) . '</a>';

            //Do we have more sub-items in this branch? Must have more than 1 to show, otherwise the 1 will be included in the main branch:
            if (count($query_subset) >= 2) {
                //Show other branches:
                $breadcrum_content .= '<div class="dropdown inline-block">';
                $breadcrum_content .= '<button type="button" class="btn no-side-padding" style="margin-top:-3px;" id="dropdown_instant_' . $followings_i['postid'] . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $breadcrum_content .= '<span style="padding-left:5px;"><i class="far fa-sharp fa-chevron-square-up rotate180"></i></span>';
                $breadcrum_content .= '</button>';
                $breadcrum_content .= '<div class="dropdown-menu" aria-labelledby="dropdown_instant_' . $followings_i['postid'] . '">';
                foreach ($query_subset as $post_subset) {

                    if (count($this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                        'chainusercreator' => $chainusercreator,
                        'chainpostinput' => $post_subset['postid'],
                    )))) {
                        $breadcrum_content .= '<a href="' . view_memory(42903, 30795) . $target_posthashtag . '/' . $post_subset['posthashtag'] . '" class="dropdown-item ' . (in_array($post_subset['postid'], $main_branch) ? ' active ' : '') . '">' . view_post_title($post_subset, true) . '</a>';
                    } else {
                        //Locked
                        $breadcrum_content .= '<div class="dropdown-item is_locked ' . (in_array($post_subset['postid'], $main_branch) ? ' active ' : '') . '" title="' . $users___11035[43010]['m__name'] . '" data-toggle="tooltip" data-placement="top"><span class="icon-block-sm">' . $users___11035[43010]['m__cover'] . '</span>' . view_post_title($post_subset, true) . '</div>';
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
if ($user_session) {
    $progress = $this->Chains->progress($chainusercreator, $target_post);
    $target_completed = $progress['fixed_completed_percentage'] >= 100;

    if($target_completed && !count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostinput' => $focus_post['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___43050')) . ')' => null, //Direct Input Ideas
        )))){
        //Hide next navigation and allow them to browse the tree:
        echo '<script> $(document).ready(function () { setTimeout(function () { $(\'.fixed-bottom .card_cards\').addClass(\'hidden\'); }, 233); }); </script>';
    }

    if ($target_completed && $at_starting_point) {
        echo '<div class="alert alert-success" role="alert" title="' . $progress['fixed_total'] . '/' . $progress['fixed_discovered'] . ' ' . $progress['fixed_completed_percentage'] . '% ' . $progress['fixed_discovered'] . ': ' . join(',', $progress['list_discovered']) . '"><span class="icon-block"><i class="far fa-check-circle"></i></span>100% Complete</div>';
    } else {
        echo '<div class="progress">
<div class="progress-bar bg31777" role="progressbar" data-toggle="tooltip" data-placement="top" title="' . $progress['fixed_discovered'] . '/' . $progress['fixed_total'] . ' Posts discovered ' . $progress['fixed_completed_percentage'] . '%" style="width: ' . $progress['fixed_completed_percentage'] . '%" aria-valuenow="' . $progress['fixed_completed_percentage'] . '" aria-valuemin="0" aria-valuemax="100"></div>
</div>';
    }
}

$x_completes = array();
if ($user_session) {
    $x_completes = $this->Chains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
        'chainusercreator' => $chainusercreator,
        'chainpostinput' => $focus_post['postid'],
    ), array('chainpostoutput'));
}


//Focus Discovery:
echo '<div class="row justify-content">';
echo post_view(43007, $focus_post, null, null, 0, $x_completes);
echo '</div>';


//Main Navigation
if ($user_session || isset($_GET['open'])) {
    echo view_post_nav(true, $focus_post, false);
}

//Fetch Post Types:
$focus_post_types = array();
foreach($this->Chains->read(array(
    'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
    'chainpostinput' => $focus_post['postid'],
    'chainuserinput IN (' . join(',', $this->config->item('userids___4737')) . ')' => null, //Post Types
)) as $mention) {
    array_push($focus_post_types, intval($mention['chainuserinput']));
}

?>

<script>

    var total_discoveries = <?= count($x_completes) ?>;
    var focus_post_types = [<?= join(',',$focus_post_types) ?>];

    $(document).ready(function () {

        load_post_menu('Next');

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

            if ( focus_post_types.includes(43758) ) {
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