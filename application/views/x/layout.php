<script>
    var focus_i__id = <?= $i_focus['i__id'] ?>;
    var focus_i__type = <?= $i_focus['i__type'] ?>;
</script>

<script src="/application/views/x/layout.js?v=<?= view_memory(6404,11060) ?>"
        type="text/javascript"></script>

<?php

echo '<div class="container coin-frame hideIfEmpty">';

$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
$e___13291 = $this->config->item('e___13291'); //DISCOVER TABS
$e___13544 = $this->config->item('e___13544'); //IDEA TREE COUNT



//Determine Focus User:
$user_e = false;
if(isset($_GET['load__e']) && superpower_active(14005, true)){
    //Fetch This User
    $e_filters = $this->E_model->fetch(array(
        'e__id' => $_GET['load__e'],
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(count($e_filters)){
        echo view__load__e($e_filters[0]);
        $user_e = $e_filters[0];
    }
}
if(!$user_e){
    $user_e = superpower_unlocked();
}
if(!isset($user_e['e__id']) ){
    $user_e['e__id'] = 0;
}





//NEXT IDEAS
$is_next = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'x__left' => $i_focus['i__id'],
), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));

//Messages:
$messages = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 4231, //IDEA NOTES Messages
    'x__right' => $i_focus['i__id'],
), array(), 0, 0, array('x__spectrum' => 'ASC'));

$completion_rate['completion_percentage'] = 0;
$u_x_ids = $this->X_model->ids($user_e['e__id']);
$in_my_x = ( $user_e['e__id'] > 0 ? $this->X_model->i_home($i_focus['i__id'], $user_e) : false );
$sitemap_items_raw = array();
$sitemap_items = array();
$i = array(); //Assume main intent not yet completed, unless proven otherwise...
$i_completed = false; //Assume main intent not yet completed, unless proven otherwise...
$i_completion_percentage = 0; //Assume main intent not yet completed, unless proven otherwise...
$i_completion_rate = array();
$in_my_discoveries = in_array($i_focus['i__id'], $u_x_ids);
$previous_level_id = 0; //The ID of the Idea one level up, if any
$superpower_10939 = superpower_active(10939, true);
$x_completes = array();
$i_type_meet_requirement = in_array($i_focus['i__type'], $this->config->item('n___7309'));

if($in_my_x){

    //Fetch Parents all the way to the Discovery Item

    if(!$in_my_discoveries){

        //Find it:
        $top_tree = $this->I_model->recursive_parents($i_focus['i__id'], true, true);

        foreach($top_tree as $grand_parent_ids) {
            foreach(array_intersect($grand_parent_ids, $u_x_ids) as $intersect) {
                foreach($grand_parent_ids as $count => $previous_i__id) {

                    if(filter_array($sitemap_items_raw, 'i__id', $previous_i__id)){
                        //Already There
                        break;
                    }

                    if($count==0){
                        //Reuse the first parent for the back button:
                        $previous_level_id = $previous_i__id;
                    }


                    $is_this = $this->I_model->fetch(array(
                        'i__id' => $previous_i__id,
                    ));

                    $completion_rate = $this->X_model->completion_progress($user_e['e__id'], $is_this[0]);
                    array_push($sitemap_items_raw, array(
                        'i__id' => $previous_i__id,
                        'i' => $is_this[0],
                        'completion_rate' => $completion_rate,
                    ));


                    if(in_array($previous_i__id, $u_x_ids)){
                        //We reached the top-level discovery:
                        $i_completed = $completion_rate['completion_percentage'] >= 100;
                        $i_completion_percentage = $completion_rate['completion_percentage'];
                        $i_completion_rate = $completion_rate;
                        $i = $is_this[0];
                        break;
                    }
                }
            }
        }

        foreach($sitemap_items_raw as $si) {
            array_push($sitemap_items, view_i_cover(6255, $si['i'],  null, false, $si['completion_rate']));
        }

    }
}


