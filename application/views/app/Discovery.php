<?php

$e___11035 = $this->config->item('e___11035'); //Encyclopedia

/*
if(access_level_i($focus_i['i__hashtag'], 0, $focus_i)){
    echo '<div class="alert alert-default" role="alert"><span class="icon-block-sm">'.$e___11035[33286]['m__cover'].'</span>You can edit this idea in <a href="'.view_memory(42903,33286).$focus_i['i__hashtag'].'"><b><u>'.$e___11035[33286]['m__title'].'</u></b></a></div>';
}
*/

$x__player = ( $player_e ? $player_e['e__id'] : 0 );
$target_i__hashtag = ( count($target_i) && $x__player ? $target_i['i__hashtag'] : null );
$can_skip = !count($this->X_model->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
    'x__next' => $focus_i['i__id'],
    'x__following' => 28239, //Required
)));





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
            $breadcrum_content .= '<a href="'.view_memory(42903,30795).$target_i__hashtag.'/'.$followings_i['i__hashtag'].'"><u>'.view_i_title($followings_i).'</u></a>';

            //Do we have more sub-items in this branch? Must have more than 1 to show, otherwise the 1 will be included in the main branch:
            if(count($query_subset) >= 2){
                //Show other branches:
                $breadcrum_content .= '<div class="dropdown inline-block">';
                $breadcrum_content .= '<button type="button" class="btn no-side-padding" id="dropdown_instant_'.$followings_i['i__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $breadcrum_content .= '<span style="padding-left:5px;"><i class="fal fa-chevron-square-down"></i></span>';
                $breadcrum_content .= '</button>';
                $breadcrum_content .= '<div class="dropdown-menu" aria-labelledby="dropdown_instant_'.$followings_i['i__id'].'">';
                foreach ($query_subset as $i_subset) {
                    $breadcrum_content .= '<a href="'.view_memory(42903,30795).$target_i__hashtag.'/'.$i_subset['i__hashtag'].'" class="dropdown-item main__title '.( in_array($i_subset['i__id'], $main_branch) ? ' active ' : '' ).'">'.view_i_title($i_subset).'</a>';
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
$tree_progress = $this->X_model->tree_progress($x__player, $target_i);
$target_completed = $tree_progress['fixed_completed_percentage'] >= 100;
if($target_completed) {
    echo '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>100% Complete</div>';
} else {
    echo '<div style="padding: 0 5px;"><div class="progress">
<div class="progress-bar bg6255" role="progressbar" data-toggle="tooltip" data-placement="top" title="'.$tree_progress['fixed_discovered'].'/'.$tree_progress['fixed_total'].' Ideas Discovered '.$tree_progress['fixed_completed_percentage'].'%" style="width: '.$tree_progress['fixed_completed_percentage'].'%" aria-valuenow="'.$tree_progress['fixed_completed_percentage'].'" aria-valuemin="0" aria-valuemax="100"></div>
</div></div>';
}





//Focus Idea:
echo '<div class="main_item view_6255 row justify-content">';
echo view_card_i(42288, $focus_i);
echo '</div>';








//Discovery Menu
$buttons_ui = '';
foreach($this->config->item('e___13289') as $x__type => $m2) {

    if($x__type==13495){

        //Edit response:
        $control_btn = '<div style="padding-left: 8px;" class="save_toggle_answer"><a class="controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="$(\'.save_toggle_answer\').toggleClass(\'hidden\');">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';

        $control_btn .= '<div style="padding-left: 8px;" class="save_toggle_answer hidden"><a class="controller-nav round-btn main-next" href="javascript:void(0);" onclick="$(\'.save_toggle_answer\').toggleClass(\'hidden\');">'.$e___11035[40639]['m__cover'].'</a><span class="nav-title main__title">'.$e___11035[40639]['m__title'].'</span></div>';

    } elseif($x__type==14422 && $target_completed && in_array($i['i__type'], $this->config->item('n___33532'))){

        //Save Response
        $control_btn = '<div style="padding-left: 8px;"><a class="controller-nav round-btn go-next main-next" href="javascript:void(0);" onclick="go_next()">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';

    } elseif($x__type==31022 && $can_skip && !$target_completed){

        //SKIP
        $control_btn = '<div style="padding-left: 13px;" class="save_toggle_answer"><a class="controller-nav round-btn" href="javascript:void(0);" onclick="x_skip()">'.$m2['m__cover'].'</a><span class="nav-title main__title">'.$m2['m__title'].'</span></div>';

    }

    $buttons_ui .= ( $control_btn ? '<div class="navigate_item navigate_'.$x__type.'">'.$control_btn.'</div>' : '' );

}



if(strlen($buttons_ui)){
    echo '<div class="nav-controller">';
    echo $buttons_ui;
    echo '</div>';
}







echo view_i_nav(true, $focus_i);



?>

<script>
    var focus_i__type = <?= $focus_i['i__type'] ?>;
    var can_skip = <?= intval($can_skip) ?>;
</script>

<script>

    $(document).ready(function () {

        show_more(<?= $focus_i['i__id'] ?>);

        //Auto next a single answer:
        if(!can_skip && js_n___7712.includes(parseInt($('.list-answers').attr('i__type')))){
            //It is, see if it has only 1 option:
            var single_id = 0;
            var answer_count = 0;
            $(".answer-item").each(function () {
                single_id = parseInt($(this).attr('selection_i__id'));
                answer_count++;
            });
            if(answer_count==1){
                //Only 1 option, select and go next only if the user cannot skip:
                select_answer(single_id);
            }
        }

        set_autosize($('.x_write'));

    });


    var is_toggling = false;
    function select_answer(i__id){

        if(is_toggling){
            return false;
        }
        is_toggling = true;

        //Allow answer to be saved/updated:
        var i__type = parseInt($('.list-answers').attr('i__type'));

        //Clear all if single selection:
        var is_single_selection = js_n___33331.includes(i__type);
        if(is_single_selection){
            //Single Selection, clear all previously selected answers, if any:
            $('.answer-item').removeClass('isSelected');
        }

        //Is selected?
        if($('.x_select_'+i__id).hasClass('isSelected')){

            //Previously Selected, delete Multi-selection:
            if(!is_single_selection){
                //Multi Selection
                $('.x_select_'+i__id).removeClass('isSelected');
            }

            is_toggling = false;

        } else {

            //Not selected, select now:
            $('.x_select_'+i__id).addClass('isSelected');

            if(is_single_selection){
                //Auto submit answer since they selected one:
                go_next();
            } else {
                //Flash call to action:
                $(".main-next").fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                is_toggling = false;
            }
        }

    }

    function go_next(){

        var is_logged_in = (js_pl_id > 0);

        //Attempts to go next if no submissions:
        if (is_logged_in && js_n___7712.includes(focus_i__type) && $('.list-answers .answer-item').length){

            //Choose
            return x_select();

        } else if(is_logged_in && js_n___33532.includes(focus_i__type)) {

            //Write

            if(focus_i__type==32603 && !$("#DigitalSignAgreement").is(':checked')){
                if(can_skip){
                    x_skip();
                } else {
                    //Must upload file first:
                    alert('Please agree to terms of service before going next.');
                }
            } else {
                //SUBMIT TEXT RESPONSE:
                return x_write();
            }

        } else if (is_logged_in && js_n___41055.includes(focus_i__type) ) {

            return x_free_ticket();

        } else {

            if (is_logged_in && js_n___34826.includes(focus_i__type) && parseInt($('#target_i__id').val()) > 0) {

                //READ:
                return x_read_only_complete();

            } else {

                //Go Next:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin"></i>');
                js_redirect(GoNext());

            }
        }
    }


    function x_write(){
        $.post("/ajax/x_write", {
            target_i__id:$('#target_i__id').val(),
            i__id:fetch_int_val('#focus__id'),
            x_write:$('.x_write').val(),
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin"></i>');
                js_redirect(GoNext());
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }




    function x_read_only_complete(){
        $.post("/ajax/x_read_only_complete", {
            target_i__id:$('#target_i__id').val(),
            i__id:fetch_int_val('#focus__id'),
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin"></i>');
                js_redirect(GoNext());
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }


    function x_skip(){

        if(!can_skip){
            alert('You cannot skip this');
            return false;
        }

        $.post("/ajax/x_skip", {
            target_i__id:$('#target_i__id').val(),
            i__id:fetch_int_val('#focus__id'),
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin"></i>');
                js_redirect(GoNext());
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }


    function x_free_ticket(){
        var i__id = fetch_int_val('#focus__id');
        $.post("/ajax/x_free_ticket", {
            target_i__id:$('#target_i__id').val(),
            i__id:i__id,
            paypal_quantity:$('.input_ui_'+i__id+' .paypal_quantity').val(),
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin"></i>');
                js_redirect(GoNext());
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }

    function x_select(){

        //Check
        var selection_i__id = [];
        $(".answer-item").each(function () {
            var selection_i__id_this = parseInt($(this).attr('selection_i__id'));
            if ($('.x_select_'+selection_i__id_this).hasClass('isSelected')) {
                selection_i__id.push(selection_i__id_this);
            }
        });


        //Show Loading:
        $.post("/ajax/x_select", {
            focus__id:fetch_int_val('#focus__id'),
            target_i__id:$('#target_i__id').val(),
            selection_i__id:selection_i__id,
            js_request_uri: js_request_uri, //Always append to AJAX Calls
        }, function (data) {
            if (data.status) {
                //Go to redirect message:
                $('.go-next').html('<i class="far fa-yin-yang fa-spin"></i>');
                js_redirect(GoNext());
            } else {
                //Show error:
                alert(data.message);
            }
        });
    }


</script>