<?php

$e___11035 = $this->config->item('e___11035'); //NAVIGATION

//Messages:
$messages = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 4231, //IDEA NOTES Messages
    'x__right' => $i_focus['i__id'],
), array(), 0, 0, array('x__spectrum' => 'ASC'));


//NEXT IDEAS
$is_next = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'x__left' => $i_focus['i__id'],
), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));


$x__source = ( $member_e ? $member_e['e__id'] : 0 );
$top_i__id = ( $i_top && $this->X_model->ids($x__source, $i_top['i__id']) ? $i_top['i__id'] : 0 );
$x_completes = ($top_i__id ? $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVERY COMPLETE
    'x__source' => $x__source,
    'x__left' => $i_focus['i__id'],
)) : array() );
$in_my_discoveries = ( $top_i__id && $top_i__id==$i_focus['i__id'] );
$top_completed = false; //Assume main intent not yet completed, unless proven otherwise...
$i_type_meet_requirement = in_array($i_focus['i__type'], $this->config->item('n___7309'));
$is_discovarable = true;
$i_stats = i_stats($i_focus['i__metadata']);



?>

<script>
    var focus_i__type = <?= $i_focus['i__type'] ?>;
</script>

<input type="hidden" id="focus__id" value="<?= $i_focus['i__id'] ?>" />
<input type="hidden" id="top_i__id" value="<?= $top_i__id ?>" />
<script src="/application/views/x_layout.js?v=<?= view_memory(6404,11060) ?>" type="text/javascript"></script>

<?php

echo '<div class="container">';

if($top_i__id){


    $is_this = $this->I_model->fetch(array(
        'i__id' => $top_i__id,
    ));
    $i_completion_rate = $this->X_model->completion_progress($x__source, $is_this[0]);
    $top_completed = $i_completion_rate['completion_percentage'] >= 100;



    if($i_type_meet_requirement){

        //Reverse check answers to see if they have previously unlocked a path:
        $unlocked_connections = $this->X_model->fetch(array(
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVERY EXPANSIONS
            'x__right' => $i_focus['i__id'],
            'x__source' => $x__source,
        ), array('x__left'), 1);

        if(count($unlocked_connections) > 0){

            //They previously have unlocked a path here!

            //Determine DISCOVERY COIN type based on it's connection type's parents that will hold the appropriate read coin.
            $x_completion_type_id = 0;
            foreach($this->config->item('e___12327') /* DISCOVERY UNLOCKS */ as $e__id => $m2){
                if(in_array($unlocked_connections[0]['x__type'], $m2['m__profile'])){
                    $x_completion_type_id = $e__id;
                    break;
                }
            }

            //Could we determine the coin type?
            if($x_completion_type_id > 0){

                //Yes, Issue coin:
                array_push($x_completes, $this->X_model->mark_complete($top_i__id, $i_focus, array(
                    'x__type' => $x_completion_type_id,
                    'x__source' => $x__source,
                )));

            } else {

                //Oooops, we could not find it, report bug:
                $this->X_model->create(array(
                    'x__type' => 4246, //Platform Bug Reports
                    'x__source' => $x__source,
                    'x__message' => 'x_layout() found idea connector ['.$unlocked_connections[0]['x__type'].'] without a valid unlock method @12327',
                    'x__left' => $i_focus['i__id'],
                    'x__reference' => $unlocked_connections[0]['x__id'],
                ));

            }

        } else {

            //Try to find paths to unlock:
            $unlock_paths = $this->I_model->unlock_paths($i_focus);

            //Set completion method:
            if(!count($unlock_paths)){

                //No path found:
                array_push($x_completes, $this->X_model->mark_complete($top_i__id, $i_focus, array(
                    'x__type' => 7492, //TERMINATE
                    'x__source' => $x__source,
                )));

            }
        }
    }

    $go_next_url = ( $top_completed ? '/x/x_completed_next/' : '/x/x_next/' ) . $top_i__id . '/' . $i_focus['i__id'];

} else {

    if(i_is_startable($i_focus)){

        //OPEN TO REGISTER
        $go_next_url = '/x/x_start/'.$i_focus['i__id'];

    } else {

        //Try to find the top registrable idea:
        $top_startable = $this->I_model->top_startable($i_focus);
        if(count($top_startable)){

            foreach($top_startable as $start_i){
                //OPEN TO REGISTER
                $is_discovarable = false;
                $go_next_url = '/'.$start_i['i__id'];
                break; //Ignore other possible pathways
            }

        } else {

            $go_next_url = null;

        }

    }

}