if($user_e['e__id']){

    //VIEW DISCOVER
    $this->X_model->create(array(
        'x__source' => $user_e['e__id'],
        'x__type' => 7610, //USER VIEWED IDEA
        'x__left' => $i_focus['i__id'],
        'x__spectrum' => fetch_cookie_order('7610_'.$i_focus['i__id']),
    ));

    if ($in_my_x) {

        //Fetch progress history:
        $x_completes = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVER COMPLETE
            'x__source' => $user_e['e__id'],
            'x__left' => $i_focus['i__id'],
        ));

        //Auto go next?
        if(!count($x_completes) && !count($messages) && count($is_next)<2 && in_array($i_focus['i__type'], $this->config->item('n___12330'))){
            echo '<script> $(document).ready(function () { go_next(\'/x/x_next/\') }); </script>';
        }

        // % DONE
        $completion_rate = $this->X_model->completion_progress($user_e['e__id'], $i_focus);
        if($in_my_discoveries){
            $i_completed = $completion_rate['completion_percentage'] >= 100;
            $i_completion_percentage = $completion_rate['completion_percentage'];
            $i_completion_rate = $completion_rate;
        }


        if($i_type_meet_requirement){

            //Reverse check answers to see if they have previously unlocked a path:
            $unlocked_connections = $this->X_model->fetch(array(
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVER IDEA LINKS
                'x__right' => $i_focus['i__id'],
                'x__source' => $user_e['e__id'],
            ), array('x__left'), 1);

            if(count($unlocked_connections) > 0){

                //They previously have unlocked a path here!

                //Determine DISCOVER COIN type based on it's connection type's parents that will hold the appropriate discover coin.
                $x_completion_type_id = 0;
                foreach($this->config->item('e___12327') /* DISCOVER UNLOCKS */ as $e__id => $m){
                    if(in_array($unlocked_connections[0]['x__type'], $m['m__profile'])){
                        $x_completion_type_id = $e__id;
                        break;
                    }
                }

                //Could we determine the coin type?
                if($x_completion_type_id > 0){

                    //Yes, Issue coin:
                    array_push($x_completes, $this->X_model->mark_complete($i_focus, array(
                        'x__type' => $x_completion_type_id,
                        'x__source' => $user_e['e__id'],
                    )));

                } else {

                    //Oooops, we could not find it, report bug:
                    $this->X_model->create(array(
                        'x__type' => 4246, //Platform Bug Reports
                        'x__source' => $user_e['e__id'],
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
                    array_push($x_completes, $this->X_model->mark_complete($i_focus, array(
                        'x__type' => 7492, //TERMINATE
                        'x__source' => $user_e['e__id'],
                    )));

                }
            }
        }
    }
}


$show_percentage = $completion_rate['completion_percentage']>0 /* && $completion_rate['completion_percentage']<100 */ ;




if($in_my_x && $previous_level_id){
    //Idea Map:
    echo '<div class="row">';
    echo join('', array_reverse($sitemap_items));
    echo '</div>';
}

echo '</div>';





echo '<div class="container wrap-card">';



//HEADER
echo '<h1 class="big-frame">' . view_i_title($i_focus) . '</h1>';


//MESSAGES
echo '<div style="margin-bottom:41px;">';
foreach($messages as $message_x) {
    echo $this->X_model->message_send(
        $message_x['x__message'],
        true,
        $user_e
    );
}
echo '</div>';







$fetch_13865 = $this->X_model->fetch(array(
    'x__right' => $i_focus['i__id'],
    'x__type' => 13865, //PREREQUISITES
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
), array('x__up'), 0);
$meets_13865 = !count($fetch_13865);

if(count($fetch_13865)){

    echo '<div class="headline"><span class="icon-block">'.$e___11035[13865]['m__icon'].'</span>'.$e___11035[13865]['m__title'].'</div>';

    $missing_13865 = 0;
    $e___13865 = $this->config->item('e___13865'); //PREREQUISITES
    echo '<div class="list-group" style="margin-bottom: 34px;">';
    foreach($fetch_13865 as $e_pre){

        $meets_this = ($user_e['e__id'] > 0 && count($this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__up' => $e_pre['x__up'],
            'x__down' => $user_e['e__id'],
        ))));

        $meets_this_id = ( $meets_this ? 13875 : 13876 );

        echo '<div class="list-group-item no-left-padding"><span class="icon-block">'.$e___13865[$meets_this_id]['m__icon'].'</span>'.$e_pre['e__title'].'</div>';

        if(!$meets_this){
            $missing_13865++;
        }

    }
    echo '</div>';
    $meets_13865 = !$missing_13865;
}




