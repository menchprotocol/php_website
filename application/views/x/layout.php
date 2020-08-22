<script>
    var focus_i__id = <?= $i_focus['i__id'] ?>;
    var focus_i__type = <?= $i_focus['i__type'] ?>;
</script>

<script src="/application/views/x/layout.js?v=<?= config_var(11060) ?>"
        type="text/javascript"></script>

<?php

echo '<div class="container load_13210">';

$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
$e___13291 = $this->config->item('e___13291'); //DISCOVER TABS
$e___13544 = $this->config->item('e___13544'); //IDEA TREE COUNT

$x_completes = array();
$i_type_meet_requirement = in_array($i_focus['i__type'], $this->config->item('n___7309'));
$user_e = superpower_assigned();
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
$in_my_x = ( $user_e['e__id'] ? $this->X_model->i_home($i_focus['i__id'], $user_e) : false );

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
$main_title = '<div style="position: relative; display: block;">' . ( $show_percentage ? '<div class="progress-bg-list no-horizonal-margin" title="Discovered '.$completion_rate['steps_completed'].' of '.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)" data-toggle="tooltip" data-placement="bottom"><span class="progress-connector"></span><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>' : '' ) . '<h1 class="block-one"><span class="icon-block top-icon '.( $show_percentage ? '' : ' thin-top ' ).'">'.view_icon_i_x( $completion_rate['completion_percentage'], $i_focus ).'</span><span class="title-block-lg">' . view_i_title($i_focus) . '</span></h1>'.'</div>';

//IDEA TITLE
echo $main_title;








//MESSAGES
$counter = null; //Hide message count
echo '<div style="margin-bottom:33px;">';
foreach($messages as $message_x) {
    echo $this->X_model->message_send(
        $message_x['x__message'],
        $user_e
    );
}
echo '</div>';

if($in_my_x && !count($x_completes) && in_array($i_focus['i__type'], $this->config->item('n___12211'))){
    //Give option to mark as read:
    //echo '<div class="margin-top-down"><span class="icon-block">&nbsp;</span><a class="btn btn-x" href="javascript:void(0);" onclick="go_12211()">'.$e___11035[12211]['m_title'].' '.$e___11035[12211]['m_icon'].'</a></div>';
}






if(!$in_my_x){

    //GET STARTED
    echo '<div class="margin-top-down left-margin"><a class="btn btn-x" href="/x/x_start/'.$i_focus['i__id'].'">'.$e___11035[4235]['m_icon'].' '.$e___11035[4235]['m_title'].'</a></div>';

}





//DISCOVER LAYOUT
$i_stats = i_stats($i_focus['i__metadata']);
$tab_group = 13291;
$tab_pills = '<ul class="nav nav-tabs nav-sm">';
$tab_content = '';
$tab_pill_count = 0;

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

        //COUNTER: Average Ideas
        $counter = ( count($is_next) > $i_stats['i___6170'] ? count($is_next) : view_number($i_stats['i___6170']) );

        if(!$in_my_x){

            //NEXT IDEAS
            $focus_tab .= '<div class="grey i_estimates">'.view_i_tree_stats($i_stats).'</div>';

            $focus_tab .= view_i_list($i_focus, $is_next, $user_e);

            //IDEA PREVIOUS
            $focus_tab .= '<div style="padding:33px 0;">'.view_i_list($i_focus, $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                'x__right' => $i_focus['i__id'],
                'x__left !=' => config_var(12137),
            ), array('x__left'), 0), $user_e, 'THIS IDEA HELPS YOU:').'</div>';

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

    } elseif($x__type==12419){

        //COMMENTS
        $comments = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 12419, //COMMENTS
            'x__right' => $i_focus['i__id'],
        ), array('x__source'), 0, 0, array('x__sort' => 'ASC'));
        $counter = ( count($comments) > 0 ? count($comments) : null );

        $focus_tab .= '<div style="margin-bottom:33px;">';
        $focus_tab .= view_i_note_mix($x__type, $comments);
        $focus_tab .= '</div>';

    } elseif($x__type==6255){

        $counter = view_coins_i(6255,  $i_focus, false);
        $focus_tab .= '<div class="i_content padded"><div class="msg">This idea has been discovered by '.$counter.' user'.view__s($counter).' so far.</div></div>';

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

    $default_active = ( $counter > 0 && in_array($x__type, $this->config->item('n___13300')));
    $tab_pill_count++;

    $tab_pills .= '<li class="nav-item"><a '.$href.' class="nav-x tab-nav-'.$tab_group.' tab-head-'.$x__type.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m['m_icon']).'"  data-toggle="tooltip" data-placement="top" title="'.$m['m_title'].( strlen($m['m_message']) ? ' '.$m['m_message'] : '' ).'">'.$m['m_icon'].( is_null($counter) ? '' : ' <span class="en-type-counter-'.$x__type.'">'.$counter.'</span>' ).'<span class="show-max-active">&nbsp;'.$m['m_title'].'</span></a></li>';

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







