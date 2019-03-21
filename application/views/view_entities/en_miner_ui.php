
<script>
    //Set global variables:
    var en_focus_filter = -1; //No filter, show all
    var en_focus_id = <?= $entity['en_id'] ?>;
    var en_all_4592 = <?= json_encode($this->config->item('en_all_4592')) ?>;
</script>
<script src="/js/custom/entity-manage-js.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>

<div class="row">
    <div class="col-xs-7 cols">

        <?php

        //Parents
        echo '<h5><span class="badge badge-h"><span class="li-parent-count">' . count($entity['en__parents']) . '</span> Parent' . fn___echo__s(count($entity['en__parents'])) . '</span></h5>';
        echo '<div id="list-parent" class="list-group  grey-list">';
        foreach ($entity['en__parents'] as $en) {
            echo fn___echo_en($en, 2, true);
        }
        //Input to add new parents:
        echo '<div id="new-parent" class="list-group-item list_input grey-input">
                    <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search" data-lpignore="true" placeholder="Add @Entity or Paste URL"></div>
                    <div class="algolia_search_pad hidden"><span>Search existing entities, create a new entity or paste a URL...</span></div>
            </div>';

        echo '</div>';





        //Focused/current entity:
        echo '<h5 class="badge badge-h indent1">Entity @'.$entity['en_id'].'</h5>';
        echo '<div id="entity-box" class="list-group indent1">';
        echo fn___echo_en($entity, 1);
        echo '</div>';






        //Children:
        echo '<div class="indent2"><table width="100%" style="margin-top:10px;"><tr>';
        echo '<td style="width:170px;">';


            echo '<h5 class="badge badge-h inline-block"><span class="li-children-count inline-block">' . $entity['en__child_count'] . '</span> Children</h5>';

            echo '<span class="'.( !fn___has_moderator_rights(4997) ? 'hidden' : '' ).'"><a href="javascript:void(0);" onclick="$(\'.mass_modify\').toggleClass(\'hidden\');mass_action_ui();" style="text-decoration: none; margin-left: 5px;"  data-toggle="tooltip" data-placement="right" title="Entity Mass Updates applied to all child entities" class="' . fn___echo_advance() . '"><i class="fal fa-list-alt" style="font-size: 1.2em; color: #2b2b2b;"></i></a></span>';

            echo '</td>';


        echo '<td style="text-align: right;"><div class="btn-group btn-group-sm ' . fn___echo_advance() . '" style="margin-top:-5px;" role="group">';

        //Fetch current count for each status from DB:
        $child_en_filters = $this->Database_model->fn___tr_fetch(array(
            'tr_parent_entity_id' => $entity['en_id'],
            'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'tr_status >=' => 0, //New+
            'en_status >=' => 0, //New+
        ), array('en_child'), 0, 0, array('en_status' => 'ASC'), 'COUNT(en_id) as totals, en_status', 'en_status');


        //Only show filtering UI if we find child entities with different statuses (Otherwise no need to filter):
        if (count($child_en_filters) > 0 && $child_en_filters[0]['totals'] < $entity['en__child_count']) {

            //Load status definitions:
            $fixed_fields = $this->config->item('fixed_fields');

            //Show fixed All button:
            echo '<a href="#" onclick="u_load_filter_status(-1)" class="btn btn-default btn-secondary u-status-filter u-status--1" data-toggle="tooltip" data-placement="top" title="View all entities"><i class="fas fa-at"></i><span class="hide-small"> All</span> [<span class="li-children-count">' . $entity['en__child_count'] . '</span>]</a>';

            //Show each specific filter based on DB counts:
            foreach ($child_en_filters as $c_c) {
                $st = $fixed_fields['en_status'][$c_c['en_status']];
                echo '<a href="#status-' . $c_c['en_status'] . '" onclick="u_load_filter_status(' . $c_c['en_status'] . ')" class="btn btn-default u-status-filter u-status-' . $c_c['en_status'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['s_desc'] . '">' . $st['s_icon'] . '<span class="hide-small"> ' . $st['s_name'] . '</span> [<span class="count-u-status-' . $c_c['en_status'] . '">' . $c_c['totals'] . '</span>]</a>';
            }

        }

        echo '</div></td>';
        echo '</tr></table></div>';


        echo '<form class="mass_modify indent2 hidden" method="POST" action="" style="width: 100% !important;"><div class="inline-box">';


            $fixed_fields = $this->config->item('fixed_fields');
            $dropdown_options = '';
            $input_options = '';
            foreach ($this->config->item('en_all_4997') as $action_en_id => $mass_action_en) {

                $dropdown_options .= '<option value="' . $action_en_id . '">' .$mass_action_en['m_name'] . '</option>';


                //Start with the input wrapper:
                $input_options .= '<span id="mass_id_'.$action_en_id.'" class="inline-block hidden mass_action_item">';

                $input_options .= '<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="right" title="'.$mass_action_en['m_desc'].'"></i> ';

                if(in_array($action_en_id, array(5000, 5001))){

                    //String Find and Replace:

                    //Find:
                    $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" placeholder="Search" style="width: 145px;" class="form-control border">';

                    //Replace:
                    $input_options .= '<input type="text" name="mass_value2_'.$action_en_id.'" placeholder="Replace" style="width: 145px;" class="form-control border">';


                } elseif(in_array($action_en_id, array(5981, 5982))){

                    //Entity search box:

                    //String command:
                    $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" style="width:300px;" placeholder="Search entities..." class="form-control algolia_search en_quick_search border">';

                    //We don't need the second value field here:
                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';


                } elseif($action_en_id == 5003){

                    //Entity Status update:

                    //Find:
                    $input_options .= '<select name="mass_value1_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set Condition...</option>';
                    $input_options .= '<option value="*">Update All Statuses</option>';
                    foreach($fixed_fields['en_status'] as $status_id => $status){
                        $input_options .= '<option value="'.$status_id.'">Update All '.$status['s_name'].'</option>';
                    }
                    $input_options .= '</select>';

                    //Replace:
                    $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set New Status...</option>';
                    foreach($fixed_fields['en_status'] as $status_id => $status){
                        $input_options .= '<option value="'.$status_id.'">Set to '.$status['s_name'].'</option>';
                    }
                    $input_options .= '</select>';


                } elseif($action_en_id == 5865){

                    //Transaction Status update:

                    //Find:
                    $input_options .= '<select name="mass_value1_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set Condition...</option>';
                    $input_options .= '<option value="*">Update All Statuses</option>';
                    foreach($fixed_fields['tr_status'] as $status_id => $status){
                        $input_options .= '<option value="'.$status_id.'">Update All '.$status['s_name'].'</option>';
                    }
                    $input_options .= '</select>';

                    //Replace:
                    $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set New Status...</option>';
                    foreach($fixed_fields['tr_status'] as $status_id => $status){
                        $input_options .= '<option value="'.$status_id.'">Set to '.$status['s_name'].'</option>';
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

            echo '<select class="form-control border inline-block" name="mass_action_en_id" id="set_mass_action">';
            echo $dropdown_options;
            echo '</select>';

            echo $input_options;

            echo '<input type="submit" value="Apply" class="btn btn-secondary inline-block">';

        echo '</div></form>';




        echo '<div id="list-children" class="list-group grey-list indent2">';

        foreach ($entity['en__children'] as $en) {
            echo fn___echo_en($en, 2);
        }
        if ($entity['en__child_count'] > count($entity['en__children'])) {
            fn___echo_en_load_more(1, $this->config->item('en_per_page'), $entity['en__child_count']);
        }


        //Input to add new parents:
        echo '<div id="new-children" class="list-group-item list_input grey-input">
            <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search" data-lpignore="true" placeholder="Add @Entity or Paste URL"></div>
            <div class="algolia_search_pad hidden"><span>Search existing entities, create a new entity or paste a URL...</span></div>
    </div>';
        echo '</div>';

        ?>
    </div>

    <div class="col-xs-5 cols">


        <div id="modifybox" class="fixed-box hidden" entity-id="0" entity-link-id="0">

            <h5 class="badge badge-h edit-header"><i class="fas fa-cog"></i> Modify</h5>
            <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
                <a href="javascript:void(0)" onclick="$('#modifybox').addClass('hidden')"><i
                            class="fas fa-times-circle"></i></a>
            </div>
            <div class="grey-box">


                <div class="row">
                    <div class="col-md-6">

                        <div class="title" style="margin-bottom:0; padding-bottom:0;">
                            <h4>
                                <i class="fas fa-at"></i> Entity Settings
                            </h4>
                        </div>
                        <div class="inline-box" style="margin-bottom: 15px;">

                            <!-- Entity Status -->
                            <span class="mini-header">Entity Status:</span>
                            <select class="form-control border" id="en_status" data-toggle="tooltip" title="Entity Status" data-placement="top">
                                <?php
                                foreach (fn___echo_fixed_fields('en_status') as $status_id => $status) {
                                    echo '<option value="' . $status_id . '" title="' . $status['s_desc'] . '">' . $status['s_name'] . '</option>';
                                }
                                ?>
                            </select>
                            <div class="notify_en_remove hidden">

                                <input type="hidden" id="en_link_count" value="0" />

                                <span class="mini-header"><span class="tr_in_link_title"></span> Merge Entity Into:</span>
                                <input style="padding-left:3px;" type="text" class="form-control algolia_search border en_quick_search" id="en_merge" value="" placeholder="Search entity to merge..." />

                                <div class="alert alert-danger" style="margin:5px 0px; padding:7px;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Saving will remove entity and remove <span class="entity_remove_stats" style="display:inline-block; padding: 0;"></span> parent/child links.
                                </div>

                            </div>


                            <!-- Entity Name -->
                            <span class="mini-header" style="margin-top:20px;">Entity Name: [<span style="margin:0 0 10px 0;">
                            <span id="charNameNum">0</span>/<?= $this->config->item('en_name_max_length') ?>
                        </span>]</span>
                            <span class="white-wrapper">
                                <textarea class="form-control text-edit border" id="en_name"
                                  onkeyup="en_name_word_count()" data-lpignore="true"
                                  style="height:66px; min-height:66px;">
                                </textarea>
                            </span>


                            <!-- Entity Icon -->
                            <span class="mini-header">Entity Icon: <a href="https://fontawesome.com/icons" target="_blank" data-toggle="tooltip" title="<?= is_valid_icon(null, true) ?> Click to see Font-Awesome Icons in a new window." data-placement="top"><i class="fal fa-info-circle"></i></a></span>
                            <div class="form-group label-floating is-empty"
                                 style="margin:1px 0 10px;">
                                <div class="input-group border" data-toggle="tooltip" title="Entity Icon" data-placement="top">
                                    <span class="input-group-addon addon-lean addon-grey icon-demo" style="color:#2f2739; font-weight: 300; padding-left:7px !important; padding-right:6px !important;"><i class="fas fa-at grey-at"></i></span>
                                    <input type="text" id="en_icon" value=""
                                           maxlength="<?= $this->config->item('en_name_max_length') ?>" data-lpignore="true" placeholder=""
                                           class="form-control">
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="col-md-6">

                        <div>

                            <div class="title">
                                <h4>
                                    <i class="fas fa-atlas"></i> Transaction Settings
                                </h4>
                            </div>

                            <div class="inline-box">

                                <div class="en-no-tr hidden">
                                    <p>No transaction available as your viewing the entity itself.</p>
                                </div>

                                <div class="en-has-tr">

                                    <form class="drag-box" method="post" enctype="multipart/form-data">

                                        <span class="mini-header">Link Content: [<span style="margin:0 0 10px 0;">
                                    <span id="chartr_contentNum">0</span>/<?= $this->config->item('tr_content_max_length') ?>
                                </span>]</span>
                                        <span class="white-wrapper">
                                        <textarea class="form-control text-edit border" id="tr_content"
                                                  maxlength="<?= $this->config->item('tr_content_max_length') ?>" data-lpignore="true"
                                                  placeholder="Write Message, Drop a File or Paste URL"
                                                  style="height:126px; min-height:126px;">
                                        </textarea>
                                    </span>

                                    <span style="padding: 0; font-size: 0.8em; line-height: 100%; display: block; margin: -8px 0 0 0px; float: right;"><input class="box__file inputfile" type="file" name="file" id="file" /><label class="textarea_buttons" for="file" data-toggle="tooltip" title="Upload files up to <?= $this->config->item('en_file_max_size') ?> MB" data-placement="top"><i class="fal fa-cloud-upload"></i> Upload</label></span>


                                    </form>


                                    <span class="mini-header">Link Type:</span>
                                    <span id="en_link_type_id"></span>
                                    <p id="en_link_preview"></p>


                                    <span class="mini-header">Transaction Status:</span>
                                    <select class="form-control border" id="tr_status" data-toggle="tooltip" title="Transaction Status" data-placement="top">
                                        <?php
                                        foreach (fn___echo_fixed_fields('tr_status') as $status_id => $status) {
                                            if($status_id < 3){ //No need to verify entity links!
                                                echo '<option value="' . $status_id . '" title="' . $status['s_desc'] . '">' . $status['s_name'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>

                                    <div class="notify_en_unlink hidden">
                                        <div class="alert alert-warning" style="margin:5px 0px; padding:7px;">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Saving will unlink entity
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="save-btn-spot">&nbsp;</div>

                    </div>

                </div>

                <table class="save-btn-box">
                    <tr>
                        <td class="save-result-td"><span class="save_entity_changes"></span></td>
                        <td class="save-td"><a href="javascript:fn___en_modify_save();" class="btn btn-secondary btn-save">Save</a></td>
                    </tr>
                </table>

            </div>

        </div>


        <div id="message-frame" class="fixed-box hidden" entity-id="">

            <h5 class="badge badge-h" data-toggle="tooltip"
                title="Message management can only be done using Intents. Entity messages are listed below for view-only"
                data-placement="bottom"><i class="fas fa-comment-plus"></i> Entity References <i class="fas fa-lock"></i>
            </h5>
            <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
                <a href="javascript:void(0)" onclick="$('#message-frame').addClass('hidden');$('#loaded-messages').html('');">
                    <i class="fas fa-times-circle"></i>
                </a>
            </div>
            <div class="grey-box">
                <div id="loaded-messages"></div>
            </div>

        </div>


        <?php $this->load->view('view_ledger/tr_actionplan_right_column'); ?>


    </div>
</div>