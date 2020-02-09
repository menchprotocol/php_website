
<?php

$en_all_6206 = $this->config->item('en_all_6206'); //Player Table
$en_all_4341 = $this->config->item('en_all_4341'); //Link Table
$en_all_2738 = $this->config->item('en_all_2738');
$en_all_6177 = $this->config->item('en_all_6177'); //Player Statuses
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
?>

<script>
    //Set global variables:
    var en_focus_filter = -1; //No filter, show all
    var en_focus_id = <?= $player['en_id'] ?>;
    var en_all_4592 = <?= json_encode($this->config->item('en_all_4592')) ?>;
</script>

<style>
    .en_child_icon_<?= $player['en_id'] ?>{ display:none; }
</style>

<script src="/application/views/play/play_coin.js?v=v<?= config_var(11060) ?>"
        type="text/javascript"></script>

<div class="container">

    <?php

    //NAME & STATUS
    $is_published = in_array($player['en_status_play_id'], $this->config->item('en_ids_7357'));


    //RIGHT
    echo '<div class="pull-right inline-block '.superpower_active(10967).'">';

        //REFERENCES
        $en_count_references = en_count_references($player['en_id']);
        if(count($en_count_references) > 0){
            $en_all_6194 = $this->config->item('en_all_6194');
            //Show this players connections:
            $ref_count = 0;
            foreach($en_count_references as $en_id=>$en_count){
                echo '<span data-toggle="tooltip" data-placement="bottom" title="Referenced as '.$en_all_6194[$en_id]['m_name'].' '.number_format($en_count, 0).' times">'.$en_all_6194[$en_id]['m_icon'] . ' '. echo_number($en_count).'</span>&nbsp;';
                $ref_count++;
            }
        }

        //Modify
        echo '<a href="javascript:void(0);" onclick="en_modify_load(' . $player['en_id'] . ',0)" class="btn btn-play btn-five icon-block-lg" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12275]['m_name'].'">'.$en_all_11035[12275]['m_icon'].'</a>';

    echo '</div>';


    //LEFT
    echo '<h1 class="'.extract_icon_color($player['en_icon']).' pull-left inline-block"><span class="icon-block en_ui_icon_'.$player['en_id'].'">'.echo_en_icon($player['en_icon']).'</span><span class="icon-block en_status_play_id_' . $player['en_id'] . ( $is_published ? ' hidden ' : '' ).'"><span data-toggle="tooltip" data-placement="bottom" title="'.$en_all_6177[$player['en_status_play_id']]['m_name'].': '.$en_all_6177[$player['en_status_play_id']]['m_desc'].'">' . $en_all_6177[$player['en_status_play_id']]['m_icon'] . '</span></span><span class="en_name_'.$player['en_id'].'">'.$player['en_name'].'</span></h1>';


    echo '<div class="doclear">&nbsp;</div>';
    ?>




    <div id="modifybox" class="fixed-box hidden" player-id="0" player-link-id="0">

        <h5 class="badge badge-h edit-header"><i class="fas fa-cog"></i> Modify</h5>
        <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
            <a href="javascript:void(0);" onclick="modify_cancel()"><i class="fas fa-times-circle"></i></a>
        </div>
        <div class="grey-box">

            <div class="row">
                <div class="col-md-6">

                    <div class="inline-box">



                        <!-- Player Status -->
                        <span class="mini-header"><?= $en_all_6206[6177]['m_icon'].' '.$en_all_6206[6177]['m_name'] ?></span>
                        <select class="form-control border" id="en_status_play_id">
                            <?php
                            foreach($this->config->item('en_all_6177') /* Player Statuses */ as $en_id => $m){
                                echo '<option value="' . $en_id . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <div class="notify_en_remove hidden">

                            <input type="hidden" id="en_link_count" value="0" />
                            <div class="alert alert-danger" style="margin:5px 0px; padding:7px;">
                                <i class="fad fa-exclamation-triangle"></i>
                                Saving will archive this player and UNLINK ALL <span class="player_remove_stats" style="display:inline-block; padding: 0;"></span> links
                            </div>

                            <span class="mini-header"><span class="tr_in_link_title"></span> Merge Player Into:</span>
                            <input style="padding-left:3px;" type="text" class="form-control algolia_search border en_quick_search" id="en_merge" value="" placeholder="Search player to merge..." />

                        </div>



                        <!-- Player Name -->
                        <span class="mini-header" style="margin-top:20px;"><?= $en_all_6206[6197]['m_icon'].' '.$en_all_6206[6197]['m_name'] ?> [<span style="margin:0 0 10px 0;"><span id="charEnNum">0</span>/<?= config_var(11072) ?></span>]</span>
                        <span class="white-wrapper">
                                <textarea class="form-control text-edit border montserrat doupper" id="en_name"
                                          onkeyup="en_name_word_count()" data-lpignore="true"
                                          style="height:66px; min-height:66px;">
                                </textarea>
                            </span>



                        <!-- Player Icon -->
                        <span class="mini-header"><?= $en_all_6206[6198]['m_icon'].' '.$en_all_6206[6198]['m_name'] ?>

                                <i class="fal fa-info-circle" data-toggle="tooltip" title="<?= is_valid_icon(null, true) ?> Click to see Font-Awesome Icons in a new window." data-placement="right"></i>

                                <a href="javascript:void(0);" style="margin-left: 5px;" onclick="$('#en_icon').val($('#en_icon').val() + '<i class=&quot;far fa-&quot;></i>' )" data-toggle="tooltip" title="Insert blank Font-Awesome HTML code" data-placement="top"><i class="far fa-edit"></i><b>FA</b></a>

                                <a href="https://fontawesome.com/icons" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Visit Font-Awesome website for a full list of icons and their HTML code" data-placement="top"><i class="fas fa-external-link"></i></a>

                            </span>
                        <div class="form-group label-floating is-empty"
                             style="margin:1px 0 10px;">
                            <div class="input-group border">
                                <input type="text" id="en_icon" value=""
                                       maxlength="<?= config_var(11072) ?>" data-lpignore="true" placeholder=""
                                       class="form-control">
                                <span class="input-group-addon addon-lean addon-grey icon-demo icon-block"></span>
                            </div>
                        </div>



                    </div>

                </div>
                <div class="col-md-6 en-has-tr">

                    <div>

                        <div class="inline-box">


                            <span class="mini-header"><?= $en_all_4341[6186]['m_icon'].' '.$en_all_4341[6186]['m_name'] ?></span>
                            <select class="form-control border" id="ln_status_play_id">
                                <?php
                                foreach($this->config->item('en_all_6186') /* Link Statuses */ as $en_id => $m){
                                    echo '<option value="' . $en_id . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                                }
                                ?>
                            </select>

                            <div class="notify_unlink_en hidden">
                                <div class="alert alert-warning" style="margin:5px 0px; padding:7px;">
                                    <i class="fad fa-exclamation-triangle"></i>
                                    Saving will unlink player
                                </div>
                            </div>




                            <form class="drag-box" method="post" enctype="multipart/form-data">
                                <span class="mini-header" style="margin-top: 20px;"><?= $en_all_4341[4372]['m_icon'].' '.$en_all_4341[4372]['m_name'] ?> [<span style="margin:0 0 10px 0;"><span id="charln_contentNum">0</span>/<?= config_var(11073) ?></span>]</span>
                                <span class="white-wrapper">
                                    <textarea class="form-control text-edit border" id="ln_content"
                                              maxlength="<?= config_var(11073) ?>" data-lpignore="true"
                                              placeholder="Write, Drop a File or Paste URL"
                                              style="height:126px; min-height:126px;">
                                    </textarea>
                                </span>

                                <span><input class="inputfile" type="file" name="file" id="enFile" /><label class="" for="enFile" data-toggle="tooltip" title="Upload files up to <?= config_var(11063) ?> MB" data-placement="top"><i class="fal fa-cloud-upload"></i> Upload</label></span>
                            </form>


                            <span class="mini-header"><?= $en_all_4341[4593]['m_icon'].' '.$en_all_4341[4593]['m_name'] ?></span>
                            <span id="en_type_link_id"></span>
                            <p id="en_link_preview"></p>



                        </div>

                    </div>

                </div>

            </div>

            <table>
                <tr>
                    <td class="save-td"><a href="javascript:en_modify_save();" class="btn btn-play btn-save">Save</a></td>
                    <td class="save-result-td"><span class="save_player_changes"></span></td>
                </tr>
            </table>

        </div>

    </div>


    <div id="message-frame" class="fixed-box hidden" player-id="">

        <h5 class="badge badge-h" data-toggle="tooltip"
            title="Message management can only be done using Ideas. Player messages are listed below for view-only"
            data-placement="bottom"><i class="fas fa-comment-plus"></i> Player References within Idea Notes
        </h5>
        <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
            <a href="#" onclick="modify_cancel()"><i class="fas fa-times-circle"></i></a>
        </div>
        <div class="grey-box">
            <div id="loaded-messages"></div>
        </div>

    </div>




    <?php

    $col_num = 0;
    echo '<div class="row">';
    foreach ($this->config->item('en_all_11088') as $en_id => $m){

        $col_num++;
        if($col_num==1){
            //PLAY HEADER already printed above...
            continue;
        }
        $tab_content = '';
        $default_active_found = false;
        $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m['m_parents']);
        $activated_tabs = array();


        echo '<div class="col-lg-12 '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'">';

        echo '<ul class="nav nav-tabs nav-tabs-sm">';

        foreach ($this->config->item('en_all_'.$en_id) as $en_id2 => $m2){

            //Is this a caret menu?
            if(in_array(11040 , $m2['m_parents'])){
                echo echo_caret($en_id2, $m2, $player['en_id']);
                continue;
            }


            //Determine counter:
            $default_active = false;
            $counter = null; //Assume no counters
            $this_tab = '';



            //PLAY
            if($en_id2==11030){

                //PLAY PARENT

                $default_active = true; //LEFT

                $play__parents = $this->READ_model->ln_fetch(array(
                    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
                    'ln_child_play_id' => $player['en_id'],
                ), array('en_parent'), 0);

                $counter = count($play__parents);

                $this_tab .= '<div id="list-parent" class="list-group ">';
                foreach ($play__parents as $en) {
                    $this_tab .= echo_en($en,true);
                }

                //Input to add new parents:
                $this_tab .= '<div id="new-parent" class="list-group-item no-side-padding '.superpower_active(10967).'">
                    <div class="form-group is-empty"><input type="text" class="form-control new-player-input algolia_search form-control-thick dotransparent" data-lpignore="true" placeholder="ADD PLAYER PASTE URL"></div>
                    <div class="algolia_pad_search hidden"></div>
            </div>';

                $this_tab .= '</div>';

            } elseif($en_id2==11029){

                //PLAY CHILD

                //COUNT TOTAL
                $child_links = $this->READ_model->ln_fetch(array(
                    'ln_parent_play_id' => $player['en_id'],
                    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
                ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as totals');
                $counter = $child_links[0]['totals'];

                //Active if count exists and not already activated.
                $authored_ideas = $this->READ_model->ln_fetch(array(
                    'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Statuses Public
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_type_play_id' => 4983,
                    'ln_parent_play_id' => $player['en_id'],
                ), array('in_child'), 0, 0, array(), 'COUNT(in_id) as totals');

                $default_active = ( $counter || !$authored_ideas[0]['totals'] );

                if($default_active){
                    array_push($activated_tabs, $en_id2);
                }

                $play__children = $this->READ_model->ln_fetch(array(
                    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
                    'ln_parent_play_id' => $player['en_id'],
                ), array('en_child'), config_var(11064), 0, array('ln_order' => 'ASC', 'en_name' => 'ASC'));

                $this_tab .= '<div id="list-children" class="list-group">';

                foreach ($play__children as $en) {
                    $this_tab .= echo_en($en,false);
                }
                if ($counter > count($play__children)) {
                    $this_tab .= echo_en_load_more(1, config_var(11064), $counter);
                }

                //Input to add new child:
                $this_tab .= '<div id="new-children" class="list-group-item no-side-padding '.superpower_active(10967).'">


            <div class="form-group is-empty"><input type="text" class="form-control new-player-input form-control-thick algolia_search dotransparent" data-lpignore="true" placeholder="ADD PLAYER"></div>
            <div class="algolia_pad_search hidden"></div>
            
            
    </div>';
                $this_tab .= '</div>';







                //Fetch current count for each status from DB:
                $player_count = $this->PLAY_model->en_child_count($player['en_id'], $this->config->item('en_ids_7358') /* Player Statuses Active */);
                $child_en_filters = $this->READ_model->ln_fetch(array(
                    'ln_parent_play_id' => $player['en_id'],
                    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
                ), array('en_child'), 0, 0, array('en_status_play_id' => 'ASC'), 'COUNT(en_id) as totals, en_status_play_id', 'en_status_play_id');

                //Only show filtering UI if we find child players with different statuses (Otherwise no need to filter):
                if (count($child_en_filters) > 0 && $child_en_filters[0]['totals'] < $player_count) {

                    //Load status definitions:
                    $en_all_6177 = $this->config->item('en_all_6177'); //Player Statuses

                    //Add 2nd Navigation to UI
                    $tab_content .= '<div class="nav nav-tabs nav-tabs-sm '.superpower_active(10986).'">';

                    //Show fixed All button:
                    $tab_content .= '<li class="nav-item"><a href="#" onclick="en_filter_status(-1)" class="nav-link u-status-filter active u-status--1" data-toggle="tooltip" data-placement="top" title="View all players"><i class="fas fa-asterisk"></i><span class="show-max"> All</span> <span class="counter-11029">' . $player_count . '</span></a></li>';

                    //Show each specific filter based on DB counts:
                    foreach ($child_en_filters as $c_c) {
                        $st = $en_all_6177[$c_c['en_status_play_id']];
                        $tab_content .= '<li class="nav-item"><a href="#status-' . $c_c['en_status_play_id'] . '" onclick="en_filter_status(' . $c_c['en_status_play_id'] . ')" class="nav-link u-status-filter u-status-' . $c_c['en_status_play_id'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['m_desc'] . '">' . $st['m_icon'] . '<span class="show-max"> ' . $st['m_name'] . '</span> <span class="count-u-status-' . $c_c['en_status_play_id'] . '">' . $c_c['totals'] . '</span></a></li>';
                    }

                    $tab_content .= '</div>';

                }

            } elseif(in_array($en_id2, $this->config->item('en_ids_4485'))){

                //IDEA NOTES
                $idea_note_filters = array(
                    'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Statuses Public
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_type_play_id' => $en_id2,
                    'ln_parent_play_id' => $player['en_id'],
                    //'(ln_owner_play_id='.$player['en_id'].' OR ln_child_play_id='.$player['en_id'].' OR ln_parent_play_id='.$player['en_id'].')' => null,
                );

                //COUNT ONLY
                $item_counters = $this->READ_model->ln_fetch($idea_note_filters, array('in_child'), 0, 0, array(), 'COUNT(in_id) as totals');
                $counter = $item_counters[0]['totals'];

                //SHOW LASTEST 100
                if($counter>0){

                    $this_tab .= '<div class="list-group">';
                    foreach ($this->READ_model->ln_fetch($idea_note_filters, array('in_child'), config_var(11064), 0, array(
                        'in_status_play_id' => 'DESC',
                        'in_title'          => 'ASC'
                    )) as $idea_note) {
                        if(in_array($en_id2, $this->config->item('en_ids_12321'))){

                            $this_tab .= echo_in_read($idea_note);

                        } elseif(in_array($en_id2, $this->config->item('en_ids_12322'))){

                            //Include the message:
                            $footnotes = null;
                            if($idea_note['ln_content']){
                                $footnotes .= '<div class="message_content">';
                                $footnotes .= $this->READ_model->dispatch_message($idea_note['ln_content']);
                                $footnotes .= '</div>';
                            }

                            $this_tab .= echo_in_read($idea_note, false, $footnotes);

                        }
                    }
                    $this_tab .= '</div>';

                } elseif($default_active){

                    $this_tab .= '<div class="alert alert-warning">No ideas featured yet.</div>';

                }

            } elseif(in_array($en_id2, $this->config->item('en_ids_12410'))){

                $join_objects = array();
                $match_columns = array(
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_'.$en_id2)) . ')' => null,
                    'ln_owner_play_id' => $player['en_id'],
                );

                if($en_id2 == 12273){
                    $match_columns['in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')'] = null; //Idea Statuses Public
                    $join_objects = array('in_child');
                }

                //READER READS & BOOKMARKS
                $item_counters = $this->READ_model->ln_fetch($match_columns, $join_objects, 1, 0, array(), 'COUNT(ln_id) as totals');

                $counter = $item_counters[0]['totals'];

                $default_active = ( in_array($en_id2, $this->config->item('en_ids_12440')) && ($counter>0 || !count($this->READ_model->ln_fetch(array(
                            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
                            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                            'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
                            'ln_parent_play_id' => $player['en_id'],
                        ), array('en_child'), 1))));

                if($default_active){
                    array_push($activated_tabs, $en_id2);
                }

                if($counter > 0){

                    //Dynamic Loading when clicked:
                    $this_tab .= '<div class="dynamic-reads"></div>';

                } else {

                    //Inform that nothing was found:
                    $en_all_12410 = $this->config->item('en_all_12410');
                    $this_tab .= '<div class="alert alert-warning">No <span class="montserrat '.extract_icon_color($en_all_12410[$en_id2]['m_icon']).'">'.$en_all_12410[$en_id2]['m_icon'].' '.$en_all_12410[$en_id2]['m_name'].'</span> added yet.</div>';

                }

            } elseif($en_id2==4997){


                $this_tab .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important;"><div class="inline-box">';

                $dropdown_options = '';
                $input_options = '';
                $counter = 0;

                foreach ($this->config->item('en_all_4997') as $action_en_id => $mass_action_en) {

                    $counter++;
                    $dropdown_options .= '<option value="' . $action_en_id . '">' .$mass_action_en['m_name'] . '</option>';


                    //Start with the input wrapper:
                    $input_options .= '<span id="mass_id_'.$action_en_id.'" class="inline-block '. ( $counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';

                    $input_options .= '<i class="fal fa-info-circle" data-toggle="tooltip" data-placement="right" title="'.$mass_action_en['m_desc'].'"></i> ';

                    if(in_array($action_en_id, array(5000, 5001, 10625))){

                        //String Find and Replace:

                        //Find:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" placeholder="Search" style="width: 145px;" class="form-control border">';

                        //Replace:
                        $input_options .= '<input type="text" name="mass_value2_'.$action_en_id.'" placeholder="Replace" stycacle="width: 145px;" class="form-control border">';


                    } elseif(in_array($action_en_id, array(5981, 5982))){

                        //Player search box:

                        //String command:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" style="width:300px;" placeholder="Search players..." class="form-control algolia_search en_quick_search border">';

                        //We don't need the second value field here:
                        $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';


                    } elseif($action_en_id == 11956){

                        //IF HAS THIS
                        $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" style="width:300px;" placeholder="IF THIS PLAYER..." class="form-control algolia_search en_quick_search border">';

                        //ADD THIS
                        $input_options .= '<input type="text" name="mass_value2_'.$action_en_id.'" style="width:300px;" placeholder="ADD THIS PLAYER..." class="form-control algolia_search en_quick_search border">';


                    } elseif($action_en_id == 5003){

                        //Player Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_en_id.'" class="form-control border">';
                        $input_options .= '<option value="">Set Condition...</option>';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($this->config->item('en_all_6177') /* Player Statuses */ as $en_id3 => $m3){
                            $input_options .= '<option value="'.$en_id3.'">Update All '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($this->config->item('en_all_6177') /* Player Statuses */ as $en_id3 => $m3){
                            $input_options .= '<option value="'.$en_id3.'">Set to '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';


                    } elseif($action_en_id == 5865){

                        //Link Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_en_id.'" class="form-control border">';
                        $input_options .= '<option value="">Set Condition...</option>';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($this->config->item('en_all_6186') /* Link Statuses */ as $en_id3 => $m3){
                            $input_options .= '<option value="'.$en_id3.'">Update All '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($this->config->item('en_all_6186') /* Link Statuses */ as $en_id3 => $m3){
                            $input_options .= '<option value="'.$en_id3.'">Set to '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';


                    } else {

                        //String command:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" style="width:300px;" placeholder="String..." class="form-control border">';

                        //We don't need the second value field here:
                        $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';

                    }

                    $input_options .= '</span>';

                }

                $this_tab .= '<select class="form-control border inline-block" name="mass_action_en_id" id="set_mass_action">';
                $this_tab .= $dropdown_options;
                $this_tab .= '</select>';

                $this_tab .= $input_options;

                $this_tab .= '<input type="submit" value="GO" class="btn btn-play inline-block">';

                $this_tab .= '</div></form>';

                if(isset($play__children)){
                    //Also add invisible child IDs for quick copy/pasting:
                    $this_tab .= '<div style="color:transparent;">';
                    foreach ($play__children as $en) {
                        $this_tab .= $en['en_id'].',';
                    }
                    $this_tab .= '</div>';
                }
            }

            //Don't show empty tabs:
            $must_show = in_array($en_id2, $this->config->item('en_ids_12391'));
            if(!$must_show){
                $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m2['m_parents']);
                if((count($superpower_actives) && !superpower_assigned(end($superpower_actives))) || intval($counter) < 1){
                    continue;
                }
            }


            $show_tab_names = in_array($en_id2, $this->config->item('en_ids_11084')); //Should we show tab names?

            echo '<li class="nav-item '.( !$must_show && count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a class="nav-link tab-nav-'.$en_id.' tab-head-'.$en_id2.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m2['m_icon']).'" href="javascript:void(0);" onclick="loadtab('.$en_id.','.$en_id2.',0,'.$player['en_id'].')" data-toggle="tooltip" data-placement="top" title="'.( $show_tab_names ? '' : $m2['m_name'] ).'">'.$m2['m_icon'].( is_null($counter) ? '' : ' <span class="counter-'.$en_id2.superpower_active(10939).'">'.echo_number($counter).'</span>' ).( $show_tab_names ? ' '.$m2['m_name'] : '' ).'</a></li>';


            $tab_content .= '<div class="tab-content tab-group-'.$en_id.' tab-data-'.$en_id2.( $default_active ? '' : ' hidden ' ).'">';
            $tab_content .= $this_tab;
            $tab_content .= '</div>';

            if($default_active){
                $default_active_found = true;
            }
        }

        echo '</ul>';

        echo $tab_content;
        echo '</div>';
    }

    echo '</div>';



    //FOR EDITING ONLY (HIDDEN FROM UI):
    echo '<div class="hidden">'.echo_en($player).'</div>';

    ?>

</div>