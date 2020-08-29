<script>
    var focus_i__id = <?= $i_focus['i__id'] ?>;
    var focus_i__type = <?= $i_focus['i__type'] ?>;
</script>

<style>
    .add_notes_12419{
        display: none;
    }
</style>

<script src="/application/views/x/layout.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>

<?php

echo '<div class="container">';

$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
$e___13291 = $this->config->item('e___13291'); //DISCOVER TABS
$e___13544 = $this->config->item('e___13544'); //IDEA TREE COUNT

$show_nav = /* superpower_active(10939, true) || */ in_array($i_focus['i__status'], $this->config->item('n___12138'));
$x_completes = array();
$i_type_meet_requirement = in_array($i_focus['i__type'], $this->config->item('n___7309'));

//Determine Forcus User:
$user_e = false;
if(isset($_GET['focus__e']) && superpower_active(12701, true)){
    //Fetch This User
    $e_filters = $this->E_model->fetch(array(
        'e__id' => $_GET['focus__e'],
        'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(count($e_filters)){
        echo view__focus__e($e_filters[0]);
        $user_e = $e_filters[0];
    }
}
if(!$user_e){
    $user_e = superpower_assigned();
}
if(!isset($user_e['e__id']) ){
    $user_e['e__id'] = 0;
}




//NEXT IDEAS
$is_next = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
    'x__left' => $i_focus['i__id'],
), array('x__right'), 0, 0, array('x__sort' => 'ASC'));

//Messages:
$messages = $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 4231, //IDEA NOTES Messages
    'x__right' => $i_focus['i__id'],
), array(), 0, 0, array('x__sort' => 'ASC'));

$chapters = count($is_next);
$completion_rate['completion_percentage'] = 0;
$u_x_ids = $this->X_model->ids($user_e['e__id']);
$in_my_x = ( $user_e['e__id'] ? $this->X_model->i_home($i_focus['i__id'], $user_e) : false );
$sitemap_items = array();
$i_completed = false; //Assume main intent not yet completed, unless proven otherwise...
$i_completion_percentage = 0; //Assume main intent not yet completed, unless proven otherwise...
$in_my_discoveries = in_array($i_focus['i__id'], $u_x_ids);
$previous_level_id = 0; //The ID of the Idea one level up, if any


if($in_my_x){

    //Fetch Parents all the way to the Discovery Item

    if(!$in_my_discoveries){

        //Find it:
        $recursive_parents = $this->I_model->recursive_parents($i_focus['i__id'], true, true);

        foreach($recursive_parents as $grand_parent_ids) {
            foreach(array_intersect($grand_parent_ids, $u_x_ids) as $intersect) {
                foreach($grand_parent_ids as $count => $previous_i__id) {

                    if($count==0){
                        //Reuser the first parent for the back button:
                        $previous_level_id = $previous_i__id;
                    }


                    $is_this = $this->I_model->fetch(array(
                        'i__id' => $previous_i__id,
                    ));

                    $completion_rate = $this->X_model->completion_progress($user_e['e__id'], $is_this[0]);

                    array_push($sitemap_items, view_i_x($is_this[0], -1 /* Unlocked */, true, null, $completion_rate));

                    if(in_array($previous_i__id, $u_x_ids)){
                        //We reached the top-level discovery:
                        $i_completed = $completion_rate['completion_percentage'] >= 100;
                        $i_completion_percentage = $completion_rate['completion_percentage'];
                        break;
                    }
                }
            }
        }
    }
}


