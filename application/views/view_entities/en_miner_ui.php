
<script>
    //Set global variables:
    var en_focus_filter = -1; //No filter, show all
    var en_focus_id = <?= $entity['en_id'] ?>;
    var en_all_4592 = <?= json_encode($this->config->item('en_all_4592')) ?>;
</script>
<script src="/js/custom/entity-manage-js.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>

<div class="row">
    <div class="<?= $this->config->item('css_column_1') ?> cols">

        <?php

        //Parents
        echo '<h5 class="opacity_fadeout"><span class="badge badge-h"><span class="li-parent-count">' . count($entity['en__parents']) . '</span> Parent' . echo__s(count($entity['en__parents'])) . '</span></h5>';
        echo '<div id="list-parent" class="list-group  grey-list">';
        foreach ($entity['en__parents'] as $en) {
            echo echo_en($en, 2, true);
        }
        //Input to add new parents:
        echo '<div id="new-parent" class="list-group-item list_input grey-input opacity_fadeout">
                    <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search" data-lpignore="true" placeholder="Add @Entity or Paste URL"></div>
                    <div class="algolia_search_pad hidden"><span>Search existing entities, create a new entity or paste a URL...</span></div>
            </div>';

        echo '</div>';





        //Focused/current entity:
        echo '<h5 class="badge badge-h indent1 opacity_fadeout skip_fadeout_en_'.$entity['en_id'].'">Entity @'.$entity['en_id'].'</h5>';

        //Hidden link to Metadata:
        echo '<a class="secret" href="/entities/en_review_metadata/' . $entity['en_id'] . '" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Review Entity Metadata" data-placement="top"><i class="fas fa-function"></i></a>';

        echo '<a class="secret" href="/links/cron__sync_algolia/en/' . $entity['en_id'] . '" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Update Algolia Search Index" data-placement="top"><i class="fas fa-search"></i></a>';


        echo '<div id="entity-box" class="list-group indent1">';
        echo echo_en($entity, 1);
        echo '</div>';






        //Children:
        echo '<div class="indent2">';
        echo '<table width="100%" style="margin-top:10px;" class="opacity_fadeout"><tr>';
        echo '<td style="width:170px;">';


            echo '<h5 class="badge badge-h inline-block"><span class="li-children-count inline-block">' . $entity['en__child_count'] . '</span> Children</h5>';

            echo '<span class="'.( !en_auth(array(1281)) ? 'hidden' : '' ).' opacity_fadeout"><a href="javascript:void(0);" onclick="$(\'.mass_modify\').toggleClass(\'hidden\');mass_action_ui();" style="text-decoration: none; margin-left: 5px;"  data-toggle="tooltip" data-placement="right" title="Mass Update Children" class="' . advance_mode() . '"><i class="fal fa-list-alt" style="font-size: 1.2em; color: #2b2b2b;"></i></a></span>';

            echo '</td>';


        echo '<td style="text-align: right;"><div class="btn-group btn-group-sm ' . advance_mode() . '" style="margin-top:-5px;" role="group">';

        //Fetch current count for each status from DB:
        $child_en_filters = $this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => $entity['en_id'],
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
        ), array('en_child'), 0, 0, array('en_status_entity_id' => 'ASC'), 'COUNT(en_id) as totals, en_status_entity_id', 'en_status_entity_id');


        //Only show filtering UI if we find child entities with different statuses (Otherwise no need to filter):
        if (count($child_en_filters) > 0 && $child_en_filters[0]['totals'] < $entity['en__child_count']) {

            //Load status definitions:
            $en_all_6177 = $this->config->item('en_all_6177'); //Entity Statuses

            //Show fixed All button:
            echo '<a href="#" onclick="en_filter_status(-1)" class="btn btn-default btn-secondary u-status-filter u-status--1" data-toggle="tooltip" data-placement="top" title="View all entities"><i class="fas fa-at"></i><span class="hide-small"> All</span> [<span class="li-children-count">' . $entity['en__child_count'] . '</span>]</a>';

            //Show each specific filter based on DB counts:
            foreach ($child_en_filters as $c_c) {
                $st = $en_all_6177[$c_c['en_status_entity_id']];
                echo '<a href="#status-' . $c_c['en_status_entity_id'] . '" onclick="en_filter_status(' . $c_c['en_status_entity_id'] . ')" class="btn btn-default u-status-filter u-status-' . $c_c['en_status_entity_id'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['m_desc'] . '">' . $st['m_icon'] . '<span class="hide-small"> ' . $st['m_name'] . '</span> [<span class="count-u-status-' . $c_c['en_status_entity_id'] . '">' . $c_c['totals'] . '</span>]</a>';
            }

        }

        echo '</div></td>';
        echo '</tr></table></div>';


        echo '<form class="mass_modify indent2 hidden opacity_fadeout" method="POST" action="" style="width: 100% !important;"><div class="inline-box">';


            $dropdown_options = '';
            $input_options = '';
            foreach ($this->config->item('en_all_4997') as $action_en_id => $mass_action_en) {

                $dropdown_options .= '<option value="' . $action_en_id . '">' .$mass_action_en['m_name'] . '</option>';


                //Start with the input wrapper:
                $input_options .= '<span id="mass_id_'.$action_en_id.'" class="inline-block hidden mass_action_item">';

                $input_options .= '<i class="fal fa-info-circle" data-toggle="tooltip" data-placement="right" title="'.$mass_action_en['m_desc'].'"></i> ';

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
                    foreach($this->config->item('en_all_6177') /* Entity Statuses */ as $en_id => $m){
                        $input_options .= '<option value="'.$en_id.'">Update All '.$m['m_name'].'</option>';
                    }
                    $input_options .= '</select>';

                    //Replace:
                    $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set New Status...</option>';
                    foreach($this->config->item('en_all_6177') /* Entity Statuses */ as $en_id => $m){
                        $input_options .= '<option value="'.$en_id.'">Set to '.$m['m_name'].'</option>';
                    }
                    $input_options .= '</select>';


                } elseif($action_en_id == 5865){

                    //Link Status update:

                    //Find:
                    $input_options .= '<select name="mass_value1_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set Condition...</option>';
                    $input_options .= '<option value="*">Update All Statuses</option>';
                    foreach($this->config->item('en_all_6186') /* Link Statuses */ as $en_id => $m){
                        $input_options .= '<option value="'.$en_id.'">Update All '.$m['m_name'].'</option>';
                    }
                    $input_options .= '</select>';

                    //Replace:
                    $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set New Status...</option>';
                    foreach($this->config->item('en_all_6186') /* Link Statuses */ as $en_id => $m){
                        $input_options .= '<option value="'.$en_id.'">Set to '.$m['m_name'].'</option>';
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




        //Private hack for now:
        //TODO Build UI for this via Github Issue #2354
        $set_sort = ( isset($_GET['set_sort']) ? $_GET['set_sort'] : 'none' );
        echo '<input type="hidden" id="set_sort" value="'.$set_sort.'" />'; //For JS to pass to the next page loader...




        echo '<div id="list-children" class="list-group grey-list indent2">';


        $entity__children = $this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => $entity['en_id'],
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
        ), array('en_child'), $this->config->item('items_per_page'), 0, sort_entities($set_sort));

        foreach ($entity__children as $en) {
            echo echo_en($en, 2);
        }
        if ($entity['en__child_count'] > count($entity__children)) {
            echo_en_load_more(1, $this->config->item('items_per_page'), $entity['en__child_count']);
        }


        //Input to add new parents:
        echo '<div id="new-children" class="list-group-item list_input grey-input opacity_fadeout">
            <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search" data-lpignore="true" placeholder="Add @Entity or Paste URL"></div>
            <div class="algolia_search_pad hidden"><span>Search existing entities, create a new entity or paste a URL...</span></div>
    </div>';
        echo '</div>';

        ?>
    </div>

    <div class="<?= $this->config->item('css_column_2') ?> cols">


        <div id="modifybox" class="fixed-box hidden" entity-id="0" entity-link-id="0">

            <h5 class="badge badge-h edit-header"><i class="fas fa-cog"></i> Modify</h5>
            <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
                <a href="#" onclick="modify_cancel()"><i class="fas fa-times-circle"></i></a>
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
                                    Saving will remove entity and remove <span class="entity_remove_stats" style="display:inline-block; padding: 0;"></span> parent/child links.
                                </div>

                                <span class="mini-header"><span class="tr_in_link_title"></span> Merge Entity Into:</span>
                                <input style="padding-left:3px;" type="text" class="form-control algolia_search border en_quick_search" id="en_merge" value="" placeholder="Search entity to merge..." />

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
                            <span class="mini-header">Entity Icon:

                                <i class="fal fa-info-circle" data-toggle="tooltip" title="<?= is_valid_icon(null, true) ?> Click to see Font-Awesome Icons in a new window." data-placement="top"></i>

                                <a href="javascript:void(0);" style="margin-left: 5px;" onclick="$('#en_icon').val($('#en_icon').val() + '<i class=&quot;far fa-&quot;></i>' )" data-toggle="tooltip" title="Insert blank Font-Awesome HTML code" data-placement="top"><i class="far fa-plus-circle"></i><b>FA</b></a>

                                <a href="https://fontawesome.com/icons" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Visit Font-Awesome website for a full list of icons and their HTML code" data-placement="top"><i class="fas fa-external-link"></i></a>

                            </span>
                            <div class="form-group label-floating is-empty"
                                 style="margin:1px 0 10px;">
                                <div class="input-group border">
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
                                    <i class="fas fa-link"></i> Link Settings
                                </h4>
                            </div>

                            <div class="inline-box">

                                <div class="en-no-tr hidden">
                                    <p>Not applicable because you are viewing the entity itself.</p>
                                </div>

                                <div class="en-has-tr">

                                    <form class="drag-box" method="post" enctype="multipart/form-data">

                                        <span class="mini-header">Link Content: [<span style="margin:0 0 10px 0;">
                                    <span id="charln_contentNum">0</span>/<?= $this->config->item('messages_max_length') ?>
                                </span>]</span>
                                        <span class="white-wrapper">
                                        <textarea class="form-control text-edit border" id="ln_content"
                                                  maxlength="<?= $this->config->item('messages_max_length') ?>" data-lpignore="true"
                                                  placeholder="Write Message, Drop a File or Paste URL"
                                                  style="height:126px; min-height:126px;">
                                        </textarea>
                                    </span>

                                    <span style="padding: 0; font-size: 0.8em; line-height: 100%; display: block; margin: -8px 0 0 0px; float: right;"><input class="box__file inputfile" type="file" name="file" id="file" /><label class="textarea_buttons" for="file" data-toggle="tooltip" title="Upload files up to <?= $this->config->item('max_file_mb_size') ?> MB" data-placement="top"><i class="fal fa-cloud-upload"></i> Upload</label></span>


                                    </form>


                                    <span class="mini-header">Link Type:</span>
                                    <span id="en_type_link_id"></span>
                                    <p id="en_link_preview"></p>


                                    <span class="mini-header">Link Status:</span>
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

                                </div>

                            </div>

                        </div>

                        <div class="save-btn-spot">&nbsp;</div>

                    </div>

                </div>

                <table class="save-btn-box">
                    <tr>
                        <td class="save-result-td"><span class="save_entity_changes"></span></td>
                        <td class="save-td"><a href="javascript:en_modify_save();" class="btn btn-secondary btn-save">Save</a></td>
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

    </div>
</div>