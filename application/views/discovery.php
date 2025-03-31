<?php

$e___11035 = $this->config->item('e___11035'); //Encyclopedia

/*
if(access_level_i($focus_i['i__hashtag'], 0, $focus_i)){
    echo '<div class="alert alert-default" role="alert"><span class="icon-block-sm">'.$e___11035[33286]['m__cover'].'</span>You can edit this idea in <a href="'.view__memory(42903,33286).$focus_i['i__hashtag'].'"><b><u>'.$e___11035[33286]['m__title'].'</u></b></a></div>';
}
*/

$link_player = ( $player_e ? $player_e['e__id'] : 0 );
$target_i__hashtag = ( count($target_i) && $link_player ? $target_i['i__hashtag'] : null );


//Breadcrump for logged in users NOT at the starting point...
$breadcrum_content = null;
if($link_player && $target_i__hashtag!=$focus_i['i__hashtag']){

    $find_previous = $this->Mench_ledger->find_previous($link_player, $target_i__hashtag, $focus_i['i__id']);
    if(count($find_previous)){

        $nav_list = array();
        $main_branch = array(intval($focus_i['i__id']));
        foreach($find_previous as $followings_i){
            //First add-up the main branch:
            array_push($main_branch, intval($followings_i['i__id']));
        }

        $level = 0;
        foreach($find_previous as $followings_i){

            $level++;

            //Does this have a follower list?
            $query_subset = $this->Mench_ledger->fetch(array(
                'link_void' => 0, //Not Void
                'link_type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Sequence Down
                'link_left' => $followings_i['i__id'],
            ), array('link_right'), 0, 0, array('link_number' => 'ASC'), '*', null, true);

            $breadcrum_content .= '<li class="breadcrumb-item">';
            $breadcrum_content .= '<a href="'.view__memory(42903,30795).$target_i__hashtag.'/'.( $followings_i['i__hashtag']==$target_i__hashtag ? 'start' : $followings_i['i__hashtag'] ).'"><u>'.view__i_title($followings_i, true).'</u></a>';

            //Do we have more sub-items in this branch? Must have more than 1 to show, otherwise the 1 will be included in the main branch:
            if(count($query_subset) >= 2){
                //Show other branches:
                $breadcrum_content .= '<div class="dropdown inline-block">';
                $breadcrum_content .= '<button type="button" class="btn no-side-padding" id="dropdown_instant_'.$followings_i['i__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $breadcrum_content .= '<span style="padding-left:5px;"><i class="far fa-sharp fa-chevron-square-up rotate180"></i></span>';
                $breadcrum_content .= '</button>';
                $breadcrum_content .= '<div class="dropdown-menu" aria-labelledby="dropdown_instant_'.$followings_i['i__id'].'">';
                foreach ($query_subset as $i_subset) {

                    if(count($this->Mench_ledger->fetch(array(
                        'link_void' => 0, //Not Void
                        'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                        'link_player' => $link_player,
                        'link_left' => $i_subset['i__id'],
                    )))){
                        $breadcrum_content .= '<a href="'.view__memory(42903,30795).$target_i__hashtag.'/'.$i_subset['i__hashtag'].'" class="dropdown-item '.( in_array($i_subset['i__id'], $main_branch) ? ' active ' : '' ).'">'.view__i_title($i_subset, true).'</a>';
                    } else {
                        //Locked
                        $breadcrum_content .= '<div class="dropdown-item is_locked '.( in_array($i_subset['i__id'], $main_branch) ? ' active ' : '' ).'" title="'.$e___11035[43010]['m__title'].'" data-toggle="tooltip" data-placement="top"><span class="icon-block-sm">'.$e___11035[43010]['m__cover'].'</span>'.view__i_title($i_subset, true).'</div>';
                    }

                }
                $breadcrum_content .= '</div>';
                $breadcrum_content .= '</div>';
            }

            $breadcrum_content .= '</li>';
        }
    }
}
if($breadcrum_content){
    //Add blank item to get final arrow:
    $breadcrum_content .= '<li class="breadcrumb-item">&nbsp;</li>';

    echo '<nav aria-label="breadcrumb" style="background-color: #FFFFFF;"><ol class="breadcrumb">';
    echo $breadcrum_content;
    echo '</ol></nav>';
}






//Progress?
if($player_e){
    $tree_progress = $this->Mench_ledger->tree_progress($link_player, $target_i);
    $target_completed = $tree_progress['fixed_completed_percentage'] >= 100;
    if($target_completed) {
        echo '<div class="alert alert-success" role="alert" title="'.$tree_progress['fixed_total'].'/'.$tree_progress['fixed_discovered'].' '.$tree_progress['fixed_completed_percentage'].'% '.$tree_progress['fixed_discovered'].': '.join(',',$tree_progress['list_discovered']).'"><span class="icon-block"><i class="far fa-check-circle"></i></span>100% Complete</div>';
        //Hide next navigation and allow them to browse the tree:
        echo '<script> $(document).ready(function () { setTimeout(function () { $(\'.fixed-bottom .card_covers\').addClass(\'hidden\'); }, 233); }); </script>';
    } else {
        echo '<div class="progress">
<div class="progress-bar bg6255" role="progressbar" data-toggle="tooltip" data-placement="top" title="'.$tree_progress['fixed_discovered'].'/'.$tree_progress['fixed_total'].' Ideas Discovered '.$tree_progress['fixed_completed_percentage'].'%" style="width: '.$tree_progress['fixed_completed_percentage'].'%" aria-valuenow="'.$tree_progress['fixed_completed_percentage'].'" aria-valuemin="0" aria-valuemax="100"></div>
</div>';
    }
}

$x_completes = array();
if($player_e){
    $x_completes = $this->Mench_ledger->fetch(array(
        'link_void' => 0, //Not Void
        'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        'link_player' => $link_player,
        'link_left' => $focus_i['i__id'],
    ), array('link_right'));
}





//Focus Discovery:
echo '<div class="row justify-content">';
echo view__card_i(43007, $focus_i, null, null, 0, $x_completes);
echo '</div>';


//Main Navigation
if($player_e || isset($_GET['open'])){
    echo view__i_nav(true, $focus_i, $x_completes);
}

?>

<script>

    var total_discoveries = <?= count($x_completes) ?>;
    var focus_i__type = <?= $focus_i['i__type'] ?>;

    $(document).ready(function () {

        load_hashtag_menu('Next');

        set_autosize($('.x_write'));

        if (js_n___7712.includes(focus_i__type)){
            //Choose
            $('.xtypecounter12840').text('');
            $('.xtypetitle_12840').text(js_e___7712[focus_i__type]['m__title']+': ');
        }


        //Show percentage progress on next button:
        if(parseInt($('.progress-bar').attr('aria-valuenow'))>0 && parseInt($('.progress-bar').attr('aria-valuenow'))<100){
            $('.go_next_btn').append(' <span title="'+$('.progress-bar').attr('aria-valuenow')+'% Completed" class="small_font inline-block">['+$('.progress-bar').attr('aria-valuenow')+'% Done]</span>');
        }

        //Detect if no scroll bar, load instantly:
        var scroll_buffer = 233;
        setTimeout(function () {

            if(total_discoveries){
                $(".fixed-bottom").removeClass('hidden');
            }

            if(focus_i__type==43758){
                invoice_update();
                $(".fixed-bottom").removeClass('hidden');
            } else {
                if (( $(window).height() + scroll_buffer ) > $(document).height()) {
                    $(".fixed-bottom").removeClass('hidden');
                } else {
                    //Detect if scroll bar:
                    $(window).scroll(function() {
                        if(($(window).scrollTop() + $(window).height() + scroll_buffer) >= $(document).height()) {
                            $(".fixed-bottom").removeClass('hidden');
                        }
                    });
                }
            }


        }, 1597);

        //Check again just in case:
        setTimeout(function () {
            if (( $(window).height() + scroll_buffer ) > $(document).height()) {
                $(".fixed-bottom").removeClass('hidden');
            }
        }, 4181);

    });

</script>