if($user_e['e__id']){

    //VIEW DISCOVER
    $this->X_model->create(array(
        'x__source' => $user_e['e__id'],
        'x__type' => 7610, //USER VIEWED IDEA
        'x__left' => $i_focus['i__id'],
        'x__sort' => fetch_cookie_order('7610_'.$i_focus['i__id']),
    ));

    if ($in_my_x) {

        //Fetch progress history:
        $x_completes = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVER COMPLETE
            'x__source' => $user_e['e__id'],
            'x__left' => $i_focus['i__id'],
        ));

        //No message, so automatically mark as read:
        if(!count($x_completes) && !count($messages) && in_array($i_focus['i__type'], $this->config->item('n___12211'))){
            array_push($x_completes, $this->X_model->mark_complete($i_focus, array(
                'x__type' => 4559, //DISCOVER MESSAGES
                'x__source' => $user_e['e__id'],
            )));
        }

        // % DONE
        $completion_rate = $this->X_model->completion_progress($user_e['e__id'], $i_focus);
        if($in_my_discoveries){
            $i_completed = $completion_rate['completion_percentage'] >= 100;
            $i_completion_percentage = $completion_rate['completion_percentage'];
        }


        if($i_type_meet_requirement){

            //Reverse check answers to see if they have previously unlocked a path:
            $unlocked_connections = $this->X_model->fetch(array(
                'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
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
                    if(in_array($unlocked_connections[0]['x__type'], $m['m_profile'])){
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
                        'x__message' => 'x_coin() found idea connector ['.$unlocked_connections[0]['x__type'].'] without a valid unlock method @12327',
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


$show_percentage = $completion_rate['completion_percentage']>0 && $completion_rate['completion_percentage']<100;


if($previous_level_id){
    //Idea Map:
    echo '<div class="list-group">';
    echo join('', array_reverse($sitemap_items));
    echo '</div>';
}


//HEADER
echo '<div style="position: relative; display: block;">' . ( $show_percentage ? '<div class="progress-bg-list no-horizonal-margin" title="Discovered '.$completion_rate['steps_completed'].' of '.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)" data-toggle="tooltip" data-placement="bottom"><span class="progress-connector"></span><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>' : '' ) . '<h1 class="block-one"><span class="icon-block top-icon '.( $show_percentage ? '' : ' thin-top ' ).'">'.view_icon_i_x( $completion_rate['completion_percentage'], $i_focus, 13757 /* Current Idea */ ).'</span><span class="title-block-lg '.( $show_percentage ? ' title-block-squeeze ' : '' ).'">' . view_i_title($i_focus) . '</span></h1>'.'</div>';


//Have they completed this?
if($i_completion_percentage>=100 && !count($sitemap_items)){
    //Recommend to go next:
    echo '<div class="alert no-margin"><span class="icon-block"><i class="fas fa-check-circle"></i></span>You have successfully discovered all ideas</div>';
}


//MESSAGES
echo '<div style="margin-bottom:33px;">';
foreach($messages as $message_x) {
    echo $this->X_model->message_send(
        $message_x['x__message'],
        $user_e
    );
}
echo '</div>';









//DISCOVER LAYOUT
$show_next_tip = $in_my_x /* && !count($sitemap_items) */ && !$i_completed;
$i_stats = i_stats($i_focus['i__metadata']);
$tab_group = 13291;
$tab_pills = '<ul class="nav nav-tabs nav-sm">';
$tab_content = '';
$tab_pill_count = 0;




if(!$in_my_x){

    //GET STARTED
    echo '<div class="margin-top-down left-margin"><a class="btn btn-discover" href="/x/x_start/'.$i_focus['i__id'].'">'.$e___11035[4235]['m_icon'].' '.$e___11035[4235]['m_title'].'</a></div>';

}

if(!$show_next_tip && count($this->X_model->fetch(array(
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
    echo '<div class="alert no-margin space-left">To save this idea for easy future access, tap<span class="icon-block-xs"><i class="far fa-bookmark discover"></i></span></div>';

}


foreach($this->config->item('e___'.$tab_group) as $x__type => $m){

    if(!$user_e['e__id'] && in_array($x__type, $this->config->item('n___13304'))){
        //Hide since Not logged in:
        continue;
    }

    //Have Needed Superpowers?
    $superpower_actives = array_intersect($this->config->item('n___10957'), $m['m_profile']);
    if(count($superpower_actives) && !superpower_assigned(end($superpower_actives))){
        continue;
    }

    //Is this a caret menu?
    if(in_array(11040 , $m['m_profile'])){
        echo view_caret($x__type, $m, $i_focus['i__id']);
        continue;
    }

    $pre_fix = null;
    $counter = null; //Assume no counters
    $focus_tab = '';
    $href = 'href="javascript:void(0);" onclick="loadtab('.$tab_group.','.$x__type.')"';

    if($x__type==13563){

        if($user_e['e__id']>0 && superpower_active(10939, true) && source_of_e($user_e['e__id'])){
            $href = 'href="/~'.$i_focus['i__id'].'"';
        } else {
            continue;
        }

    } elseif($x__type==12273){

        //IDEAS
        $counter = ( count($is_next) > $i_stats['i___6170'] ? count($is_next) : $i_stats['i___6170'] );

        if($counter > $i_stats['i___6169'] && $i_stats['i___6169'] > 0){
            $pre_fix = view_number($i_stats['i___6169']).'<span class="mid-range">-</span>';
        }

        //Now we can make it look nice:
        $counter = view_number($counter);
        $has_substance = false;

        if($in_my_x) {

            //PREVIOUSLY UNLOCKED:
            $unlocked_x = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__type' => 6140, //DISCOVER UNLOCK LINK
                'x__source' => $user_e['e__id'],
                'x__left' => $i_focus['i__id'],
            ), array('x__right'), 0);

            //Did we have any steps unlocked?
            if (count($unlocked_x) > 0) {
                $focus_tab .= view_i_list($i_completed, $in_my_x, $i_focus, $unlocked_x, $user_e, 'UNLOCKED:');
                $has_substance = true;
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
                    $focus_tab .= view_i_list($i_completed, $in_my_x, $i_focus, $unlock_paths, $user_e, 'SUGGESTED IDEAS:');
                    $has_substance = true;

                }

                //List Children if any:
                $focus_tab .= view_i_list($i_completed, $in_my_x, $i_focus, $is_next, $user_e, 'UP NEXT:');
                $has_substance = count($is_next);


            } elseif (in_array($i_focus['i__type'], $this->config->item('n___7712'))) {

                //SELECT ANSWER

                //Has no children:
                if (!$chapters) {

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

                    $has_substance = true;

                    //First fetch answers based on correct order:
                    $x_selects = array();
                    foreach ($this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                        'x__left' => $i_focus['i__id'],
                    ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $x) {
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
                        $focus_tab .= view_i_list($i_completed, $in_my_x, $i_focus, $x_selects, $user_e, 'YOU SELECTED:');

                        $focus_tab .= '<div class="doclear">&nbsp;</div>';

                        //EDIT ANSWER:
                        $focus_tab .= '<div class="margin-top-down left-margin"><a class="btn btn-discover" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');">' . $e___11035[13495]['m_icon'] . ' ' . $e___11035[13495]['m_title'] . '</a></div>';

                        $focus_tab .= '<div class="doclear">&nbsp;</div>';

                        $focus_tab .= '</div>';
                    }


                    $focus_tab .= '<div class="edit_select_answer ' . (count($x_selects) > 0 ? 'hidden' : '') . '">';
                    $focus_tab .= '<div class="doclear">&nbsp;</div>';

                    //HTML:
                    if ($i_focus['i__type'] == 6684) {

                        $focus_tab .= '<div class="pull-left headline"><span class="icon-block">&nbsp;</span>SELECT ONE:</div>';

                    } elseif ($i_focus['i__type'] == 7231) {


                        $focus_tab .= '<div class="pull-left headline"><span class="icon-block">&nbsp;</span>SELECT ONE OR MORE:</span></div>';

                        //Give option to Select None/All
                        $focus_tab .= '<div class="doclear">&nbsp;</div>';
                        $focus_tab .= '<div class="pull-right right-adj inline-block"><a href="javascript:void(0);" onclick="$(\'.answer-item i\').removeClass(\'far fa-circle\').addClass(\'fas fa-check-circle\');" style="text-decoration: underline;" title="'.$e___11035[13692]['m_title'].'">'.$e___11035[13692]['m_icon'].'</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="$(\'.answer-item i\').removeClass(\'fas fa-check-circle\').addClass(\'far fa-circle\');" style="text-decoration: underline;" title="'.$e___11035[13693]['m_title'].'">'.$e___11035[13693]['m_icon'].'</a></div>';

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

                        //Save Answers:
                        $focus_tab .= '<div class="margin-top-down left-margin"><a class="btn btn-discover" href="javascript:void(0);" onclick="x_select()">' . $e___11035[13524]['m_icon'] . ' ' . $e___11035[13524]['m_title'] . '</a></div>';

                        //Cancel:
                        $focus_tab .= '<div class="inline-block margin-top-down left-half-margin"><a class="btn btn-discover" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');" title="' . $e___11035[13502]['m_title'] . '">' . $e___11035[13502]['m_icon'] . '</a></div>';

                    }

                    $focus_tab .= '</div>';

                }

            } elseif ($i_focus['i__type'] == 6677) {

                $has_stats = false;
                if (count($is_next) > 1) {
                    //NEXT IDEAS
                    $has_stats = view_i_tree_stats($i_stats, $show_nav);
                    $focus_tab .= '<div class="i_estimates hideIfEmpty">' . $has_stats . '</div>';
                    $has_substance = true;
                }

                //DISCOVER ONLY
                $focus_tab .= view_i_list($i_completed, $in_my_x, $i_focus, $is_next, $user_e, ( $has_stats ? '' : 'UP NEXT:' ));
                $has_substance = count($is_next);

            } elseif ($i_focus['i__type'] == 6683) {

                //TEXT RESPONSE
                $has_substance = true;

                $focus_tab .= '<div class="headline"><span class="icon-block">&nbsp;</span>YOUR RESPONSE:</div>';

                //Write `skip` if you prefer not to answer...
                $focus_tab .= '<textarea class="border i_content padded x_input" placeholder="" id="x_reply">' . (count($x_completes) ? trim($x_completes[0]['x__message']) : '') . '</textarea>';

                if (count($x_completes)) {
                    //Next Ideas:
                    $focus_tab .= view_i_list($i_completed, $in_my_x, $i_focus, $is_next, $user_e, 'UP NEXT:');
                }

                $focus_tab .= '<script> $(document).ready(function () { autosize($(\'#x_reply\')); $(\'#x_reply\').focus(); }); </script>';


            } elseif ($i_focus['i__type'] == 7637) {

                $has_substance = true;

                //FILE UPLOAD
                $focus_tab .= '<div class="userUploader">';
                $focus_tab .= '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

                $focus_tab .= '<input class="inputfile" type="file" name="file" id="fileType' . $i_focus['i__type'] . '" />';


                if (count($x_completes)) {

                    $focus_tab .= '<div class="file_saving_result">';

                    $focus_tab .= '<div class="headline"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div>';

                    $focus_tab .= '<div class="previous_answer">' . $this->X_model->message_send($x_completes[0]['x__message']) . '</div>';

                    $focus_tab .= '</div>';

                    //Any child ideas?
                    $focus_tab .= view_i_list($i_completed, $in_my_x, $i_focus, $is_next, $user_e, 'UP NEXT:');

                } else {

                    //for when added:
                    $focus_tab .= '<div class="file_saving_result"></div>';

                }

                //UPLOAD BUTTON:
                $focus_tab .= '<div class="margin-top-down left-margin"><label class="btn btn-discover inline-block" for="fileType' . $i_focus['i__type'] . '" style="margin-left:5px;">' . $e___11035[13572]['m_icon'] . ' ' . $e___11035[13572]['m_title'] . '</label></div>';


                $focus_tab .= '<div class="doclear">&nbsp;</div>';
                $focus_tab .= '</form>';
                $focus_tab .= '</div>';

            }

        } else {

            //NOT IN READING LIST YET...

            //NEXT IDEAS
            $focus_tab .= '<div class="i_estimates hideIfEmpty">'.view_i_tree_stats($i_stats, $show_nav).'</div>';

            $focus_tab .= view_i_list($i_completed, $in_my_x, $i_focus, $is_next, $user_e);

            $is_previous = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                'x__right' => $i_focus['i__id'],
                'x__left !=' => config_var(12137),
            ), array('x__left'), 0);

            if(count($is_previous)){
                //IDEA PREVIOUS
                $focus_tab .= '<div style="padding:33px 0;">'.view_i_list($i_completed, $in_my_x, $i_focus, $is_previous, $user_e, 'THIS IDEA HELPS YOU:').'</div>';
            }

            $has_substance = count($is_next) || count($is_previous);

        }

    } elseif($x__type==12274){

        $counter = $i_stats['e_count'];

        //List Sources:
        $focus_tab .= view_i_tree_e($i_focus);

    } elseif($x__type==7545){

        //CERTIFICATES
        $counter = $i_stats['count_7545'];
        $focus_tab .= '<div class="i_content padded"><div class="msg">Completion could earn you some of the following certificates:</div></div>';
        $focus_tab .= '<div class="list-group" style="margin-bottom:33px;">';
        foreach ($i_stats['array_7545'] as $e) {
            $e['x__message'] = ''; //Remove for this
            $focus_tab .= view_e_basic($e);
        }
        $focus_tab .= '</div>';

    } elseif($x__type==13023){

        $this_url = $this->config->item('base_url').'/'.$i_focus['i__id'];

        foreach($this->config->item('e___13023') as $x__type2 => $m2) {

            if($x__type2==10876){

                //MENCH URL
                $focus_tab .= '<div class="headline"><span class="icon-block">'.$m2['m_icon'].'</span>'.$m2['m_title'].'</div>';
                $focus_tab .= '<input type="url" value="' .$this_url . '" class="form-control border" style="margin: 5px 0 33px 33px; width: 233px; padding-left: 8px;">';

            } elseif($x__type2==13531){

                //SHARE ON SOCIAL MEDIA
                $focus_tab .= '<div class="headline"><span class="icon-block">'.$m2['m_icon'].'</span>'.$m2['m_title'].'</div>';
                $focus_tab .= '<div class="share-this space-content" style="margin:5px 0 33px;">';
                foreach($this->config->item('e___13531') as $m2) {
                    $focus_tab .= '<div class="icon-block"><div data-network="'.$m2['m_message'].'" data-url="'.$this_url.'" data-title="'.$i_focus['i__title'].'" class="st-custom-button" title="Share with '.$m2['m_title'].'" data-toggle="tooltip" data-placement="top">'.$m2['m_icon'].'</div></div>';
                }
                $focus_tab .= '</div>';

            } elseif($x__type2==4601){

                //KEYWORDS
                $keywords = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type' => 4601, //IDEA NOTES Messages
                    'x__right' => $i_focus['i__id'],
                ), array(), 0);
                if(count($keywords)){
                    $focus_tab .= '<div class="headline"><span class="icon-block">'.$m2['m_icon'].'</span>'.$m2['m_title'].'</div>';
                    $focus_tab .= '<div class="share-this space-content" style="margin-bottom:33px;">';
                    foreach($keywords as $count => $keyword) {
                        if($count > 0){
                            $focus_tab .= ', ';
                        }
                        $focus_tab .= $keyword['x__message'];
                    }
                    $focus_tab .= '</div>';
                }
            }
        }

    } else {

        //Not supported via here:
        continue;

    }



    if(!$counter && in_array($x__type, $this->config->item('n___13298'))){
        //Hide since Zero count:
        continue;
    }

    if(!$counter && $x__type==12273 && !$has_substance){
        continue;
    }

    $default_active = ( in_array($x__type, $this->config->item('n___13300')) );
    $tab_pill_count++;

    $tab_pills .= '<li class="nav-item"><a '.$href.' class="nav-x tab-nav-'.$tab_group.' tab-head-'.$x__type.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m['m_icon']).'">'.$m['m_icon'].'&nbsp;'.( $pre_fix ? '<span class="show-max-active">'.$pre_fix.'</span>' : '' ).( !$counter ? '' : '<span class="en-type-counter-'.$x__type.'">'.$counter.'</span>&nbsp;' ).'<span class="show-max-active">'.$m['m_title'].'</span></a></li>';

    $tab_content .= '<div class="tab-content tab-group-'.$tab_group.' tab-data-'.$x__type.( $default_active ? '' : ' hidden ' ).'">';
    $tab_content .= $focus_tab;
    $tab_content .= '</div>';

}
$tab_pills .= '</ul>';




