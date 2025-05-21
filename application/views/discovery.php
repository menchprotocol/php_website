<?php

$sources___11035 = $this->config->item('sources___11035'); //Encyclopedia
$chainsourcecreator = ($source_session ? $source_session['sourceid'] : 0);
$target_ideahashtag = (count($target_i) && $chainsourcecreator ? $target_i['ideahashtag'] : null);
$at_starting_point = $target_ideahashtag==$focus_i['ideahashtag'];

//Breadcrump for logged in users NOT at the starting point...
$breadcrum_content = null;
if ($chainsourcecreator && !$at_starting_point) {

    $previous = $this->Chains->previousidea($chainsourcecreator, $target_ideahashtag, $focus_i['ideaid']);
    if (count($previous)) {

        $nav_list = array();
        $main_branch = array(intval($focus_i['ideaid']));
        foreach ($previous as $followings_i) {
            //First add-up the main branch:
            array_push($main_branch, intval($followings_i['ideaid']));
        }

        $level = 0;
        foreach ($previous as $followings_i) {

            $level++;

            //Does this have a follower list?
            $query_subset = $this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42267')) . ')' => null, //Sequence Down
                'chainidealeft' => $followings_i['ideaid'],
            ), array('chainidearight'), 0, 0, array('chainkey' => 'ASC'), '*', null, true);

            $breadcrum_content .= '<li class="breadcrumb-item">';
            $breadcrum_content .= '<a href="' . view_memory(42903, 30795) . $target_ideahashtag . '/' . ($followings_i['ideahashtag'] == $target_ideahashtag ? 'start' : $followings_i['ideahashtag']) . '">' . view_idea_title($followings_i, true) . '</a>';

            //Do we have more sub-items in this branch? Must have more than 1 to show, otherwise the 1 will be included in the main branch:
            if (count($query_subset) >= 2) {
                //Show other branches:
                $breadcrum_content .= '<div class="dropdown inline-block">';
                $breadcrum_content .= '<button type="button" class="btn no-side-padding" style="margin-top:-3px;" id="dropdown_instant_' . $followings_i['ideaid'] . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $breadcrum_content .= '<span style="padding-left:5px;"><i class="far fa-sharp fa-chevron-square-up rotate180"></i></span>';
                $breadcrum_content .= '</button>';
                $breadcrum_content .= '<div class="dropdown-menu" aria-labelledby="dropdown_instant_' . $followings_i['ideaid'] . '">';
                foreach ($query_subset as $idea_subset) {

                    if (count($this->Chains->read(array(
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                        'chainsourcecreator' => $chainsourcecreator,
                        'chainidealeft' => $idea_subset['ideaid'],
                    )))) {
                        $breadcrum_content .= '<a href="' . view_memory(42903, 30795) . $target_ideahashtag . '/' . $idea_subset['ideahashtag'] . '" class="dropdown-item ' . (in_array($idea_subset['ideaid'], $main_branch) ? ' active ' : '') . '">' . view_idea_title($idea_subset, true) . '</a>';
                    } else {
                        //Locked
                        $breadcrum_content .= '<div class="dropdown-item is_locked ' . (in_array($idea_subset['ideaid'], $main_branch) ? ' active ' : '') . '" title="' . $sources___11035[43010]['m__title'] . '" data-toggle="tooltip" data-placement="top"><span class="icon-block-sm">' . $sources___11035[43010]['m__cover'] . '</span>' . view_idea_title($idea_subset, true) . '</div>';
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
if ($source_session) {
    $progress = $this->Chains->progress($chainsourcecreator, $target_i);
    $target_completed = $progress['fixed_completed_percentage'] >= 100;

    if($target_completed && !in_array($focus_i['ideatype'], $this->config->item('sourceids___43050'))){
        //Hide next navigation and allow them to browse the tree:
        echo '<script> $(document).ready(function () { setTimeout(function () { $(\'.fixed-bottom .card_cards\').addClass(\'hidden\'); }, 233); }); </script>';
    }

    if ($target_completed && $at_starting_point) {
        echo '<div class="alert alert-success" role="alert" title="' . $progress['fixed_total'] . '/' . $progress['fixed_idea_discovered'] . ' ' . $progress['fixed_completed_percentage'] . '% ' . $progress['fixed_idea_discovered'] . ': ' . join(',', $progress['list_idea_discovered']) . '"><span class="icon-block"><i class="far fa-check-circle"></i></span>100% Complete</div>';
    } else {
        echo '<div class="progress">
<div class="progress-bar bg6255" role="progressbar" data-toggle="tooltip" data-placement="top" title="' . $progress['fixed_idea_discovered'] . '/' . $progress['fixed_total'] . ' Ideas idea_discovered ' . $progress['fixed_completed_percentage'] . '%" style="width: ' . $progress['fixed_completed_percentage'] . '%" aria-valuenow="' . $progress['fixed_completed_percentage'] . '" aria-valuemin="0" aria-valuemax="100"></div>
</div>';
    }
}

$x_completes = array();
if ($source_session) {
    $x_completes = $this->Chains->read(array(
        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        'chainsourcecreator' => $chainsourcecreator,
        'chainidealeft' => $focus_i['ideaid'],
    ), array('chainidearight'));
}


//Focus Discovery:
echo '<div class="row justify-content">';
echo idea_view(43007, $focus_i, null, null, 0, $x_completes);
echo '</div>';


//Main Navigation
if ($source_session || isset($_GET['open'])) {
    echo view_idea_nav(true, $focus_i, $x_completes);
}


?>

<script>

    var total_discoveries = <?= count($x_completes) ?>;
    var focus_ideatype = <?= $focus_i['ideatype'] ?>;

    $(document).ready(function () {


        //load_hashtag_menu('Next');

        set_autosize($('.x_write'));

        if (js_sourceids___7712.includes(focus_ideatype)) {
            //Choose
            $('.xtypecounter12840').text('');
            $('.xtypetitle_12840').text(js_sources___7712[focus_ideatype]['m__title'] + ': ');
        }


        //Show percentage progress on next button:
        if (parseInt($('.progress-bar').attr('aria-valuenow')) > 0 && parseInt($('.progress-bar').attr('aria-valuenow')) < 100) {
            $('.idea_discovered_btn').append(' <span title="' + $('.progress-bar').attr('aria-valuenow') + '% Completed" class="small_font inline-block">[' + $('.progress-bar').attr('aria-valuenow') + '% Done]</span>');
        }

        //Detect if no scroll bar, load instantly:
        var scroll_buffer = 233;
        setTimeout(function () {

            if (total_discoveries) {
                $(".fixed-bottom").removeClass('hidden');
            }

            if (focus_ideatype == 43758) {
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