//DISCOVER LAYOUT
$i_stats = i_stats($i_focus['i__metadata']);
$tab_group = 13291;
$tab_pills = '<ul class="nav nav-tabs nav-sm nav-discover '.superpower_active(10939).'">';
$tab_content = '';
$tab_pill_count = 0;


if($in_my_x && count($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'x__type' => 4983, //IDEA SOURCES
        'x__up' => 12896, //SAVE THIS IDAE
        'x__right' => $i_focus['i__id'],
    ))) && !count($this->X_model->fetch(array(
        'x__up' => $user_e['e__id'],
        'x__right' => $i_focus['i__id'],
        'x__type' => 12896, //SAVED
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    )))){

    //Recommended to Save This Idea:
    echo '<div class="msg alert no-margin space-left">Save idea for quick access? <span class="inline-block">Tap <i class="far fa-bookmark black"></i></span></div>';

}


foreach($this->config->item('e___'.$tab_group) as $x__type => $m){

    if(!$user_e['e__id'] && in_array($x__type, $this->config->item('n___13304'))){
        //Hide since Not logged in:
        continue;
    }

    //Have Needed Superpowers?
    $superpower_actives = array_intersect($this->config->item('n___10957'), $m['m__profile']);
    if(count($superpower_actives) && !superpower_unlocked(end($superpower_actives))){
        continue;
    }

    //Is this a caret menu?
    if(in_array(11040 , $m['m__profile'])){
        echo view_caret($x__type, $m, $i_focus['i__id']);
        continue;
    }

    $counter = null; //Assume no counters
    $focus_tab = '';
    $href = 'href="javascript:void(0);" onclick="loadtab('.$tab_group.','.$x__type.')"';

    if($x__type==13563){

        if($user_e['e__id']>0 && $superpower_10939 && source_of_e($user_e['e__id'])){
            $href = 'href="/~'.$i_focus['i__id'].'"';
        } else {
            continue;
        }

    } elseif($x__type==12273){

        //IDEAS
        $counter = count($is_next);

        if($in_my_x) {

            //PREVIOUSLY UNLOCKED:
            $unlocked_x = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__type' => 6140, //DISCOVER UNLOCK LINK
                'x__source' => $user_e['e__id'],
                'x__left' => $i_focus['i__id'],
            ), array('x__right'), 0);

            //Did we have any steps unlocked?
            if (count($unlocked_x) > 0) {
                $focus_tab .= view_i_list(13978, $in_my_x, $i_focus, $unlocked_x, $user_e);
            }


            /*
             *
             * IDEA TYPE INPUT CONTROLLER
             * Now let's show the appropriate
             * inputs that correspond to the
             * idea type that enable the user
             * to move forward.
             *
             * */


            //LOCKED
            if ($i_type_meet_requirement) {

                //Requirement lock
                if (!count($x_completes) && !count($unlocked_connections) && count($unlock_paths)) {

                    //List Unlock paths:
                    $focus_tab .= view_i_list(13979, $in_my_x, $i_focus, $unlock_paths, $user_e);

                }

                //List Children if any:
                $focus_tab .= view_i_list(12211, $in_my_x, $i_focus, $is_next, $user_e);


            } elseif (in_array($i_focus['i__type'], $this->config->item('n___7712'))) {

                //SELECT ANSWER

                //Has no children:
                if (!count($is_next)) {

                    //Mark this as complete since there is no child to choose from:
                    if (!count($this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVER COMPLETE
                        'x__source' => $user_e['e__id'],
                        'x__left' => $i_focus['i__id'],
                    )))) {

                        array_push($x_completes, $this->X_model->mark_complete($i_focus, array(
                            'x__type' => 4559, //DISCOVER MESSAGES
                            'x__source' => $user_e['e__id'],
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
                            'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVER IDEA LINK
                            'x__left' => $i_focus['i__id'],
                            'x__right' => $x['i__id'],
                            'x__source' => $user_e['e__id'],
                        )))) {
                            array_push($x_selects, $x);
                        }
                    }

                    if (count($x_selects) > 0) {
                        //MODIFY ANSWER
                        $focus_tab .= '<div class="edit_select_answer">';

                        //List answers:
                        $focus_tab .= view_i_list(13980, $in_my_x, $i_focus, $x_selects, $user_e);

                        $focus_tab .= '<div class="doclear">&nbsp;</div>';

                        //EDIT ANSWER:
                        $focus_tab .= '<div class="margin-top-down"><a class="btn btn-discover" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');">' . $e___11035[13495]['m__icon'] . ' ' . $e___11035[13495]['m__title'] . '</a></div>';

                        $focus_tab .= '<div class="doclear">&nbsp;</div>';

                        $focus_tab .= '</div>';
                    }


                    $focus_tab .= '<div class="edit_select_answer ' . (count($x_selects) > 0 ? 'hidden' : '') . '">';
                    $focus_tab .= '<div class="doclear">&nbsp;</div>';

                    //HTML:
                    if ($i_focus['i__type'] == 6684) {

                        $focus_tab .= '<div class="pull-left headline"><span class="icon-block">'.$e___11035[13981]['m__icon'].'</span>'.$e___11035[13981]['m__title'].'</div>';

                    } elseif ($i_focus['i__type'] == 7231) {


                        $focus_tab .= '<div class="pull-left headline"><span class="icon-block">'.$e___11035[13982]['m__icon'].'</span>'.$e___11035[13982]['m__title'].'</div>';

                        //Give option to Select None/All
                        /*
                        $focus_tab .= '<div class="doclear">&nbsp;</div>';
                        $focus_tab .= '<div class="pull-right right-adj inline-block" data-toggle="tooltip" data-placement="top" title="SELECT ALL OR NONE"><a href="javascript:void(0);" onclick="$(\'.answer-item i\').removeClass(\'far fa-circle\').addClass(\'fas fa-check-circle\');" style="text-decoration: underline;" title="'.$e___11035[13692]['m__title'].'">'.$e___11035[13692]['m__icon'].'</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="$(\'.answer-item i\').removeClass(\'fas fa-check-circle\').addClass(\'far fa-circle\');" style="text-decoration: underline;" title="'.$e___11035[13693]['m__title'].'">'.$e___11035[13693]['m__icon'].'</a></div>';
                        */

                    }

                    $focus_tab .= '<div class="doclear">&nbsp;</div>';


                    //Open for list to be printed:
                    $focus_tab .= '<div class="list-group list-answers" i__type="' . $i_focus['i__type'] . '">';


                    //List children to choose from:
                    $common_prefix = i_calc_common_prefix($is_next, 'i__title');
                    foreach ($is_next as $key => $next_i) {

                        //Has this been previously selected?
                        $previously_selected = count($this->X_model->fetch(array(
                            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVER IDEA LINKS
                            'x__left' => $i_focus['i__id'],
                            'x__right' => $next_i['i__id'],
                            'x__source' => $user_e['e__id'],
                        )));

                        $focus_tab .= '<a href="javascript:void(0);" onclick="select_answer(' . $next_i['i__id'] . ')" selection_i__id="' . $next_i['i__id'] . '" class="x_select_' . $next_i['i__id'] . ' answer-item list-group-item itemdiscover no-left-padding">';


                        $focus_tab .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';
                        $focus_tab .= '<td class="icon-block check-icon" style="padding: 0 !important;"><i class="' . ($previously_selected ? 'fas fa-check-circle discover' : 'far fa-circle discover') . '"></i></td>';

                        $focus_tab .= '<td style="width:100%; padding: 0 !important;">';
                        $focus_tab .= '<b class="montserrat i-url" style="margin-left:0;">' . view_i_title($next_i, $common_prefix) . '</b>';
                        $focus_tab .= '</td>';

                        $focus_tab .= '</tr></table>';


                        $focus_tab .= '</a>';
                    }


                    //Close list:
                    $focus_tab .= '</div>';




                    if (count($x_selects) > 0) {

                        //Cancel:
                        $focus_tab .= '<div class="inline-block margin-top-down"><a class="btn btn-discover" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');" title="' . $e___11035[13502]['m__title'] . '">' . $e___11035[13502]['m__icon'] . '</a></div>';

                        //Save Answers:
                        $focus_tab .= '<div class="inline-block margin-top-down left-half-margin"><a class="btn btn-discover" href="javascript:void(0);" onclick="x_select(\'/x/x_next/\')">' . $e___11035[13524]['m__title'] . ' ' . $e___11035[13524]['m__icon'] . '</a></div>';

                    }

                    $focus_tab .= '</div>';

                }

            } elseif ($i_focus['i__type'] == 6677) {

                //DISCOVER ONLY
                $focus_tab .= view_i_list(12211, $in_my_x, $i_focus, $is_next, $user_e, ( count($is_next) > 1 ? view_i_time($i_stats) : '' ));


            } elseif ($i_focus['i__type'] == 6683) {

                //TEXT RESPONSE
                $focus_tab .= '<div class="headline"><span class="icon-block">'.$e___11035[13980]['m__icon'].'</span>'.$e___11035[13980]['m__title'].'</div>';

                //Write `skip` if you prefer not to answer...
                $focus_tab .= '<textarea class="border i_content padded x_input" placeholder="" id="x_reply">' . (count($x_completes) ? trim($x_completes[0]['x__message']) : '') . '</textarea>';

                if (count($x_completes)) {
                    //Next Ideas:
                    $focus_tab .= view_i_list(12211, $in_my_x, $i_focus, $is_next, $user_e);
                }

                $focus_tab .= '<script> $(document).ready(function () { autosize($(\'#x_reply\')); $(\'#x_reply\').focus(); }); </script>';


            } elseif ($i_focus['i__type'] == 7637) {

                //FILE UPLOAD
                $focus_tab .= '<div class="userUploader">';
                $focus_tab .= '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

                $focus_tab .= '<input class="inputfile" type="file" name="file" id="fileType' . $i_focus['i__type'] . '" />';


                if (count($x_completes)) {

                    $focus_tab .= '<div class="file_saving_result">';

                    $focus_tab .= '<div class="headline"><span class="icon-block">'.$e___11035[13980]['m__icon'].'</span>'.$e___11035[13977]['m__title'].'</div>';

                    $focus_tab .= '<div class="previous_answer">' . $this->X_model->message_send($x_completes[0]['x__message'], true) . '</div>';

                    $focus_tab .= '</div>';

                    //Any child ideas?
                    $focus_tab .= view_i_list(12211, $in_my_x, $i_focus, $is_next, $user_e);

                } else {

                    //for when added:
                    $focus_tab .= '<div class="file_saving_result"></div>';

                }

                //UPLOAD BUTTON:
                $focus_tab .= '<div class="margin-top-down"><label class="btn btn-discover inline-block" for="fileType' . $i_focus['i__type'] . '" style="margin-left:5px;">' . $e___11035[13572]['m__icon'] . ' ' . $e___11035[13572]['m__title'] . '</label></div>';


                $focus_tab .= '<div class="doclear">&nbsp;</div>';
                $focus_tab .= '</form>';
                $focus_tab .= '</div>';

            }

        } else {

            //NEXT IDEAS
            $focus_tab .= view_i_list(12211, $in_my_x, $i_focus, $is_next, $user_e, ( count($is_next) ? view_i_time($i_stats) : '' )); //13542

            //IDEA PREVIOUS
            /*
            $is_previous = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                'x__right' => $i_focus['i__id'],
                'x__left !=' => view_memory(6404,14002),
            ), array('x__left'), 0);
            if(count($is_previous)){
                $focus_tab .= '<div style="padding-top: 34px;">';
                $focus_tab .= view_i_list(12991, $in_my_x, $i_focus, $is_previous, $user_e);
                $focus_tab .= '</div>';
            }
            */

        }

    } elseif($x__type==6255){

        $discovered = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
            'x__left' => $i_focus['i__id'],
        ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

        $counter = view_number($discovered[0]['totals']);

        if($counter > 0){
            $focus_tab .= '<div class="list-group" style="margin-bottom:41px;">';
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
                'x__left' => $i_focus['i__id'],
            ), array('x__source'), view_memory(6404,11064), 0, array( 'x__id' => 'DESC' )) as $discover_e){
                $focus_tab .= view_e($discover_e);
            }
            $focus_tab .= '</div>';
        }

    } elseif( $x__type==12274 || in_array($x__type, $this->config->item('n___4485')) ){

        //NOTES
        $note_x__type = ($x__type==12274 ? 4983 : $x__type );
        $notes = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => $note_x__type,
            'x__right' => $i_focus['i__id'],
        ), array('x__source'), 0, 0, array('x__spectrum' => 'ASC'));
        $counter = count($notes);
        $is_editable = in_array($note_x__type, $this->config->item('n___14043'));
        $focus_tab .= view_i_note_list($note_x__type, $notes, ( $user_e['e__id'] > 0 && $is_editable ), true, false);

    } elseif($x__type==13023){

        $this_url = $this->config->item('base_url').'/'.$i_focus['i__id'];

        foreach($this->config->item('e___13023') as $x__type2 => $m2) {

            if($x__type2==10876){

                //MENCH URL
                $focus_tab .= '<div class="headline"><span class="icon-block">'.$m2['m__icon'].'</span>'.$m2['m__title'].'</div>';
                $focus_tab .= '<div style="margin: 5px 0 41px 41px; cursor: text;">'.$this_url.'</div>';

            } elseif($x__type2==13531){

                //SHARE ON SOCIAL MEDIA
                $focus_tab .= '<div class="headline"><span class="icon-block">'.$m2['m__icon'].'</span>'.$m2['m__title'].'</div>';
                $focus_tab .= '<div class="share-this space-content" style="margin:5px 0 41px;">';
                foreach($this->config->item('e___13531') as $m2) {
                    $focus_tab .= '<div class="icon-block"><div data-network="'.$m2['m__message'].'" data-url="'.$this_url.'" data-title="'.$i_focus['i__title'].'" class="st-custom-button" title="Share with '.$m2['m__title'].'" data-toggle="tooltip" data-placement="top">'.$m2['m__icon'].'</div></div>';
                }
                $focus_tab .= '</div>';

            }
        }

    }


    if(!$counter && in_array($x__type, $this->config->item('n___13298'))){
        //Hide since Zero count:
        continue;
    }

    $default_active = ( in_array($x__type, $this->config->item('n___13300')) );
    $tab_pill_count++;


    $tab_pills .= '<li class="nav-item'.( in_array($x__type, $this->config->item('n___14103')) ? ' pull-right ' : '' ).'"><a '.$href.' class="nav-x tab-nav-'.$tab_group.' tab-head-'.$x__type.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m['m__icon']).'" title="'.$m['m__title'].( strlen($m['m__message']) ? ' '.$m['m__message'] : '' ).'" data-toggle="tooltip" data-placement="top">&nbsp;'.$m['m__icon'].'&nbsp;'.( !$counter ? '' : '<span class="en-type-counter-'.$x__type.'">'.$counter.'</span>&nbsp;' ).'</a></li>';


    $tab_content .= '<div class="tab-content tab-group-'.$tab_group.' tab-data-'.$x__type.( $default_active ? '' : ' hidden ' ).'">';
    $tab_content .= $focus_tab;
    $tab_content .= '</div>';

}
$tab_pills .= '</ul>';




