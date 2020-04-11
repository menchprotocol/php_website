
<?php

$en_all_6206 = $this->config->item('en_all_6206'); //Player Table
$en_all_4341 = $this->config->item('en_all_4341'); //Link Table
$en_all_2738 = $this->config->item('en_all_2738');
$en_all_6177 = $this->config->item('en_all_6177'); //Source Status
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH  NAVIGATION
?>

<script>
    //Set global variables:
    var en_focus_filter = -1; //No filter, show all
    var en_focus_id = <?= $source['en_id'] ?>;
    var en_all_4592 = <?= json_encode($this->config->item('en_all_4592')) ?>;
</script>

<style>
    .en_child_icon_<?= $source['en_id'] ?>{ display:none; }
</style>

<script src="/application/views/source/source_coin.js?v=v<?= config_var(11060) ?>"
        type="text/javascript"></script>

<div class="container">

    <?php

    //NAME & STATUS
    $is_published = in_array($source['en_status_source_id'], $this->config->item('en_ids_7357'));


    //LEFT
    echo '<h1 class="'.extract_icon_color($source['en_icon']).' pull-left inline-block" style="padding-top:5px;"><span class="icon-block en_ui_icon_'.$source['en_id'].'">'.echo_en_icon($source['en_icon']).'</span><span class="icon-block en_status_source_id_' . $source['en_id'] . ( $is_published ? ' hidden ' : '' ).'"><span data-toggle="tooltip" data-placement="bottom" title="'.$en_all_6177[$source['en_status_source_id']]['m_name'].': '.$en_all_6177[$source['en_status_source_id']]['m_desc'].'">' . $en_all_6177[$source['en_status_source_id']]['m_icon'] . '</span></span><span class="en_name_'.$source['en_id'].'">'.$source['en_name'].'</span></h1>';



    //RIGHT
    echo '<div class="pull-right inline-block '.superpower_active(10967).'">';

        //REFERENCES
        $en_count_references = en_count_references($source['en_id']);
        if(count($en_count_references) > 0){
            $en_all_6194 = $this->config->item('en_all_6194');
            //Show this sources connections:
            $ref_count = 0;
            foreach($en_count_references as $en_id=>$en_count){
                echo '<span class="montserrat doupper '.extract_icon_color($en_all_6194[$en_id]['m_icon']).'" data-toggle="tooltip" data-placement="bottom" title="Referenced as '.$en_all_6194[$en_id]['m_name'].' '.number_format($en_count, 0).' times">'.$en_all_6194[$en_id]['m_icon'] . ' '. echo_number($en_count).'</span>&nbsp;';
                $ref_count++;
            }
        }

        //Modify
        echo '<a href="javascript:void(0);" onclick="en_modify_load(' . $source['en_id'] . ',0)" class="btn btn-source btn-five icon-block-lg" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12275]['m_name'].'">'.$en_all_11035[12275]['m_icon'].'</a>';

    echo '</div>';


    echo '<div class="doclear">&nbsp;</div>';
    ?>




    <div id="modifybox" class="fixed-box hidden" source-id="0" source-link-id="0" style="padding: 5px;">

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
                        <select class="form-control border" id="en_status_source_id">
                            <?php
                            foreach($this->config->item('en_all_6177') /* Source Status */ as $en_id => $m){
                                echo '<option value="' . $en_id . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <div class="notify_en_remove hidden">

                            <input type="hidden" id="en_link_count" value="0" />
                            <div class="alert alert-danger" style="margin:5px 0px; padding:7px;">
                                <i class="fad fa-exclamation-triangle"></i>
                                Saving will archive this source and UNLINK ALL <span class="source_remove_stats" style="display:inline-block; padding: 0;"></span> links
                            </div>

                            <span class="mini-header"><span class="tr_in_link_title"></span> Merge Source Into:</span>
                            <input style="padding-left:3px;" type="text" class="form-control algolia_search border en_quick_search" id="en_merge" value="" placeholder="Search source to merge..." />

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

                                <a href="javascript:void(0);" style="margin-left: 5px;" onclick="$('#en_icon').val($('#en_icon').val() + '<i class=&quot;fad fa-&quot;></i>' )" data-toggle="tooltip" title="Insert blank Font-Awesome HTML code" data-placement="top"><i class="far fa-edit"></i><b>FA</b></a>

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
                            <select class="form-control border" id="ln_status_source_id">
                                <?php
                                foreach($this->config->item('en_all_6186') /* Transaction Status */ as $en_id => $m){
                                    echo '<option value="' . $en_id . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                                }
                                ?>
                            </select>

                            <div class="notify_unlink_en hidden">
                                <div class="alert alert-warning" style="margin:5px 0px; padding:7px;">
                                    <i class="fad fa-exclamation-triangle"></i>
                                    Saving will unlink source
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
                    <td class="save-td"><a href="javascript:en_modify_save();" class="btn btn-source btn-save">Save</a></td>
                    <td class="save-result-td"><span class="save_source_changes"></span></td>
                </tr>
            </table>

        </div>

    </div>


    <div id="message-frame" class="fixed-box hidden" source-id="">

        <h5 class="badge badge-h" data-toggle="tooltip"
            title="Message management can only be done using Notes. Source messages are listed below for view-only"
            data-placement="bottom"><i class="fas fa-comment-plus"></i> Source References within Note Pads
        </h5>
        <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
            <a href="#" onclick="modify_cancel()"><i class="fas fa-times-circle"></i></a>
        </div>
        <div class="grey-box">
            <div id="loaded-messages"></div>
        </div>

    </div>





    <?php
    //Print Play Layout
    foreach ($this->config->item('en_all_11089') as $en_id => $m){

        //Don't show empty tabs:
        $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m['m_parents']);
        if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
            continue;
        }

        $this_tab = null;
        $counter = 0;

        //SOURCE
        if($en_id==12412){

            //Play Header Skip as already printed above:
            continue;

        } elseif($en_id==11030){

            $source__parents = $this->READ_model->ln_fetch(array(
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
                'ln_child_source_id' => $source['en_id'],
            ), array('en_parent'), 0);

            $counter = count($source__parents);

            //SOURCE PARENT

            $this_tab .= '<div id="list-parent" class="list-group ">';
            foreach ($source__parents as $en) {
                $this_tab .= echo_en($en,true);
            }

            //Input to add new parents:
            $this_tab .= '<div id="new-parent" class="list-group-item itemsource no-side-padding '.superpower_active(10967).'">
                <div class="form-group is-empty"><input type="text" class="form-control new-source-input algolia_search form-control-thick dotransparent" data-lpignore="true" placeholder="+ SOURCE"></div>
                <div class="algolia_pad_search hidden"></div>
        </div>';

            $this_tab .= '</div>';

        } elseif($en_id==11029){

            //SOURCE CHILD

            //COUNT TOTAL
            $child_links = $this->READ_model->ln_fetch(array(
                'ln_parent_source_id' => $source['en_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
            ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as totals');
            $counter = $child_links[0]['totals'];


            $source__children = $this->READ_model->ln_fetch(array(
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
                'ln_parent_source_id' => $source['en_id'],
            ), array('en_child'), config_var(11064), 0, array('ln_order' => 'ASC', 'en_name' => 'ASC'));



            //Fetch current count for each status from DB:
            $source_count = $this->SOURCE_model->en_child_count($source['en_id'], $this->config->item('en_ids_7358') /* Source Status Active */);
            $child_en_filters = $this->READ_model->ln_fetch(array(
                'ln_parent_source_id' => $source['en_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
            ), array('en_child'), 0, 0, array('en_status_source_id' => 'ASC'), 'COUNT(en_id) as totals, en_status_source_id', 'en_status_source_id');

            //Only show filtering UI if we find child sources with different Status (Otherwise no need to filter):
            if (count($child_en_filters) > 0 && $child_en_filters[0]['totals'] < $source_count) {

                //Load status definitions:
                $en_all_6177 = $this->config->item('en_all_6177'); //Source Status

                //Add 2nd Navigation to UI
                $this_tab .= '<div class="nav nav-pills nav-sm '.superpower_active(10986).'">';

                //Show fixed All button:
                $this_tab .= '<li class="nav-item"><a href="#" onclick="en_filter_status(-1)" class="nav-link u-status-filter active u-status--1" data-toggle="tooltip" data-placement="top" title="View all sources"><i class="fas fa-asterisk"></i><span class="show-max"> All</span> <span class="counter-11029">' . $source_count . '</span></a></li>';

                //Show each specific filter based on DB counts:
                foreach ($child_en_filters as $c_c) {
                    $st = $en_all_6177[$c_c['en_status_source_id']];
                    $this_tab .= '<li class="nav-item"><a href="#status-' . $c_c['en_status_source_id'] . '" onclick="en_filter_status(' . $c_c['en_status_source_id'] . ')" class="nav-link u-status-filter u-status-' . $c_c['en_status_source_id'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['m_desc'] . '">' . $st['m_icon'] . '<span class="show-max"> ' . $st['m_name'] . '</span> <span class="count-u-status-' . $c_c['en_status_source_id'] . '">' . $c_c['totals'] . '</span></a></li>';
                }

                $this_tab .= '</div>';

            }


            $this_tab .= '<div id="list-children" class="list-group">';

            foreach ($source__children as $en) {
                $this_tab .= echo_en($en,false);
            }
            if ($counter > count($source__children)) {
                $this_tab .= echo_en_load_more(1, config_var(11064), $counter);
            }

            //Input to add new child:
            $this_tab .= '<div id="new-children" class="list-group-item itemsource no-side-padding '.superpower_active(10967).'">


        <div class="form-group is-empty"><input type="text" class="form-control new-source-input form-control-thick algolia_search dotransparent" data-lpignore="true" placeholder="+ SOURCE"></div>
        <div class="algolia_pad_search hidden"></div>
        
        
</div>';
            $this_tab .= '</div>';



        } elseif(in_array($en_id, $this->config->item('en_ids_4485'))){

            //Note Pads
            $in_pads_filters = array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Note Status Active
                'ln_type_source_id' => $en_id,
                '(ln_creator_source_id='.$source['en_id'].' OR ln_child_source_id='.$source['en_id'].' OR ln_parent_source_id='.$source['en_id'].')' => null,
            );

            //COUNT ONLY
            $item_counters = $this->READ_model->ln_fetch($in_pads_filters, array('in_child'), 0, 0, array(), 'COUNT(in_id) as totals');
            $counter = $item_counters[0]['totals'];

            //SHOW LASTEST 100
            if($counter>0){

                $this_tab .= '<div class="list-group">';
                foreach ($this->READ_model->ln_fetch($in_pads_filters, array('in_child'), config_var(11064), 0, array('in_weight' => 'DESC')) as $in_pads) {
                    if(in_array($en_id, $this->config->item('en_ids_12321'))){

                        $this_tab .= echo_in_read($in_pads);

                    } elseif(in_array($en_id, $this->config->item('en_ids_12322'))){

                        //Include the message:
                        $infobar_details = null;
                        if($in_pads['ln_content']){
                            $infobar_details .= '<div class="message_content">';
                            $infobar_details .= $this->READ_model->dispatch_message($in_pads['ln_content']);
                            $infobar_details .= '</div>';
                        }

                        $this_tab .= echo_in_read($in_pads, false, $infobar_details);

                    }
                }
                $this_tab .= '</div>';

            } else {

                $this_tab .= '<div class="alert alert-warning">No notes yet.</div>';

            }

        } elseif($en_id == 7347 /* READ LIST */){

            $player_reads = $this->READ_model->ln_fetch(array(
                'ln_creator_source_id' => $source['en_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Note Set
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Note Status Public
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            ), array('in_parent'), 1, 0, array(), 'COUNT(ln_id) as totals');
            $counter = $player_reads[0]['totals'];

        } elseif(in_array($en_id, $this->config->item('en_ids_12410'))){

            //SOURCE COINS (READ & NOTE)

            $join_objects = array();
            $match_columns = array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_'.$en_id)) . ')' => null,
            );

            if($en_id == 12273){
                //Note Coins
                $match_columns['ln_parent_source_id'] = $source['en_id'];
                $match_columns['in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')'] = null; //Note Status Public
                $join_objects = array('in_child');
            } elseif($en_id == 6255){
                //Read Coins:
                $match_columns['ln_creator_source_id'] = $source['en_id'];
            }

            //READER READS & BOOKMARKS
            $item_counters = $this->READ_model->ln_fetch($match_columns, $join_objects, 1, 0, array(), 'COUNT(ln_id) as totals');

            $counter = $item_counters[0]['totals'];

            if($counter > 0){

                //Dynamic Loading when clicked:
                $read_history_ui = $this->READ_model->read_history_ui($en_id, 0, $source['en_id']);
                if($read_history_ui['status']){
                    $this_tab .= $read_history_ui['message'];
                }

            }

        } elseif($en_id==4997 /* SOURCE UPDATER */){



            $dropdown_options = '';
            $input_options = '';
            $counter = 0;

            foreach ($this->config->item('en_all_4997') as $action_en_id => $mass_action_en) {

                $counter++;
                $dropdown_options .= '<option value="' . $action_en_id . '">' .$mass_action_en['m_name'] . '</option>';
                $is_upper = ( in_array($action_en_id, $this->config->item('en_ids_12577') /* SOURCE UPDATER UPPERCASE */) ? ' montserrat doupper ' : false );


                //Start with the input wrapper:
                $input_options .= '<span id="mass_id_'.$action_en_id.'" title="'.$mass_action_en['m_desc'].'" class="inline-block '. ( $counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';




                if(in_array($action_en_id, array(5000, 5001, 10625))){

                    //String Find and Replace:

                    //Find:
                    $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" placeholder="Search" class="form-control border '.$is_upper.'">';

                    //Replace:
                    $input_options .= '<input type="text" name="mass_value2_'.$action_en_id.'" placeholder="Replace" class="form-control border '.$is_upper.'">';


                } elseif(in_array($action_en_id, array(5981, 5982))){

                    //Player search box:

                    //String command:
                    $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'"  placeholder="Search sources..." class="form-control algolia_search en_quick_search border '.$is_upper.'">';

                    //We don't need the second value field here:
                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';


                } elseif($action_en_id == 11956){

                    //IF HAS THIS
                    $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'"  placeholder="IF THIS SOURCE..." class="form-control algolia_search en_quick_search border '.$is_upper.'">';

                    //ADD THIS
                    $input_options .= '<input type="text" name="mass_value2_'.$action_en_id.'"  placeholder="ADD THIS SOURCE..." class="form-control algolia_search en_quick_search border '.$is_upper.'">';


                } elseif($action_en_id == 5003){

                    //Player Status update:

                    //Find:
                    $input_options .= '<select name="mass_value1_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set Condition...</option>';
                    $input_options .= '<option value="*">Update All Statuses</option>';
                    foreach($this->config->item('en_all_6177') /* Source Status */ as $en_id3 => $m3){
                        $input_options .= '<option value="'.$en_id3.'">Update All '.$m3['m_name'].'</option>';
                    }
                    $input_options .= '</select>';

                    //Replace:
                    $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set New Status...</option>';
                    foreach($this->config->item('en_all_6177') /* Source Status */ as $en_id3 => $m3){
                        $input_options .= '<option value="'.$en_id3.'">Set to '.$m3['m_name'].'</option>';
                    }
                    $input_options .= '</select>';


                } elseif($action_en_id == 5865){

                    //Transaction Status update:

                    //Find:
                    $input_options .= '<select name="mass_value1_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set Condition...</option>';
                    $input_options .= '<option value="*">Update All Statuses</option>';
                    foreach($this->config->item('en_all_6186') /* Transaction Status */ as $en_id3 => $m3){
                        $input_options .= '<option value="'.$en_id3.'">Update All '.$m3['m_name'].'</option>';
                    }
                    $input_options .= '</select>';

                    //Replace:
                    $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set New Status...</option>';
                    foreach($this->config->item('en_all_6186') /* Transaction Status */ as $en_id3 => $m3){
                        $input_options .= '<option value="'.$en_id3.'">Set to '.$m3['m_name'].'</option>';
                    }
                    $input_options .= '</select>';


                } else {

                    //String command:
                    $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'"  placeholder="String..." class="form-control border '.$is_upper.'">';

                    //We don't need the second value field here:
                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';

                }

                $input_options .= '</span>';

            }

            $this_tab .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important; margin-left: 33px;">';
            $this_tab .= '<div class="inline-box">';

            //Drop Down
            $this_tab .= '<select class="form-control border" name="mass_action_en_id" id="set_mass_action">';
            $this_tab .= $dropdown_options;
            $this_tab .= '</select>';

            $this_tab .= $input_options;

            $this_tab .= '<div><input type="submit" value="APPLY" class="btn btn-source inline-block"></div>';

            $this_tab .= '</div>';
            $this_tab .= '</form>';

            if(isset($source__children)){
                //Also add invisible child IDs for quick copy/pasting:
                $this_tab .= '<div style="color:transparent;">';
                foreach ($source__children as $en) {
                    $this_tab .= $en['en_id'].',';
                }
                $this_tab .= '</div>';
            }

            $counter = 0;
        }

        if(!$counter && (!in_array($en_id, $this->config->item('en_ids_12574')) || !$session_en)){
            continue;
        }

        $auto_expand_tab = in_array($en_id, $this->config->item('en_ids_12571'));

        //HEADER
        echo '<div class="'.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'">';
        echo '<div class="read-topic"><a href="javascript:void(0);" onclick="$(\'.contentTab'.$en_id.'\').toggleClass(\'hidden\')"><span class="icon-block">'.$m['m_icon'].'</span>'.($counter>0 ? '<span title="'.number_format($counter, 0).'">'.echo_number($counter).'&nbsp;</span>' : '').$m['m_name'].' <i class="far fa-plus-circle contentTab'.$en_id.( $auto_expand_tab ? ' hidden ' : '' ).'"></i><i class="far fa-minus-circle contentTab'.$en_id.( $auto_expand_tab ? '' : ' hidden ' ).'"></i></a></div>';

        //BODY
        echo '<div class="contentTab'.$en_id.( $auto_expand_tab ? '' : ' hidden ' ).'" style="padding-bottom:34px;">';
        echo $this_tab;
        echo '</div>';
        echo '</div>';

    }

    //FOR EDITING ONLY (HIDDEN FROM UI):
    echo '<div class="hidden">'.echo_en($source).'</div>';

    ?>

</div>