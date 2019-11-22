
<?php

$en_all_6206 = $this->config->item('en_all_6206'); //Entity Table
$en_all_4341 = $this->config->item('en_all_4341'); //Link Table
$en_all_2738 = $this->config->item('en_all_2738');
$en_all_6177 = $this->config->item('en_all_6177'); //Entity Statuses
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
?>

<script>
    //Set global variables:
    var en_focus_filter = -1; //No filter, show all
    var en_focus_id = <?= $entity['en_id'] ?>;
    var en_all_4592 = <?= json_encode($this->config->item('en_all_4592')) ?>;
</script>
<style>
    .en_child_icon_<?= $entity['en_id'] ?>{ display:none; }
</style>
<script src="/js/custom/play_modify.js?v=v<?= config_var(11060) ?>"
        type="text/javascript"></script>

<div class="container">

    <?php


    //NAME
    echo '<h1 class="inline montserrat" style="padding-right:10px;"><span class="icon-block-lg en-icon en_ui_icon_'.$entity['en_id'].'">'.echo_en_icon($entity['en_icon']).'</span> <span class="en_name_'.$entity['en_id'].'">'.$entity['en_name'].'</span></h1>';


    echo '<div class="inline-block" style="padding-bottom:10px;">';


    //STATUS
    $is_published = in_array($entity['en_status_entity_id'], $this->config->item('en_ids_7357'));
    echo '<span class="icon-block en_status_entity_id_' . $entity['en_id'] . ( $is_published ? 'hidden' : '' ).'"><span data-toggle="tooltip" data-placement="bottom" title="'.$en_all_6177[$entity['en_status_entity_id']]['m_name'].': '.$en_all_6177[$entity['en_status_entity_id']]['m_desc'].'">' . $en_all_6177[$entity['en_status_entity_id']]['m_icon'] . '</span></span>';


    //ACCOUNT
    if(isset($session_en['en_id']) && $session_en['en_id']==$entity['en_id']){

        echo '<a href="/play/myaccount" class="btn btn-sm btn-play btn-five inline-block" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[6225]['m_desc'].'">'.$en_all_11035[6225]['m_icon'].' '.$en_all_11035[6225]['m_name'].'</a>';

        echo '<a href="/play/signout" class="btn btn-sm btn-play btn-five inline-block" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[7291]['m_name'].'">'.$en_all_11035[7291]['m_icon'].'</a>';

    }

    //MODIFY
    echo '<a href="javascript:void(0);" onclick="en_modify_load(' . $entity['en_id'] . ',0)" class="btn btn-sm btn-play btn-five inline-block '. require_superpower(10983) .'"><i class="fas fa-cog"></i></a>';


    //REFERENCES
    echo '<div class="'.require_superpower(10964).'">';
    $en_count_references = en_count_references($entity['en_id']);
    if(count($en_count_references) > 0){
        $en_all_6194 = $this->config->item('en_all_6194');
        //Show this entities connections:
        $ref_count = 0;
        foreach($en_count_references as $en_id=>$en_count){
            echo '&nbsp;&nbsp;<span data-toggle="tooltip" data-placement="bottom" title="Referenced as '.$en_all_6194[$en_id]['m_name'].' '.number_format($en_count, 0).' times">'.$en_all_6194[$en_id]['m_icon'] . ' '. echo_number($en_count).'</span>';
            $ref_count++;
        }
    }
    echo '</div>';


    echo '</div>';

    ?>





    <div id="modifybox" class="fixed-box hidden" entity-id="0" entity-link-id="0">

        <h5 class="badge badge-h edit-header"><i class="fas fa-cog"></i> Modify</h5>
        <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
            <a href="javascript:void(0);" onclick="modify_cancel()"><i class="fas fa-times-circle"></i></a>
        </div>
        <div class="grey-box">

            <div class="row">
                <div class="col-md-6">

                    <div class="inline-box">



                        <!-- Entity Status -->
                        <span class="mini-header"><?= $en_all_6206[6177]['m_icon'].' '.$en_all_6206[6177]['m_name'] ?></span>
                        <select class="form-control border" id="en_status_entity_id">
                            <?php
                            foreach($this->config->item('en_all_6177') /* Entity Statuses */ as $en_id => $m){
                                echo '<option value="' . $en_id . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <div class="notify_en_remove hidden">

                            <input type="hidden" id="en_link_count" value="0" />
                            <div class="alert alert-danger" style="margin:5px 0px; padding:7px;">
                                <i class="fas fa-exclamation-triangle"></i>
                                Saving will archive this entity and UNLINK ALL <span class="entity_remove_stats" style="display:inline-block; padding: 0;"></span> links
                            </div>

                            <span class="mini-header"><span class="tr_in_link_title"></span> Merge Entity Into:</span>
                            <input style="padding-left:3px;" type="text" class="form-control algolia_search border en_quick_search" id="en_merge" value="" placeholder="Search entity to merge..." />

                        </div>



                        <!-- Entity Name -->
                        <span class="mini-header" style="margin-top:20px;"><?= $en_all_6206[6197]['m_icon'].' '.$en_all_6206[6197]['m_name'] ?> [<span style="margin:0 0 10px 0;"><span id="charNameNum">0</span>/<?= config_var(11072) ?></span>]</span>
                        <span class="white-wrapper">
                                <textarea class="form-control text-edit border montserrat" id="en_name"
                                          onkeyup="en_name_word_count()" data-lpignore="true"
                                          style="height:66px; min-height:66px;">
                                </textarea>
                            </span>



                        <!-- Entity Icon -->
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
                                <span class="input-group-addon addon-lean addon-grey icon-demo" style="color:#070707; font-weight: 300; padding-left:7px !important; padding-right:6px !important;"></span>
                            </div>
                        </div>



                    </div>

                </div>
                <div class="col-md-6 en-has-tr">

                    <div>

                        <div class="inline-box">


                            <span class="mini-header"><?= $en_all_4341[6186]['m_icon'].' '.$en_all_4341[6186]['m_name'] ?></span>
                            <select class="form-control border" id="ln_status_entity_id">
                                <?php
                                foreach($this->config->item('en_all_6186') /* Link Statuses */ as $en_id => $m){
                                    echo '<option value="' . $en_id . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                                }
                                ?>
                            </select>

                            <div class="notify_unlink_en hidden">
                                <div class="alert alert-warning" style="margin:5px 0px; padding:7px;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Saving will unlink entity
                                </div>
                            </div>




                            <form class="drag-box" method="post" enctype="multipart/form-data">
                                <span class="mini-header" style="margin-top: 20px;"><?= $en_all_4341[4372]['m_icon'].' '.$en_all_4341[4372]['m_name'] ?> [<span style="margin:0 0 10px 0;"><span id="charln_contentNum">0</span>/<?= config_var(11073) ?></span>]</span>
                                <span class="white-wrapper">
                                    <textarea class="form-control text-edit border" id="ln_content"
                                              maxlength="<?= config_var(11073) ?>" data-lpignore="true"
                                              placeholder="Write Message, Drop a File or Paste URL"
                                              style="height:126px; min-height:126px;">
                                    </textarea>
                                </span>

                                <span><input class="box__file inputfile" type="file" name="file" id="file" /><label class="textarea_buttons" for="file" data-toggle="tooltip" title="Upload files up to <?= config_var(11063) ?> MB" data-placement="top"><i class="fal fa-cloud-upload"></i> Upload</label></span>
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
                    <td class="save-result-td"><span class="save_entity_changes"></span></td>
                </tr>
            </table>

        </div>

    </div>


    <div id="message-frame" class="fixed-box hidden" entity-id="">

        <h5 class="badge badge-h" data-toggle="tooltip"
            title="Message management can only be done using Intents. Entity messages are listed below for view-only"
            data-placement="bottom"><i class="fas fa-comment-plus"></i> Entity References within Intent Notes
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
        $tab_content = '';
        $default_active = false;
        $require_superpowers = array_intersect($this->config->item('en_ids_10957'), $m['m_parents']);


        echo '<div class="'.config_var($col_num==1 ? 11092 : 11093).'">';

        echo '<div class="'.( count($require_superpowers) ? require_superpower(end($require_superpowers)) : '' ).'">';
        echo '<ul class="nav nav-tabs nav-tabs-sm menu_bar">';

        foreach ($this->config->item('en_all_'.$en_id) as $en_id2 => $m2){


            //Is this a caret menu?
            if(in_array(11040 , $m2['m_parents'])){
                echo echo_caret($en_id2, $m2, $entity['en_id']);
                continue;
            }



            //Determine counter:
            $default_active = false;
            $show_tab_names = (in_array($en_id2, $this->config->item('en_ids_11084')));
            $counter = null; //Assume no counters
            $this_tab = '';




            //PLAY
            if($en_id2==11030){

                //PLAY TREE UP/INPUT
                $default_active = true; //LEFT

                $fetch_11030 = $this->READ_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
                    'ln_child_entity_id' => $entity['en_id'],
                ), array('en_parent'), 0, 0, array('ln_up_order' => 'ASC'));

                $counter = count($fetch_11030);

                $this_tab .= '<div id="list-parent" class="list-group ">';
                foreach ($fetch_11030 as $en) {
                    $this_tab .= echo_en($en,true);
                }

                //Input to add new parents:
                $this_tab .= '<div id="new-parent" class="'.require_superpower(10983).'">
                    <div class="form-group is-empty"><input type="text" class="form-control new-player-input algolia_search form-control-thick" data-lpignore="true" placeholder="Add Profile Player"></div>
                    <div class="algolia_search_pad hidden"><b class="montserrat"><span class="icon-block"><i class="fas fa-search-plus blue"></i></span>Search players, create a new player or paste URL...</b></div>
            </div>';

                $this_tab .= '</div>';

            } elseif($en_id2==11029){

                //PLAY PROJETCS
                $default_active = true; //RIGHT


                //COUNT TOTAL
                $child_links = $this->READ_model->ln_fetch(array(
                    'ln_parent_entity_id' => $entity['en_id'],
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
                ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');
                $counter = $child_links[0]['en__child_count'];


                $fetch_11029 = $this->READ_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
                    'ln_parent_entity_id' => $entity['en_id'],
                ), array('en_child'), config_var(11064), 0, array('ln_order' => 'ASC', 'en_name' => 'ASC'));

                $this_tab .= '<div id="list-children" class="list-group">';

                foreach ($fetch_11029 as $en) {
                    $this_tab .= echo_en($en,false);
                }
                if ($counter > count($fetch_11029)) {
                    $this_tab .= echo_en_load_more(1, config_var(11064), $counter);
                }

                //Input to add new child:
                $this_tab .= '<div id="new-children" class="'.require_superpower(10983).'">
            <div class="form-group is-empty"><input type="text" class="form-control new-player-input form-control-thick algolia_search" data-lpignore="true" placeholder="Add Portfolio Player"></div>
            <div class="algolia_search_pad hidden"><b class="montserrat"><span class="icon-block"><i class="fas fa-search-plus blue"></i></span>Search players, create a new player or paste URL...</b></div>
    </div>';
                $this_tab .= '</div>';






                //Fetch current count for each status from DB:
                $child_en_filters = $this->READ_model->ln_fetch(array(
                    'ln_parent_entity_id' => $entity['en_id'],
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
                ), array('en_child'), 0, 0, array('en_status_entity_id' => 'ASC'), 'COUNT(en_id) as totals, en_status_entity_id', 'en_status_entity_id');

                //Only show filtering UI if we find child entities with different statuses (Otherwise no need to filter):
                if (count($child_en_filters) > 0 && $child_en_filters[0]['totals'] < $entity['en__child_count']) {


                    //Load status definitions:
                    $en_all_6177 = $this->config->item('en_all_6177'); //Entity Statuses

                    //Add 2nd Navigation to UI
                    $tab_content .= '<div class="nav nav-tabs nav-tabs-sm '.require_superpower(10967).'">';

                    //Show fixed All button:
                    $tab_content .= '<li class="nav-item"><a href="#" onclick="en_filter_status(-1)" class="nav-link u-status-filter active u-status--1" data-toggle="tooltip" data-placement="top" title="View all entities"><i class="fas fa-at"></i><span class="hide-small"> All</span> [<span class="counter-11029">' . $entity['en__child_count'] . '</span>]</a></li>';

                    //Show each specific filter based on DB counts:
                    foreach ($child_en_filters as $c_c) {
                        $st = $en_all_6177[$c_c['en_status_entity_id']];
                        $tab_content .= '<li class="nav-item"><a href="#status-' . $c_c['en_status_entity_id'] . '" onclick="en_filter_status(' . $c_c['en_status_entity_id'] . ')" class="nav-link u-status-filter u-status-' . $c_c['en_status_entity_id'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['m_desc'] . '">' . $st['m_icon'] . '<span class="hide-small"> ' . $st['m_name'] . '</span> [<span class="count-u-status-' . $c_c['en_status_entity_id'] . '">' . $c_c['totals'] . '</span>]</a></li>';
                    }

                    $tab_content .= '</div>';

                }

            } elseif(in_array($en_id2, array(7347,6146))){

                //READER READS & BOOKMARKS
                $item_counters = $this->READ_model->ln_fetch(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_'.$en_id2)) . ')' => null,
                    'ln_creator_entity_id' => $entity['en_id'],
                ), array(), 1, 0, array(), 'COUNT(ln_id) as totals');

                $counter = $item_counters[0]['totals'];

            } elseif(in_array($en_id2, $this->config->item('en_ids_4485'))){

                //BLOG NOTES
                $blog_note_filters = array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'ln_type_entity_id' => $en_id2,
                    '(ln_creator_entity_id='.$entity['en_id'].' OR ln_child_entity_id='.$entity['en_id'].' OR ln_parent_entity_id='.$entity['en_id'].')' => null,
                );

                //COUNT ONLY
                $item_counters = $this->READ_model->ln_fetch($blog_note_filters, array(), 1, 0, array(), 'COUNT(ln_id) as totals');
                $counter = $item_counters[0]['totals'];


                //SHOW LASTEST 100
                $this_tab .= '<div id="list-messages" class="list-group">';
                foreach ($this->READ_model->ln_fetch($blog_note_filters, array('in_child')) as $blog_note) {
                    $this_tab .= echo_en_messages($blog_note);
                }
                $this_tab .= '</div>';


            } elseif($en_id2==4997){


                $this_tab .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important;"><div class="inline-box">';

                $dropdown_options = '';
                $input_options = '';
                $tab_counter = 0;
                foreach ($this->config->item('en_all_4997') as $action_en_id => $mass_action_en) {

                    $tab_counter++;
                    $dropdown_options .= '<option value="' . $action_en_id . '">' .$mass_action_en['m_name'] . '</option>';


                    //Start with the input wrapper:
                    $input_options .= '<span id="mass_id_'.$action_en_id.'" class="inline-block '. ( $tab_counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';

                    $input_options .= '<i class="fal fa-info-circle" data-toggle="tooltip" data-placement="right" title="'.$mass_action_en['m_desc'].'"></i> ';

                    if(in_array($action_en_id, array(5000, 5001, 10625))){

                        //String Find and Replace:

                        //Find:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" placeholder="Search" style="width: 145px;" class="form-control border">';

                        //Replace:
                        $input_options .= '<input type="text" name="mass_value2_'.$action_en_id.'" placeholder="Replace" stycacle="width: 145px;" class="form-control border">';


                    } elseif(in_array($action_en_id, array(5981, 5982))){

                        //Entity search box:

                        //String command:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" style="width:300px;" placeholder="Search entities..." class="form-control algolia_search en_quick_search border">';

                        //We don't need the second value field here:
                        $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';


                    } elseif($action_en_id == 11956){

                        //IF HAS THIS
                        $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" style="width:300px;" placeholder="IF THIS PLAYER..." class="form-control algolia_search en_quick_search border">';

                        //ADD THIS
                        $input_options .= '<input type="text" name="mass_value2_'.$action_en_id.'" style="width:300px;" placeholder="ADD THIS PLAYER..." class="form-control algolia_search en_quick_search border">';


                    } elseif($action_en_id == 5003){

                        //Entity Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_en_id.'" class="form-control border">';
                        $input_options .= '<option value="">Set Condition...</option>';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($this->config->item('en_all_6177') /* Entity Statuses */ as $en_id3 => $m3){
                            $input_options .= '<option value="'.$en_id3.'">Update All '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($this->config->item('en_all_6177') /* Entity Statuses */ as $en_id3 => $m3){
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

            }

            //Don't show empty tabs:
            if(!is_null($counter) && $counter < 1 && !$show_tab_names){
                continue;
            }

            $require_superpowers = array_intersect($this->config->item('en_ids_10957'), $m2['m_parents']);

            echo '<li class="nav-item '.( count($require_superpowers) ? require_superpower(end($require_superpowers)) : '' ).'"><a class="nav-link tab-nav-'.$en_id.' tab-head-'.$en_id2.' '.( $default_active ? ' active ' : '' ).'" href="javascript:void(0);" onclick="loadtab('.$en_id.','.$en_id2.')" data-toggle="tooltip" data-placement="top" title="'.( $show_tab_names ? '' : $m2['m_name'] ).'">'.$m2['m_icon'].( is_null($counter) ? '' : ' <span class="counter-'.$en_id2.'">'.echo_number($counter).'</span>' ).( $show_tab_names ? ' '.$m2['m_name'] : '' ).'</a></li>';


            $tab_content .= '<div class="tab-content tab-group-'.$en_id.' tab-data-'.$en_id2.( $default_active ? '' : ' hidden ' ).'">';
            $tab_content .= $this_tab;
            $tab_content .= '</div>';

            $default_active = false;

        }

        echo '</ul>';
        echo '</div>';

        echo $tab_content;
        echo '</div>';
    }

    echo '</div>';



    //FOR EDITING ONLY (HIDDEN FROM UI):
    echo '<div class="hidden">'.echo_en($entity).'</div>';

    ?>

</div>