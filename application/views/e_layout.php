<?php
$e___6177 = $this->config->item('e___6177'); //Source Status
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$source_of_e = source_of_e($e['e__id']);
$source_is_e = $e['e__id']==$member_e['e__id'];
$superpower_10939 = superpower_active(10939, true); //SUPERPOWER OF IDEAGING
$superpower_13422 = superpower_active(13422, true); //SUPERPOWER OF SOURCING
$superpower_12701 = superpower_active(12701, true); //SUPERPOWER OF GLASSES
$superpower_12703 = superpower_active(12703, true); //SUPERPOWER OF CHAIN LINK
$control_enabled = $source_is_e || $superpower_10939;


$profiles = $this->X_model->fetch(array(
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    'x__down' => $e['e__id'],
), array('x__up'), 0, 0, array('e__spectrum' => 'DESC'));
?>

<script>
    //Set global variables:
    var e_focus_filter = -1; //No filter, show all
</script>
<input type="hidden" id="focus__id" value="<?= $e['e__id'] ?>" />
<script src="/application/views/e_layout.js?v=<?= view_memory(6404,11060) ?>" type="text/javascript"></script>

    <?php

    echo '<div class="container">';





    //PROFILE
    if(!$source_is_e || $superpower_13422){

        $count = 0;
        $show_max_14538 = view_memory(6404,14538);
        $see_more_button = false;

        echo '<div id="list-in-11030" class="row dominHeight">';

        if($superpower_13422){
            echo '<div id="new_11030" class="col-12 list-group-item list-adder no-side-padding">
                    <div class="input-group border">
                        <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'#New11030input\').focus();"><span class="icon-block">'.$e___11035[13914]['m__cover'].'</span></a>
                        <input type="text"
                               class="form-control form-control-thick algolia_search dotransparent add-input"
                               id="New11030input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="'.$e___11035[13914]['m__title'].'">
                    </div></div>';
        }

        foreach($profiles as $e_profile) {

            if(!$see_more_button && $count==$show_max_14538){
                echo view_show_more('.see_all_profiles', (count($e_profile)-$show_max_14538));
                $see_more_button = true;
            }

            $view_e = view_e(11030, $e_profile, ( $count<$show_max_14538 ? '' : 'see_all_profiles hidden'),  ($source_of_e || ($member_e && ($member_e['e__id']==$e_profile['x__source']))));

            if($view_e){
                echo $view_e;
                $count++;
            }
        }

        echo '</div>';


        //SOURE STATUS
        echo '<div>'.view_input_dropdown(6177, $e['e__type'], null, $source_of_e, true, $e['e__id']).'</div>';

        //SOURCE TITLE
        echo '<div>'.view_input_text(6197, $e['e__title'], $e['e__id'], ($source_of_e && in_array($e['e__type'], $this->config->item('n___7358'))), 0, true, '<span class="cover_icon_'.$e['e__id'].'">'.view_e__cover($e['e__cover']).'</span>', extract_icon_color($e['e__cover'])).'</div>';

    }

    //Determine Focus Tab:
    $counter__e = view_coins_e(12274, $e['e__id'], 0, false);
    $counter__i = view_coins_e(12273, $e['e__id'], 0, false);
    $counter__x = view_coins_e( 6255, $e['e__id'], 0, false);
    $active_x__type = 0;


    if(($counter__i>0 && !($source_is_e && !$superpower_10939)) || ($source_is_e && $superpower_10939)){
        //IDEAS
        $active_x__type = 12273;
    } elseif($counter__x > 0 || $source_is_e){
        //DISCOVERIES
        $active_x__type = 6255;
    } elseif($counter__e > 0){
        //SOURCES
        $active_x__type = 12274;
    }


    $tab_nav = '';
    $tab_content = '';
    foreach($this->config->item('e___11089') as $x__type => $m) {

        $superpower_actives = array_intersect($this->config->item('n___10957'), $m['m__profile']);
        if(count($superpower_actives)){
            if(!superpower_active(end($superpower_actives), true) && !$source_is_e){
                //Missing Superpower:
                continue;
            }
        }

        $counter = null;
        $ui = null;

        if($source_is_e && strlen($m['m__message']) > 0){
            $ui .= '<div style="padding-bottom: 13px;"><span class="icon-block"><i class="fas fa-info-circle black"></i></span>'.$m['m__message'].'</div>';
        }

        //Is this a caret menu?
        if(in_array(11040 , $m['m__profile'])){

            $tab_nav .= view_caret($x__type, $m, $e['e__id']);
            continue;

        } elseif(in_array($x__type, $this->config->item('n___6194'))){

            //SOURCE REFERENCE:
            $e_count_6194 = e_count_6194($e['e__id'], $x__type);
            $counter = ( isset($e_count_6194[$x__type]) ? $e_count_6194[$x__type] : 0 );
            if(!$counter){
                continue;
            }
            //$ui .= '<div class="headline"><span class="icon-block">'.$m['m__cover'].'</span>'.$m['m__title'].'</div>';
            $ui .= '<div><span class="icon-block">&nbsp;</span>Source referenced as '.$m['m__cover'].' '.$m['m__title'].' '.number_format($counter, 0).' times.</div>';

        } elseif($x__type==12274){

            //SOURCES
            $counter = $counter__e;
            $list_e = view_coins_e(12274, $e['e__id'], 1);

            //SOURCE MASS EDITOR
            if($superpower_12703){

                //Mass Editor:
                $dropdown_options = '';
                $input_options = '';
                $editor_counter = 0;

                foreach($this->config->item('e___4997') as $action_e__id => $e_list_action) {


                    $editor_counter++;
                    $dropdown_options .= '<option value="' . $action_e__id . '">' .$e_list_action['m__title'] . '</option>';
                    $is_upper = ( in_array($action_e__id, $this->config->item('n___12577') /* SOURCE UPDATER UPPERCASE */) ? ' css__title ' : false );


                    //Start with the input wrapper:
                    $input_options .= '<span id="mass_id_'.$action_e__id.'" title="'.$e_list_action['m__message'].'" class="inline-block '. ( $editor_counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';




                    if(in_array($action_e__id, array(5000, 5001, 10625))){

                        //String Find and Replace:

                        //Find:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'" placeholder="Search" class="form-control border '.$is_upper.'">';

                        //Replace:
                        $input_options .= '<input type="text" name="mass_value2_'.$action_e__id.'" placeholder="Replace" class="form-control border '.$is_upper.'">';


                    } elseif(in_array($action_e__id, array(5981, 12928, 12930, 5982, 13441))){

                        //Member search box:

                        //String command:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search sources..." class="form-control algolia_search e_text_search border '.$is_upper.'">';

                        //We don't need the second value field here:
                        $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" placeholder="Search Source" />';


                    } elseif($action_e__id == 11956){

                        //IF HAS THIS
                        $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="IF THIS SOURCE..." class="form-control algolia_search e_text_search border '.$is_upper.'">';

                        //ADD THIS
                        $input_options .= '<input type="text" name="mass_value2_'.$action_e__id.'"  placeholder="ADD THIS SOURCE..." class="form-control algolia_search e_text_search border '.$is_upper.'">';


                    } elseif($action_e__id == 5003){

                        //Member Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_e__id.'" class="form-control border">';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($this->config->item('e___6177') /* Source Status */ as $x__type3 => $m3){
                            $input_options .= '<option value="'.$x__type3.'">Update All '.$m3['m__title'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_e__id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($this->config->item('e___6177') /* Source Status */ as $x__type3 => $m3){
                            $input_options .= '<option value="'.$x__type3.'">Set to '.$m3['m__title'].'</option>';
                        }
                        $input_options .= '</select>';


                    } elseif($action_e__id == 5865){

                        //Transaction Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_e__id.'" class="form-control border">';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($this->config->item('e___6186') /* Transaction Status */ as $x__type3 => $m3){
                            $input_options .= '<option value="'.$x__type3.'">Update All '.$m3['m__title'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_e__id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($this->config->item('e___6186') /* Transaction Status */ as $x__type3 => $m3){
                            $input_options .= '<option value="'.$x__type3.'">Set to '.$m3['m__title'].'</option>';
                        }
                        $input_options .= '</select>';


                    } else {

                        //String command:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="String..." class="form-control border '.$is_upper.'">';

                        //We don't need the second value field here:
                        $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

                    }

                    $input_options .= '</span>';

                }


                $ui .= '<div class="action-middle-btn grey toggle_4997"><a href="javascript:void(0);" onclick="$(\'.toggle_4997\').toggleClass(\'hidden\');" title="'.$e___11035[4997]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$e___11035[4997]['m__cover'].'</a></div>';



                $ui .= '<div class="toggle_4997 hidden">';
                $ui .= '<div class="headline"><span class="icon-block">'.$e___11035[4997]['m__cover'].'</span>'.$e___11035[4997]['m__title'].'</div>';
                $ui .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important; margin-left: 41px;">';

                //Drop Down
                $ui .= '<select class="form-control border" name="mass_action_e__id" id="set_mass_action">';
                $ui .= $dropdown_options;
                $ui .= '</select>';

                $ui .= $input_options;

                $ui .= '<div><input type="submit" value="APPLY" class="btn btn-default inline-block"></div>';

                $ui .= '</form>';

                //Also add invisible child IDs for quick copy/pasting:
                $ui .= '<div class="hideIfEmpty texttransparent">';
                foreach($list_e as $e_portfolio) {
                    $ui .= $e_portfolio['e__id'].',';
                }
                $ui .= '</div>';
                $ui .= '<div class="doclear">&nbsp;</div>';
                $ui .= '</div>';







                //Source Status Filters:
                if(superpower_active(14005, true)){

                    $e_count = $this->E_model->child_count($e['e__id'], $this->config->item('n___7358') /* ACTIVE */);
                    $child_e_filters = $this->X_model->fetch(array(
                        'x__up' => $e['e__id'],
                        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                    ), array('x__down'), 0, 0, array('e__type' => 'ASC'), 'COUNT(e__id) as totals, e__type', 'e__type');

                    //Only show filtering UI if we find child sources with different Status (Otherwise no need to filter):
                    if (count($child_e_filters) > 0 && $child_e_filters[0]['totals'] < $e_count) {

                        //Add 2nd Navigation to UI
                        $ui .= '<div class="nav nav-pills nav-sm">';

                        //Show fixed All button:
                        $ui .= '<li class="nav-item"><a href="#" onclick="e_filter_status(11029, -1)" class="nav-x e_filter_status_11029 active en_status_11029_-1" data-toggle="tooltip" data-placement="top" title="View all sources"><i class="fas fa-asterisk source"></i><span class="source">&nbsp;' . $e_count . '</span></a></li>';

                        //Show each specific filter based on DB counts:
                        foreach($child_e_filters as $c_c) {
                            $st = $e___6177[$c_c['e__type']];
                            $extract_icon_color = extract_icon_color($st['m__cover']);
                            $ui .= '<li class="nav-item"><a href="javascript:void(0)" onclick="e_filter_status(11029, ' . $c_c['e__type'] . ')" class="nav-x nav-link e_filter_status_11029 en_status_11029_' . $c_c['e__type'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['m__message'] . '">' . $st['m__cover'] . '<span class="' . $extract_icon_color . '">&nbsp;' . $c_c['totals'] . '</span><span class="show-max '.$extract_icon_color.'">&nbsp;' . $st['m__title'] . '</span></a></li>';
                        }

                        $ui .= '</div>';

                    }
                }
            }

            //$ui .= '<div class="headline"><span class="icon-block">'.$e___11035[11029]['m__cover'].'</span>'.$e___11035[11029]['m__title'].'</div>';
            $ui .= '<div id="list-in-11029" class="row hideIfEmpty">';

            $common_prefix = i_calc_common_prefix($list_e, 'e__title');

            foreach($list_e as $e_portfolio) {
                $ui .= view_e(11029, $e_portfolio,null,  ($source_of_e || ($member_e && ($member_e['e__id']==$e_portfolio['x__source']))), $common_prefix);
            }
            if ($counter > count($list_e)) {
                $ui .= view_load_more(11029, 1, view_memory(6404,11064), $counter);
            }

            //Input to add new child:
            if($superpower_13422){

                $ui .= '<div id="new_11029" current-count="'.$counter.'" class="list-group-item list-adder no-side-padding '.superpower_active(10939).'">
                        <div class="input-group border">
                            <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'#New11029input\').focus();"><span class="icon-block">'.$e___11035[14054]['m__cover'].'</span></a>
                            <input type="text"
                                   class="form-control form-control-thick algolia_search dotransparent add-input"
                                   id="New11029input"
                                   maxlength="' . view_memory(6404,6197) . '"
                                   placeholder="'.$e___11035[14054]['m__title'].'">
                        </div><div class="algolia_pad_search hidden pad_expand">&nbsp;</div></div>';

            } else {

                $ui .= '<div id="new_11029" class="hideIfEmpty"></div>';

            }

            $ui .= '</div>';

        } elseif($x__type==12273){

            //IDEAS
            $counter = $counter__i;
            $i_exclude = array();

            //My Ideas
            $i_bookmarks = $this->X_model->fetch(array(
                'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 10573, //BOOKMARKED IDEAS
                'x__up' => $e['e__id'],
            ), array('x__right'), view_memory(6404,11064), 0, array('x__spectrum' => 'ASC'));


            //Any Ideas?
            if(count($i_bookmarks) || $source_is_e){

                $ui .= '<div class="headline top-margin"><span class="icon-block">' . $e___11035[10573]['m__cover'] . '</span>' . $e___11035[10573]['m__title'] . '</div>';

                //Need 2 or more to sort...
                $ui .= ( count($i_bookmarks) >= view_memory(6404,14527) ? '<script> $(document).ready(function () {x_sort_load(10573)}); </script>' : '<style> #list-in-10573 .x_sort {display:none !important;} </style>' );

                $ui .= '<div class="row hideIfEmpty" id="list-in-10573">';
                foreach($i_bookmarks as $item){
                    array_push($i_exclude, $item['i__id']);
                    $ui .= view_i(10573, 0, null, $item, $control_enabled,null, $e);
                }
                $ui .= '</div>';

            }

            //Add Idea:
            if($superpower_10939 && $source_is_e){

                //Give Option to Add New Idea:
                $ui .= '<div class="new-list-10573 list-group"><div class="list-group-item list-adder">
                    <div class="input-group border">
                        <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'#newIdeaTitle\').focus();"><span class="icon-block">'.$e___11035[14015]['m__cover'].'</span></a>
                        <input type="text"
                               class="form-control form-control-thick algolia_search dotransparent add-input"
                               maxlength="' . view_memory(6404,4736) . '"
                               id="newIdeaTitle"
                               placeholder="'.$e___11035[14015]['m__title'].'">
                    </div></div></div>';

                $ui .= '<script> $(document).ready(function () { i_load_search(10573); }); </script>';

            }


            //Referenced Ideas
            $list_i = view_coins_e(12273, $e['e__id'], 1, true, $i_exclude);

            if(count($list_i) || $superpower_10939){

                if(count($i_bookmarks) && count($list_i)){
                    $ui .= '<div class="headline top-margin"><span class="icon-block">'.$e___11035[13550]['m__cover'].'</span>'.$e___11035[13550]['m__title'].'</div>';
                }

                $ui .= '<div class="row hideIfEmpty" id="list-in-13550">';
                $drop_limit = doubleval(view_memory(6404,14684));
                $max_seconds = intval(view_memory(6404,14684));
                $max_i__spectrum = 0;
                $show_all_i_btn = false;
                foreach($list_i as $count => $item){

                    $i_stats = i_stats($item['i__metadata']);
                    if(!$show_all_i_btn && $max_i__spectrum>0 && $item['i__spectrum']>0 && $i_stats['i___6162']<=$max_seconds && (($max_i__spectrum * $drop_limit) > $item['i__spectrum'])){
                        $ui .= '<div class="col-md-4 col-6 no-padding show_all_ideas"><div class="cover-wrapper"><a href="javascript:void();" onclick="$(\'.show_all_ideas\').toggleClass(\'hidden\');" class="grey-background cover-link"><div class="cover-btn">'.$e___11035[14684]['m__cover'].'</div><div class="cover-head '.extract_icon_color($e___11035[14684]['m__cover']).'">'.$e___11035[14684]['m__title'].'</div></a></div></div>';
                        $show_all_i_btn = true;
                    }

                    $max_i__spectrum = $item['i__spectrum'];
                    $show_message = strlen($item['x__message']) && trim($item['x__message'])!=$this->uri->segment(1); //Basic references only
                    $ui .= view_i(13550, 0, null, $item, $control_enabled,( $show_message ? $this->X_model->message_view($item['x__message'], true) : null), $e, null, ( $show_all_i_btn ? ' show_all_ideas hidden ' : null ));

                }
                $ui .= '</div>';

                //Are there more?
                if($counter > count($list_i)){
                    $ui .= '<div style="padding: 13px 0;" class="'.superpower_active(12700).'"><div class="msg alert alert-warning" role="alert"><a href="/-4341?x__source='.$member_e['e__id'].'&x__type=4983&x__status='.join(',', $this->config->item('n___7359')).'"><span class="icon-block">'.$e___11035[13913]['m__cover'].'</span>'.$e___11035[13913]['m__title'].' ['.$counter.']</a></div></div>';
                }

            }

            if($superpower_10939 && !$source_is_e){

                //Give Option to Add New Idea:
                $ui .= '<div class="new-list-13550 list-group"><div class="list-group-item list-adder">
                    <div class="input-group border">
                        <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'#newIdeaTitle\').focus();"><span class="icon-block">'.$e___11035[14016]['m__cover'].'</span></a>
                        <input type="text"
                               class="form-control form-control-thick algolia_search dotransparent add-input"
                               maxlength="' . view_memory(6404,4736) . '"
                               id="newIdeaTitle"
                               placeholder="'.$e___11035[14016]['m__title'].'">
                    </div></div></div>';

                $ui .= '<script> $(document).ready(function () { i_load_search(13550); }); </script>';

            }


        } elseif($x__type==6255){

            //DISCOVERIES
            $counter = $counter__x;
            $my_x_ids = array();

            //Show My discoveries
            if($counter){

                $list_x  = view_coins_e(6255, $e['e__id'], 1);

                $ui .= '<div class="headline top-margin"><span class="icon-block">'.$e___11035[12969]['m__cover'].'</span>'.$e___11035[12969]['m__title'].'</div>';
                $ui .= '<div class="row hideIfEmpty" id="list-in-12969">';
                foreach($list_x as $item){
                    $ui .= view_i(12969, $item['i__id'], null, $item,$control_enabled,null, $e);
                    array_push($my_x_ids, $item['i__id']);
                }
                $ui .= '</div>';

                $ui .= ( count($list_x) >= view_memory(6404,14527) ? '<script> $(document).ready(function () {x_sort_load(12969)}); </script>' : '<style> #list-in-12969 .x_sort {display:none !important;} </style>' ); //Need 2 or more to sort

            }



            if($source_is_e){

                //SAVED
                $i_notes_query = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
                    'x__type' => 12896,
                    'x__up' => $e['e__id'],
                ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC', 'x__id' => 'DESC'));
                if(count($i_notes_query)){
                    $ui .= '<div class="headline top-margin"><span class="icon-block">'.$e___11035[12896]['m__cover'].'</span>'.$e___11035[12896]['m__title'].'</div>';
                    $ui .= '<div class="row hideIfEmpty" id="list-in-12896">';
                    foreach($i_notes_query as $count => $i_notes) {
                        $ui .= view_i(12896, $i_notes['x__left'], null, $i_notes, $control_enabled);
                    }
                    $ui .= '</div>';

                    $ui .= ( count($i_notes_query) >= view_memory(6404,14527) ? '<script> $(document).ready(function () {x_sort_load(12896)}); </script>' : '<style> #list-in-12896 .x_sort {display:none !important;} </style>' ); //Need 2 or more to sort

                }

                //FEATURED IDEAS
                $ui .= view_i_featured($my_x_ids);

                //Info Boxes:
                $ui .= view_info_box();

            }

        } elseif(in_array($x__type, $this->config->item('n___4485'))){

            //IDEA NOTES
            $i_notes_filters = array(
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
                'x__type' => $x__type,
                'x__up' => $e['e__id'],
            );

            //COUNT ONLY
            $item_counters = $this->X_model->fetch($i_notes_filters, array('x__right'), 0, 0, array(), 'COUNT(i__id) as totals');
            $counter = $item_counters[0]['totals'];

            //$ui .= '<div class="headline"><span class="icon-block">'.$m['m__cover'].'</span>'.$m['m__title'].'</div>';
            $ui .= '<div class="row top-margin">';
            if($counter>0){

                $i_notes_query = $this->X_model->fetch($i_notes_filters, array('x__right'), view_memory(6404,11064), 0, array('i__spectrum' => 'DESC'));
                foreach($i_notes_query as $count => $i_notes) {
                    $ui .= view_i(4485, 0, null, $i_notes, $control_enabled);
                }

            }

            $ui .= '</div>';

        }

        if(!$counter && !in_array($x__type, $this->config->item('n___12574')) && !($x__type==12274 && $superpower_13422) && !($x__type==6255 && $source_is_e)){
            //Hide since Zero without exception @12574:
            continue;
        }



        $default_active = $x__type==$active_x__type;

        $tab_nav .= '<li class="nav-item'.( in_array($x__type, $this->config->item('n___14655')) ? ' pull-right ' : '' ).''.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a class="nav-x tab-nav-11089 tab-head-'.$x__type.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m['m__cover']).'" href="javascript:void(0);" onclick="loadtab(11089, '.$x__type.')" title="'.$m['m__title'].( strlen($m['m__message']) ? ' '.$m['m__message'] : '' ).'" data-toggle="tooltip" data-placement="top">&nbsp;'.$m['m__cover'].'&nbsp;<span class="en-type-counter-'.$x__type.'">'.view_number($counter).'</span>'.( intval($counter) ? '&nbsp;' : '' ).'</a></li>';


        $tab_content .= '<div class="tab-content tab-group-11089 tab-data-'.$x__type.( $default_active ? '' : ' hidden ' ).( $source_is_e ? ' no-border ' : '' ).'">';
        $tab_content .= $ui;
        $tab_content .= '</div>';

    }


    if($tab_nav){

        echo '<ul class="nav nav-tabs nav-sm '.( $source_is_e ? superpower_active(10939) : '' ).'">';
        echo $tab_nav;
        echo '</ul>';

        //Show All Tab Content:
        echo $tab_content;

    }

    echo '</div>';

    ?>