if($tab_pill_count > 1 && $show_nav){
    //DISCOVER TABS
    echo $tab_pills;
}

//Show All Tab Content:
echo $tab_content;



if($show_next_tip && $user_e['e__id'] > 0 && view_coins_e(6255, $user_e['e__id'], 0, false) <= config_item(13762)){
    //Recommend to go next:
    echo '<div class="alert no-margin"><span class="icon-block"><i class="fas fa-info-circle"></i></span>Tip: Continue to the next idea by tapping<span class="icon-block-xs"><i class="fas fa-step-forward black"></i></span>(below)</div>';
}


//COMMENTS
echo '<div style="padding:33px 0 0;">';
echo view_i_note_mix(12419, $this->X_model->fetch(array(
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 12419, //COMMENTS
    'x__right' => $i_focus['i__id'],
), array('x__source'), 0, 0, array('x__sort' => 'ASC')), ( $user_e['e__id'] > 0 ));
echo '</div>';


echo '</div>'; //CLOSE CONTAINER


if($in_my_x){

    $buttons_found = 0;
    $buttons_ui = '';


    foreach($this->config->item('e___13289') as $e__id => $m) {

        $control_btn = '';

        if($e__id==12896 && count($sitemap_items)){

            //Is Saved already by this user?
            $is_saved = count($this->X_model->fetch(array(
                'x__up' => $user_e['e__id'],
                'x__right' => $i_focus['i__id'],
                'x__type' => 12896, //SAVED
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )));

            $control_btn = '<a href="javascript:void(0);" onclick="x_save('.$i_focus['i__id'].')" title="'.$m['m_title'].'"><span class="controller-nav toggle_saved '.( $is_saved ? '' : 'hidden' ).'">'.$e___11035[12896]['m_icon'].'</span><span class="controller-nav toggle_saved '.( $is_saved ? 'hidden' : '' ).'">'.$e___11035[12906]['m_icon'].'</span></a>';

        } elseif($e__id==12991 && !$i_completed && count($sitemap_items)){

            //GO BACK
            $control_btn = '<a class="controller-nav" href="'.( isset($_GET['previous_x']) && $_GET['previous_x']>0 ? '/'.$_GET['previous_x'] : ( $previous_level_id > 0 ? '/x/x_previous/'.$previous_level_id.'/'.$i_focus['i__id'] : home_url() ) ).'" title="'.$m['m_title'].'">'.$m['m_icon'].'</a>';

        } elseif($e__id==12211 && !$i_completed){

            //GO NEXT
            $control_btn = '<a class="controller-nav" href="javascript:void(0);" onclick="go_12211()" title="'.$m['m_title'].'">'.$m['m_icon'].'</a>';

        } elseif($e__id==12419 && count($sitemap_items)){

            //ADD COMMENT
            $control_btn = '<a class="controller-nav add-comments" href="javascript:void(0);" title="'.$m['m_title'].'">'.$m['m_icon'].'</a>';

        }

        $buttons_ui .= '<div>'.( $control_btn ? $control_btn : '&nbsp;' ).'</div>';

        if($control_btn){
            $buttons_found++;
        }

    }

    if($buttons_found > 0){
        echo '<div class="container fixed-bottom">';
        echo '<div class="row">';
        echo '<div class="discover-controller">';
        echo $buttons_ui;
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

} else {

    echo '</div>'; //CLOSE CONTAINER

}



?>