//PREVIOUS DISCOVERIES
if($top_i__id){
    echo '<div class="row justify-content-center">';
    foreach($this->X_model->find_previous($member_e['e__id'], $top_i__id, $i_focus['i__id']) as $sitemap_i){
        echo view_i(14450, $top_i__id, null, $sitemap_i);
    }
    echo '</div>';
}



//IDEA TITLE
echo '<h1>' . view_i_title($i_focus) . '<span class="title-editor '.superpower_active(10939).'"><a href="/~'.$i_focus['i__id'].'" title="'.$e___11035[13563]['m__title'].'">'.$e___11035[13563]['m__cover'].'</a></span></h1>';



//MESSAGES
foreach($messages as $message_x) {
    echo $this->X_model->message_view(
        $message_x['x__message'],
        true,
        $member_e
    );
}





$fetch_13865 = $this->X_model->fetch(array(
    'x__right' => $i_focus['i__id'],
    'x__type' => 13865, //PREREQUISITES
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
), array('x__up'), 0);
$meets_13865 = !count($fetch_13865);

if(count($fetch_13865)){

    echo '<div class="headline" style="margin-top: 41px;"><span class="icon-block">&nbsp;</span>'.$e___11035[13865]['m__title'].'</div>';

    $missing_13865 = 0;
    $e___13865 = $this->config->item('e___13865'); //PREREQUISITES
    echo '<div class="list-group" style="margin-bottom: 34px;">';
    foreach($fetch_13865 as $e_pre){

        $meets_this = ($x__source > 0 && count($this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__up' => $e_pre['x__up'],
                'x__down' => $x__source,
            ))));

        $meets_this_id = ( $meets_this ? 13875 : 13876 );

        echo '<div class="list-group-item no-left-padding"><span class="icon-block">'.$e___13865[$meets_this_id]['m__cover'].'</span>'.$e_pre['e__title'].'</div>';

        if(!$meets_this){
            $missing_13865++;
        }

    }
    echo '</div>';
    $meets_13865 = !$missing_13865;
}

/*
if($top_i__id && count($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'x__type' => 4983, //IDEA SOURCES
        'x__up' => 12896, //SAVE THIS IDEA
        'x__right' => $i_focus['i__id'],
    ))) && !count($this->X_model->fetch(array(
        'x__up' => $x__source,
        'x__right' => $i_focus['i__id'],
        'x__type' => 12896, //SAVED
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    )))){

    //Recommended to Save This Idea:
    echo '<div class="msg alert no-margin space-left">Save idea for quick access? <span class="inline-block">Tap <i class="far fa-bookmark black"></i></span></div>';

}
*/


//DISCUSSIONS:
echo '<div class="view-discussions hidden">';
echo '<a name="comment" class="black" style="padding: 10px 0;">&nbsp;</a>';
echo '<div class="headline top-margin"><span class="icon-block">&nbsp;</span>'.$e___11035[12419]['m__title'].'</div>';
$comments = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    'x__type' => 12419,
    'x__right' => $i_focus['i__id'],
), array('x__source'), view_memory(6404,11064), 0, array('x__spectrum' => 'ASC'));
echo view_i_note_list(12419, true, $i_focus, $comments, true, true);
echo '</div>';



if(!$top_i__id){

    $discovery_e = ( $is_discovarable ? 4235 : 14022 );

    //Get Started
    echo '<div class="discover-controller margin-top-down center"><a class="controller-nav btn btn-lrg btn-discover go-next" href="javascript:void(0);" onclick="go_next(\''.$go_next_url.'\')">'.$e___11035[$discovery_e]['m__title'].' '.$e___11035[$discovery_e]['m__cover'].'</a></div>';

} else {

    $buttons_found = 0;
    $buttons_ui = '';

    foreach($this->config->item('e___13289') as $e__id => $m2) {


        $superpower_actives = array_intersect($this->config->item('n___10957'), $m2['m__profile']);
        if(count($superpower_actives) && !superpower_unlocked(end($superpower_actives))){
            continue;
        }

        $control_btn = '';

        if($e__id==13877 && $top_i__id && !$in_my_discoveries){

            //Is Saved already by this member?
            $is_saves = $this->X_model->fetch(array(
                'x__up' => $x__source,
                'x__right' => $i_focus['i__id'],
                'x__type' => 12896, //SAVED
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ));

            $control_btn = '<a class="round-btn save_controller" href="javascript:void(0);" onclick="x_save('.$i_focus['i__id'].')" current_x_id="'.( count($is_saves) ? $is_saves[0]['x__id'] : '0' ).'"><span class="controller-nav toggle_saved '.( count($is_saves) ? '' : 'hidden' ).'">'.$e___11035[12896]['m__cover'].'</span><span class="controller-nav toggle_saved '.( count($is_saves) ? 'hidden' : '' ).'">'.$e___11035[13877]['m__cover'].'</span></a><span class="nav-title css__title">'.$m2['m__title'].'</span>';

        } elseif($e__id==14672){

            //COMMENT
            $control_btn = '<a class="controller-nav round-btn" href="#comment" onclick="load_comments()">'.$m2['m__cover'].'<span class="nav-counter css__title en-type-counter-12419 hideIfEmpty">'.( count($comments) ? count($comments) : '' ).'</span></a><span class="nav-title css__title">'.$m2['m__title'].'</span>';

        } elseif($e__id==12211){

            //NEXT
            $control_btn = '<a class="controller-nav round-btn go-next" href="javascript:void(0);" onclick="go_next(\''.$go_next_url.'\')">'.$m2['m__cover'].'</a><span class="nav-title css__title">'.$m2['m__title'].'</span>';

        }

        $buttons_ui .= '<div>'.( $control_btn ? $control_btn : '&nbsp;' ).'</div>';

        if($control_btn){
            $buttons_found++;
        }

    }

    if($buttons_found > 0){
        echo '<div class="discover-controller margin-top-down">';
        echo $buttons_ui;
        echo '</div>';
    }

}