if($tab_pill_count > 1){
    //DISCOVER TABS
    echo $tab_pills;
}

//Show All Tab Content:
echo $tab_content;

echo '</div>'; //CLOSE CONTAINER




if($in_my_x){

    $buttons_found = 0;
    $buttons_ui = '';

    foreach($this->config->item('e___13289') as $e__id => $m) {


        $superpower_actives = array_intersect($this->config->item('n___10957'), $m['m__profile']);
        if(count($superpower_actives) && !superpower_unlocked(end($superpower_actives))){
            continue;
        }

        $control_btn = '';

        if($e__id==13877 && count($sitemap_items)){

            //Is Saved already by this user?
            $is_saved = count($this->X_model->fetch(array(
                'x__up' => $user_e['e__id'],
                'x__right' => $i_focus['i__id'],
                'x__type' => 12896, //SAVED
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )));

            $control_btn = '<a class="round-btn" href="javascript:void(0);" onclick="x_save('.$i_focus['i__id'].')"><span class="controller-nav toggle_saved '.( $is_saved ? '' : 'hidden' ).'">'.$e___11035[12896]['m__icon'].'</span><span class="controller-nav toggle_saved '.( $is_saved ? 'hidden' : '' ).'">'.$e___11035[12906]['m__icon'].'</span></a><span class="nav-title">'.$m['m__title'].'</span>';

        } elseif($e__id==12991 && count($sitemap_items)){

            //BACK
            $control_btn = '<a class="controller-nav round-btn" href="'.( isset($_GET['previous_x']) && $_GET['previous_x']>0 ? '/'.$_GET['previous_x'] : ( $previous_level_id > 0 ? '/x/x_previous/'.$previous_level_id.'/'.$i_focus['i__id'] : home_url() ) ).'">'.$m['m__icon'].'</a><span class="nav-title">'.$m['m__title'].'</span>';

        } elseif($e__id==13563){

            //EDIT
            $control_btn = '<a class="controller-nav round-btn" href="/~'.$i_focus['i__id'].'">'.$m['m__icon'].'</a><span class="nav-title">'.$m['m__title'].'</span>';

        } elseif($e__id==12211){

            //NEXT
            $control_btn = '<a class="controller-nav round-btn" href="javascript:void(0);" onclick="go_next(\''.($i_completed ? '/x/i_next/' : '/x/x_next/').'\')">'.$m['m__icon'].'</a><span class="nav-title">'.$m['m__title'].'</span>';

        }

        $buttons_ui .= '<div>'.( $control_btn ? $control_btn : '&nbsp;' ).'</div>';

        if($control_btn){
            $buttons_found++;
        }

    }

    if($buttons_found > 0){
        echo '<div class="fixed-bottom">';
        echo '<div class="container">';
        echo '<div class="row">';
        echo '<div class="discover-controller">';
        echo $buttons_ui;
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo view_x_progress($i_completion_rate, $i_focus);
        echo '</div>';
    }

} else {

    echo '<div class="container">'; // fixed-bottom
    echo '<div class="margin-top-down center">';

    //GET STARTED
    if($meets_13865){

        if(i_is_startable($i_focus)){

            //OPEN TO REGISTER
            echo '<a class="btn btn-lrg btn-discover" href="/x/x_start/'.$i_focus['i__id'].'">'.$e___11035[4235]['m__title'].' '.$e___11035[4235]['m__icon'].'</a>';

        } else {

            //Try to find the top registrable idea:
            $top_startable = $this->I_model->top_startable($i_focus);
            if(count($top_startable)){

                foreach($top_startable as $start_i){
                    //Give link to go to top:
                    echo '<div class="bottom-margin"><a class="btn btn-lrg btn-discover" href="/'.$start_i['i__id'].'">'.$start_i['i__title'].' '.$e___11035[14022]['m__icon'].'</a></div>';
                }

            } else {

                //Inform them that nothing was found:
                echo '<div style="text-align:center;"><div class="montserrat '.extract_icon_color($e___11035[14023]['m__title']).'">'.$e___11035[14023]['m__icon'].' '.$e___11035[14023]['m__title'].'</div><div>'.$e___11035[14023]['m__message'].'</div></div>';

            }
        }



    } elseif(!$user_e['e__id']) {

        //Signin to see if they meet requirement:
        echo '<a class="btn btn-lrg btn-source" href="/signin">'.$e___11035[4269]['m__title'].' '.$e___11035[4269]['m__icon'].'</a>';

    } else {

        //Locked to meet requirements...
        echo '<div style="text-align:center;">You must meet all requirements</div>';

        //TODO show message...

    }

    echo '</div>';
    echo '</div>';

}



//ADD GIF MODAL
$this->load->view('i/giphy');


?>