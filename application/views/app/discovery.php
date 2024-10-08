<?php

$e___11035 = $this->config->item('e___11035'); //Encyclopedia

/*
if(access_level_i($focus_i['i__hashtag'], 0, $focus_i)){
    echo '<div class="alert alert-default" role="alert"><span class="icon-block-sm">'.$e___11035[33286]['m__cover'].'</span>You can edit this idea in <a href="'.view_memory(42903,33286).$focus_i['i__hashtag'].'"><b><u>'.$e___11035[33286]['m__title'].'</u></b></a></div>';
}
*/

$x__player = ( $player_e ? $player_e['e__id'] : 0 );
$target_i__hashtag = ( count($target_i) && $x__player ? $target_i['i__hashtag'] : null );


//Breadcrump for logged in users NOT at the starting point...
$breadcrum_content = null;
if($x__player && $target_i__hashtag!=$focus_i['i__hashtag']){

    $find_previous = $this->X_model->find_previous($x__player, $target_i__hashtag, $focus_i['i__id']);
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
            $query_subset = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Sequence Down
                'x__previous' => $followings_i['i__id'],
            ), array('x__next'), 0, 0, array('x__weight' => 'ASC'), '*', null, true);

            $breadcrum_content .= '<li class="breadcrumb-item">';
            $breadcrum_content .= '<a href="'.view_memory(42903,30795).$target_i__hashtag.'/'.( $followings_i['i__hashtag']==$target_i__hashtag ? 'start' : $followings_i['i__hashtag'] ).'"><u>'.view_i_title($followings_i, true).'</u></a>';

            //Do we have more sub-items in this branch? Must have more than 1 to show, otherwise the 1 will be included in the main branch:
            if(count($query_subset) >= 2){
                //Show other branches:
                $breadcrum_content .= '<div class="dropdown inline-block">';
                $breadcrum_content .= '<button type="button" class="btn no-side-padding" id="dropdown_instant_'.$followings_i['i__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $breadcrum_content .= '<span style="padding-left:5px;"><i class="far fa-sharp fa-chevron-square-up rotate180"></i></span>';
                $breadcrum_content .= '</button>';
                $breadcrum_content .= '<div class="dropdown-menu" aria-labelledby="dropdown_instant_'.$followings_i['i__id'].'">';
                foreach ($query_subset as $i_subset) {

                    if(count($this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'x__player' => $x__player,
                        'x__previous' => $i_subset['i__id'],
                    )))){
                        $breadcrum_content .= '<a href="'.view_memory(42903,30795).$target_i__hashtag.'/'.$i_subset['i__hashtag'].'" class="dropdown-item '.( in_array($i_subset['i__id'], $main_branch) ? ' active ' : '' ).'">'.view_i_title($i_subset, true).'</a>';
                    } else {
                        //Locked
                        $breadcrum_content .= '<div class="dropdown-item is_locked '.( in_array($i_subset['i__id'], $main_branch) ? ' active ' : '' ).'" title="'.$e___11035[43010]['m__title'].'" data-toggle="tooltip" data-placement="top"><span class="icon-block-sm">'.$e___11035[43010]['m__cover'].'</span>'.view_i_title($i_subset, true).'</div>';
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
    $tree_progress = $this->X_model->tree_progress($x__player, $target_i);
    $target_completed = $tree_progress['fixed_completed_percentage'] >= 100;
    if($target_completed) {
        echo '<div class="alert alert-success" role="alert" title="'.$tree_progress['fixed_total'].'/'.$tree_progress['fixed_discovered'].' '.$tree_progress['fixed_completed_percentage'].'% '.$tree_progress['fixed_discovered'].': '.join(',',$tree_progress['list_discovered']).'"><span class="icon-block"><i class="far fa-check-circle"></i></span>100% Complete</div>';
    } else {
        echo '<div class="progress">
<div class="progress-bar bg6255" role="progressbar" data-toggle="tooltip" data-placement="top" title="'.$tree_progress['fixed_discovered'].'/'.$tree_progress['fixed_total'].' Ideas Discovered '.$tree_progress['fixed_completed_percentage'].'%" style="width: '.$tree_progress['fixed_completed_percentage'].'%" aria-valuenow="'.$tree_progress['fixed_completed_percentage'].'" aria-valuemin="0" aria-valuemax="100"></div>
</div>';
    }
}







//Focus Discovery:
echo '<div class="main_item row justify-content">';
echo view_card_i(43007, $focus_i);
echo '</div>';




//Main Navigation
if($player_e || isset($_GET['open'])){
    echo view_i_nav(true, $focus_i);
}


?>

<script>

    var focus_i__type = <?= $focus_i['i__type'] ?>;

    $(document).ready(function () {

        load_hashtag_menu('Next');

        set_autosize($('.x_write'));

        //Show percentage progress on next button:
        if(parseInt($('.progress-bar').attr('aria-valuenow'))>0 && parseInt($('.progress-bar').attr('aria-valuenow'))<100){
            $('.go_next_btn').append(' <span title="'+$('.progress-bar').attr('aria-valuenow')+'% Completed" style="font-size:0.8em; display: inline-block;">['+$('.progress-bar').attr('aria-valuenow')+'% Done]</span>');
        }

        //Detect if no scroll bar, load instantly:
        var scroll_buffer = 233;
        setTimeout(function () {
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
        }, 1597);

        //Check again just in case:
        setTimeout(function () {
            if (( $(window).height() + scroll_buffer ) > $(document).height()) {
                $(".fixed-bottom").removeClass('hidden');
            }
        }, 4181);

    });

</script>