//IDAS
if($top_i__id) {

    //PREVIOUSLY UNLOCKED:
    $unlocked_x = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        'x__type' => 6140, //DISCOVERY UNLOCK LINK
        'x__source' => $x__source,
        'x__left' => $i_focus['i__id'],
    ), array('x__right'), 0);

    //Did we have any steps unlocked?
    if (count($unlocked_x) > 0) {
        echo view_i_list(13978, $top_i__id, $top_i__id, $i_focus, $unlocked_x, $member_e);
    }


    /*
     *
     * IDEA TYPE INPUT CONTROLLER
     * Now let's show the appropriate
     * inputs that correspond to the
     * idea type that enable the member
     * to move forward.
     *
     * */


    //LOCKED
    if ($i_type_meet_requirement) {

        //Requirement lock
        if (!count($x_completes) && !count($unlocked_connections) && count($unlock_paths)) {

            //List Unlock paths:
            echo view_i_list(13979, $top_i__id, $top_i__id, $i_focus, $unlock_paths, $member_e);

        }

        //List Children if any:
        echo view_i_list(12211, $top_i__id, $top_i__id, $i_focus, $is_next, $member_e);


    } elseif (in_array($i_focus['i__type'], $this->config->item('n___7712'))) {

        //SELECT ANSWER

        //Has no children:
        if (!count($is_next)) {

            //Mark this as complete since there is no child to choose from:
            if (!count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVERY COMPLETE
                'x__source' => $x__source,
                'x__left' => $i_focus['i__id'],
            )))) {

                array_push($x_completes, $this->X_model->mark_complete($top_i__id, $i_focus, array(
                    'x__type' => 4559, //DISCOVERY MESSAGES
                    'x__source' => $x__source,
                )));

            }

        } else {

            //First fetch answers based on correct order:
            $x_selects = array();
            foreach ($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'x__left' => $i_focus['i__id'],
            ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $x) {
                //See if this answer was seleted:
                if (count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVERY IDEA LINK
                    'x__left' => $i_focus['i__id'],
                    'x__right' => $x['i__id'],
                    'x__source' => $x__source,
                )))) {
                    array_push($x_selects, $x);
                }
            }

            if (count($x_selects) > 0) {
                //MODIFY ANSWER
                echo '<div class="edit_select_answer">';

                //List answers:
                echo view_i_list(13980, $top_i__id, $top_i__id, $i_focus, $x_selects, $member_e);

                echo '<div class="doclear">&nbsp;</div>';

                //EDIT ANSWER:
                echo '<div class="margin-top-down btn-five"><a class="btn btn-discover" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');">' . $e___11035[13495]['m__cover'] . ' ' . $e___11035[13495]['m__title'] . '</a></div>';

                echo '<div class="doclear">&nbsp;</div>';

                echo '</div>';
            }


            echo '<div class="edit_select_answer ' . (count($x_selects) > 0 ? 'hidden' : '') . '">';
            echo '<div class="doclear">&nbsp;</div>';

            //HTML:
            $e___4737 = $this->config->item('e___4737'); //Idea Types
            echo '<div class="pull-left headline"><span class="icon-block">&nbsp;</span>'.$e___4737[$i_focus['i__type']]['m__title'].':</div>';


            echo '<div class="doclear">&nbsp;</div>';


            //Open for list to be printed:
            echo '<div class="row justify-content-center top-margin list-answers" i__type="' . $i_focus['i__type'] . '">';


            //List children to choose from:
            foreach ($is_next as $key => $next_i) {

                //Has this been previously selected?
                $previously_selected = count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVERY EXPANSIONS
                    'x__left' => $i_focus['i__id'],
                    'x__right' => $next_i['i__id'],
                    'x__source' => $x__source,
                )));

                echo view_i_select($next_i, $x__source, $previously_selected);

                /*

                echo '<a href="javascript:void(0);" onclick="select_answer(' . $next_i['i__id'] . ')" selection_i__id="' . $next_i['i__id'] . '" class="x_select_' . $next_i['i__id'] . ' answer-item list-group-item itemread no-left-padding">';


                echo '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';
                echo '<td class="icon-block item-selected" style="padding: 0 !important;"><i class="' . ($previously_selected ? 'fas fa-check-circle read' : 'far fa-circle read') . '"></i></td>';

                echo '<td style="width:100%; padding: 0 !important;">';
                echo '<b class="css__title i-url" style="margin-left:0;">' . view_i_title($next_i) . '</b>';
                echo '</td>';

                echo '</tr></table>';

                echo '</a>';

                */
            }


            echo '</div>';




            if (count($x_selects) > 0) {

                //Cancel:
                echo '<div class="inline-block margin-top-down btn-five"><a class="btn btn-discover" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');" title="' . $e___11035[13502]['m__title'] . '">' . $e___11035[13502]['m__cover'] . '</a></div>';

                //Save Answers:
                echo '<div class="inline-block margin-top-down left-half-margin"><a class="btn btn-discover" href="javascript:void(0);" onclick="x_select(\'/x/x_next/'.$top_i__id.'/'.$i_focus['i__id'].'\')">' . $e___11035[13524]['m__title'] . ' ' . $e___11035[13524]['m__cover'] . '</a></div>';

            }

            echo '</div>';

        }

    } elseif (in_array($i_focus['i__type'], $this->config->item('n___4559'))) {

        //DISCOVERY ONLY
        echo view_i_list(12211, $top_i__id, $top_i__id, $i_focus, $is_next, $member_e, ( count($is_next) > 1 ? view_i_time($i_stats, true) : '' ));

    } elseif ($i_focus['i__type'] == 6683) {

        //TEXT RESPONSE
        echo '<div class="headline"><span class="icon-block">&nbsp;</span>'.$e___11035[13980]['m__title'].'</div>';

        //Write `skip` if you prefer not to answer...
        echo '<textarea class="border i_content padded x_input" placeholder="" id="x_reply">' . (count($x_completes) ? trim($x_completes[0]['x__message']) : '') . '</textarea>';

        if (count($x_completes)) {
            //Next Ideas:
            echo view_i_list(12211, $top_i__id, $top_i__id, $i_focus, $is_next, $member_e);
        }

        echo '<script> $(document).ready(function () { set_autosize($(\'#x_reply\')); $(\'#x_reply\').focus(); }); </script>';


    } elseif ($i_focus['i__type'] == 7637) {

        //FILE UPLOAD
        echo '<div class="userUploader">';
        echo '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

        echo '<input class="inputfile" type="file" name="file" id="fileType' . $i_focus['i__type'] . '" />';


        if (count($x_completes)) {

            echo '<div class="file_saving_result">';

            echo '<div class="headline"><span class="icon-block">&nbsp;</span>'.$e___11035[13977]['m__title'].'</div>';

            echo '<div class="previous_answer">' . $this->X_model->message_view($x_completes[0]['x__message'], true) . '</div>';

            echo '</div>';

            //Any child ideas?
            echo view_i_list(12211, $top_i__id, $top_i__id, $i_focus, $is_next, $member_e);

        } else {

            //for when added:
            echo '<div class="file_saving_result"></div>';

        }

        //UPLOAD BUTTON:
        echo '<div class="margin-top-down"><label class="btn btn-discover inline-block" for="fileType' . $i_focus['i__type'] . '" style="margin-left:5px;">' . $e___11035[13572]['m__cover'] . ' ' . $e___11035[13572]['m__title'] . '</label></div>';


        echo '<div class="doclear">&nbsp;</div>';
        echo '</form>';
        echo '</div>';

    }

} else {

    //NEXT IDEAS
    echo view_i_list(12211, $top_i__id, $top_i__id, $i_focus, $is_next, $member_e, ( count($is_next) ? view_i_time($i_stats, true) : '' ));

}





echo '</div>'; //CLOSE CONTAINER

?>