if($in_my_x){

    //PREVIOUSLY UNLOCKED:
    $unlocked_x = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        'x__type' => 6140, //DISCOVER UNLOCK LINK
        'x__source' => $user_e['e__id'],
        'x__left' => $i_focus['i__id'],
    ), array('x__right'), 0);

    //Did we have any steps unlocked?
    if(count($unlocked_x) > 0){
        echo view_i_list($i_focus, $unlocked_x, $user_e, 'UNLOCKED:');
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
        if(!count($x_completes) && !count($unlocked_connections) && count($unlock_paths)){

            //List Unlock paths:
            echo view_i_list($i_focus, $unlock_paths, $user_e, 'SUGGESTED IDEAS:');

        }

        //List Children if any:
        echo view_i_list($i_focus, $is_next, $user_e);


    } elseif (in_array($i_focus['i__type'], $this->config->item('n___7712'))){

        //SELECT ANSWER

        //Has no children:
        if(!$chapters){

            //Mark this as complete since there is no child to choose from:
            if(!count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',' , $this->config->item('n___12229')) . ')' => null, //DISCOVER COMPLETE
                'x__source' => $user_e['e__id'],
                'x__left' => $i_focus['i__id'],
            )))){

                array_push($x_completes, $this->X_model->mark_complete($i_focus, array(
                    'x__type' => 4559, //DISCOVER MESSAGES
                    'x__source' => $user_e['e__id'],
                )));

            }

        } else {

            //First fetch answers based on correct order:
            $x_selects = array();
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'x__left' => $i_focus['i__id'],
            ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $x){
                //See if this answer was seleted:
                if(count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVER IDEA LINK
                    'x__left' => $i_focus['i__id'],
                    'x__right' => $x['i__id'],
                    'x__source' => $user_e['e__id'],
                )))){
                    array_push($x_selects, $x);
                }
            }

            if(count($x_selects) > 0){
                //MODIFY ANSWER
                echo '<div class="edit_select_answer">';

                //List answers:
                echo view_i_list($i_focus, $x_selects, $user_e, 'YOU SELECTED:');

                echo '<div class="doclear">&nbsp;</div>';


                //EDIT ANSWER:
                echo '<div class="margin-top-down left-margin"><a class="btn btn-x" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');">'.$e___11035[13495]['m_icon'].' '.$e___11035[13495]['m_title'].'</a></div>';

                echo '<div class="doclear">&nbsp;</div>';

                echo '</div>';
            }


            echo '<div class="edit_select_answer '.( count($x_selects)>0 ? 'hidden' : '' ).'">';
            echo '<div class="doclear">&nbsp;</div>';

            //HTML:
            if ($i_focus['i__type'] == 6684) {

                echo '<div class="pull-left headline"><span class="icon-block">&nbsp;</span>SELECT ONE:</div>';

            } elseif ($i_focus['i__type'] == 7231) {

                //Give option to Select None/All
                echo '<div class="pull-left headline"><span class="icon-block">&nbsp;</span>SELECT ONE OR MORE: [<span style="padding:0 2px;"><a href="javascript:void(0);" onclick="$(\'.answer-item .fa-circle\').removeClass(\'far\').addClass(\'fas\');" style="text-decoration: underline;">ALL</a><span style="padding:0 2px;">/</span><a href="javascript:void(0);" onclick="$(\'.answer-item .fa-circle\').removeClass(\'fas\').addClass(\'far\');" style="text-decoration: underline;">NONE</a></span>]</div>';

            }

            echo '<div class="doclear">&nbsp;</div>';


            //Open for list to be printed:
            echo '<div class="list-group list-answers" i__type="'.$i_focus['i__type'].'">';


            //List children to choose from:
            $common_prefix = i_calc_common_prefix($is_next, 'i__title');
            foreach($is_next as $key => $next_i) {

                //Has this been previously selected?
                $previously_selected = count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVER IDEA LINKS
                    'x__left' => $i_focus['i__id'],
                    'x__right' => $next_i['i__id'],
                    'x__source' => $user_e['e__id'],
                )));

                echo '<a href="javascript:void(0);" onclick="select_answer('.$next_i['i__id'].')" selection_i__id="'.$next_i['i__id'].'" class="x_select_'.$next_i['i__id'].' answer-item list-group-item itemdiscover no-left-padding">';


                echo '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';
                echo '<td class="icon-block check-icon" style="padding: 0 !important;"><i class="'.( $previously_selected ? 'fas' : 'far' ).' fa-circle discover"></i></td>';

                echo '<td style="width:100%; padding: 0 !important;">';
                echo '<b class="montserrat i-url" style="margin-left:0;">'.view_i_title($next_i, $common_prefix).'</b>';
                echo '</td>';

                echo '</tr></table>';


                echo '</a>';
            }


            //Close list:
            echo '</div>';


            if(count($x_selects)>0){

                //Save Answers:
                echo '<div class="margin-top-down left-margin"><a class="btn btn-x" href="javascript:void(0);" onclick="x_select()">'.$e___11035[13524]['m_icon'].' '.$e___11035[13524]['m_title'].'</a></div>';

                //Cancel:
                echo '<div class="inline-block margin-top-down left-half-margin"><a class="btn btn-x" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');" title="'.$e___11035[13502]['m_title'].'">'.$e___11035[13502]['m_icon'].'</a></div>';

            } else {

                //Give option to save:
                echo '<div class="margin-top-down"><span class="icon-block">&nbsp;</span><a class="btn btn-x" href="javascript:void(0);" onclick="go_12211()">'.$e___11035[13524]['m_title'].' '.$e___11035[13524]['m_icon'].'</a></div>';

            }

            echo '</div>';

        }

    } elseif ($i_focus['i__type'] == 6677) {

        if(count($is_next) > 1){
            //NEXT IDEAS
            echo '<div class="grey i_estimates">'.view_i_tree_stats($i_stats).'</div>';
        }

        //DISCOVER ONLY
        echo view_i_list($i_focus, $is_next, $user_e);

    } elseif ($i_focus['i__type'] == 6683) {

        //TEXT RESPONSE

        echo '<div class="headline"><span class="icon-block">&nbsp;</span>YOUR ANSWER:</div>';

        echo '<textarea class="border i_content padded x_input" placeholder="Write `skip` if you prefer not to answer..." id="x_reply">'.( count($x_completes) ? trim($x_completes[0]['x__message']) : '' ).'</textarea>';

        if(count($x_completes)){
            //Next Ideas:
            echo view_i_list($i_focus, $is_next, $user_e);
        } else {
            //Give Button option:
            echo '<div><span class="icon-block">&nbsp;</span><a class="btn btn-x" href="javascript:void(0);" onclick="go_12211()">'.$e___11035[13524]['m_title'].' '.$e___11035[13524]['m_icon'].'</a></div>';
        }

        echo '<script> $(document).ready(function () { autosize($(\'#x_reply\')); $(\'#x_reply\').focus(); }); </script>';


    } elseif ($i_focus['i__type'] == 7637) {

        //FILE UPLOAD
        echo '<div class="userUploader">';
        echo '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

        echo '<input class="inputfile" type="file" name="file" id="fileType'.$i_focus['i__type'].'" />';


        if(count($x_completes)) {

            echo '<div class="file_saving_result">';

            echo '<div class="headline"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div>';

            echo '<div class="previous_answer">'.$this->X_model->message_send($x_completes[0]['x__message']).'</div>';

            echo '</div>';

            //Any child ideas?
            echo view_i_list($i_focus, $is_next, $user_e);

        } else {

            //for when added:
            echo '<div class="file_saving_result"></div>';

        }

        //UPLOAD BUTTON:
        echo '<div class="margin-top-down left-margin"><label class="btn btn-x inline-block" for="fileType'.$i_focus['i__type'].'" style="margin-left:5px;">'.$e___11035[13572]['m_icon'].' '.$e___11035[13572]['m_title'].'</label></div>';


        echo '<div class="doclear">&nbsp;</div>';
        echo '</form>';
        echo '</div>';

    }




    echo '</div>'; //CLOSE CONTAINER




    //DISCOVERY CONTROLLER


    //Discoveries
    $previous_level_id = 0; //The ID of the Idea one level up, if any
    $u_x_ids = $this->X_model->ids($user_e['e__id']);

    if(!in_array($i_focus['i__id'], $u_x_ids)){

        //Find it:
        $recursive_parents = $this->I_model->recursive_parents($i_focus['i__id'], true, true);
        $sitemap_items = array();

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

                    array_push($sitemap_items, view_i_x($is_this[0]));

                    if(in_array($previous_i__id, $u_x_ids)){
                        //We reached the top-level discovery:
                        break;
                    }
                }
            }
        }
    }



    echo '<div class="container load_13210 hidden">';
    echo '<div class="list-group">';

    //My Discoveries:
    echo '<div class="list-group-item no-side-padding itemsource"><a href="/@'.$user_e['e__id'].'"><span class="icon-block">'.$user_e['e__icon'].'</span><span class="montserrat">'.$user_e['e__title'].'</span></a></div>';


    //Did We Find It?
    if($previous_level_id){
        //Idea Map:
        echo join('', array_reverse($sitemap_items));
    }

    //Current Idea:
    echo '<div class="list-group-item no-padding itemdiscover"><a href="javascript:void(0);" onclick="$(\'.load_13210\').toggleClass(\'hidden\');">'.$main_title.'</a></div>';


    echo '</div>';
    echo '</div>';






    $column_width = number_format(100/count($this->config->item('n___13289')), 2);

    echo "\n\n".'<div class="container fixed-bottom">'."\n\n";
    echo '<div class="row">';
    echo '<table class="discover-controller"><tr>';

    foreach($this->config->item('e___13289') as $e__id => $m) {

        $url = '';
        if($e__id==13510){

            //Is Saved?
            $is_saved = count($this->X_model->fetch(array(
                'x__up' => $user_e['e__id'],
                'x__right' => $i_focus['i__id'],
                'x__type' => 12896, //SAVED
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )));

            $e___13510 = $this->config->item('e___13510'); //SAVED IDEAS
            $url = '<a href="javascript:void(0);" onclick="x_save('.$i_focus['i__id'].')" title="'.$m['m_title'].'"><span class="controller-nav toggle_saved '.( $is_saved ? '' : 'hidden' ).'">'.$e___13510[12896]['m_icon'].'</span><span class="controller-nav toggle_saved '.( $is_saved ? 'hidden' : '' ).'">'.$e___13510[12906]['m_icon'].'</span></a>';

        } elseif($e__id==12991){

            //GO BACK
            $url = '<a class="controller-nav" href="'.( isset($_GET['previous_x']) && $_GET['previous_x']>0 ? '/'.$_GET['previous_x'] : ( $previous_level_id > 0 ? '/x/x_previous/'.$previous_level_id.'/'.$i_focus['i__id'] : home_url() ) ).'" title="'.$m['m_title'].'">'.$m['m_icon'].'</a>';

        } elseif($e__id==12211){

            //GO NEXT
            $url = '<a class="controller-nav" href="javascript:void(0);" onclick="go_12211()" title="'.$m['m_title'].'">'.$m['m_icon'].'</a>';

        } elseif($e__id==13491){

            //FONT SIZE
            $url .= '<div class="dropdown inline-block" title="'.$m['m_title'].'">';
            $url .= '<button type="button" class="btn no-side-padding" id="dropdownMenuButton'.$e__id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
            $url .= '<span class="icon-block controller-nav">' .$m['m_icon'].'</span>';
            $url .= '</button>';
            $url .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$e__id.'">';
            foreach($this->config->item('e___'.$e__id) as $x__type2 => $m2) {
                $url .= '<a href="javascript:void(0);" onclick="set_13491('.$x__type2.')" class="dropdown-item montserrat font_items font_item_'.$x__type2.' '.( $this->session->userdata('session_var_13491')==$x__type2 ? ' active ' : '' ).'"><span class="icon-block">'.$m2['m_icon'].'</span>'.$m2['m_title'].'</a>';
            }
            $url .= '</div>';
            $url .= '</div>';

        } elseif($e__id==13210){

            //IDEA INDEX
            $url = '<a href="javascript:void(0);" onclick="$(\'.load_13210\').toggleClass(\'hidden\');" class="controller-nav" title="'.$m['m_title'].'">'.$m['m_icon'].'</a>';

        }

        echo '<td style="width:'.$column_width.'%;">'.$url.'</td>';

    }

    echo '</tr></table>';
    echo '</div>';
    echo '</div>';

} else {

    echo '</div>'; //CLOSE CONTAINER

}



?>