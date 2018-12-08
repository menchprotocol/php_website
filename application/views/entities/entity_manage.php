<?php

//Fetch Data Components
$udata = $this->session->userdata('user');

//Fetch other data:
$child_entities = $this->Db_model->en_children_fetch(array(
    'tr_en_parent_id' => $entity['u_id'],
    'tr_status' => 1, //Only active
), array('u__children_count'), $this->config->item('items_per_page'));

//Intents subscribed:
$limit = (is_dev() ? 10 : 100);
$ws = $this->Db_model->w_fetch(array(
    'tr_en_parent_id' => $entity['u_id'],
), array('en', 'in', 'w_stats'), array(
    'w_id' => 'DESC',
), $limit);


//Javascript Logic:
?>
<script>
    //Set global variables:
    var u_status_filter = -1; //No filter, show all!
    var top_u_id = <?= $entity['u_id'] ?>;
    var top_u_full_name = '<?= str_replace('\'', '’', $entity['u_full_name']) ?>';
</script>
<script src="/js/custom/entity-manage-js.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>


<div class="row">
    <div class="col-xs-6 cols">

        <?php
        //Entity & Components:

        //Parents
        if ($entity['u_id'] != $this->config->item('primary_en_id') || count($entity['en__parents']) > 0) {

            echo '<h5><span class="badge badge-h"><i class="fas fa-sign-in-alt"></i> <span class="li-parent-count">' . count($entity['en__parents']) . '</span> Parent' . echo__s(count($entity['en__parents'])) . '</span></h5>';
            echo '<div id="list-parent" class="list-group  grey-list">';
            foreach ($entity['en__parents'] as $ur) {
                echo echo_u($ur, 2, true);
            }
            //Input to add new parents:
            echo '<div id="new-parent" class="list-group-item list_input grey-input">
                <div class="input-group">
                    <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search" data-lpignore="true" placeholder="Add Entity..."></div>
                    <span class="input-group-addon">
                        <a class="badge badge-secondary new-btn" href="javascript:void(0);" onclick="alert(\'Note: Either choose an option from the suggestion menu to continue\')">ADD</a>
                    </span>
                </div>
            </div>';

            echo '</div>';
        }


        //Focused/current entity:
        echo '<h5 class="badge badge-h indent1"><i class="fas fa-at"></i> Entity</h5>';
        echo '<div id="entity-box" class="list-group indent1">';
        echo echo_u($entity, 1);
        echo '</div>';


        //Children:
        echo '<div class="indent2"><table width="100%" style="margin-top:10px;"><tr>';
        echo '<td style="width: 100px;"><h5 class="badge badge-h"><i class="fas fa-sign-out-alt rotate90"></i> <span class="li-children-count">' . $entity['u__children_count'] . '</span> Children</h5></td>';
        //Count orphans IF we are in the top parent root:
        if ($this->config->item('primary_en_id') == $entity['u_id']) {
            $orphans_count = count($this->Db_model->en_orphans_fetch());
            if ($orphans_count > 0) {
                echo '<td style="width:130px;">';
                echo '<span style="padding-left:8px; display: inline-block;"><a href="/entities/orphan">' . $orphans_count . ' Orphans &raquo;</a></span>';
                echo '</td>';
            }
        }
        echo '<td style="text-align: right;"><div class="btn-group btn-group-sm" style="margin-top:-5px;" role="group">';

        //Fetch current count for each status from DB:
        $counts = $this->Db_model->en_children_fetch(array(
            'tr_en_parent_id' => $entity['u_id'],
            'tr_status' => 1, //Only active
            'u_status >=' => 0,
        ), array(), 0, 0, 'COUNT(u_id) as u_counts, u_status', 'u_status', array(
            'u_status' => 'ASC',
        ));


        //Only show filtering UI if we find entities with different statuses
        if (count($counts) > 0 && $counts[0]['u_counts'] < $entity['u__children_count']) {

            //Load status definitions:
            $status_index = $this->config->item('object_statuses');

            //Show fixed All button:
            echo '<a href="javascript:void(0)" onclick="u_load_filter_status(-1)" class="btn btn-default btn-secondary u-status-filter u-status--1" data-toggle="tooltip" data-placement="top" title="View all entities"><i class="fas fa-at"></i><span class="hide-small"> All</span> [<span class="li-children-count">' . $entity['u__children_count'] . '</span>]</a>';

            //Show each specific filter based on DB counts:
            foreach ($counts as $c_c) {
                $st = $status_index['en'][$c_c['u_status']];
                echo '<a href="#status-' . $c_c['u_status'] . '" onclick="u_load_filter_status(' . $c_c['u_status'] . ')" class="btn btn-default u-status-filter u-status-' . $c_c['u_status'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['s_desc'] . '"><i class="' . $st['s_icon'] . '"></i><span class="hide-small"> ' . $st['s_name'] . '</span> [<span class="count-u-status-' . $c_c['u_status'] . '">' . $c_c['u_counts'] . '</span>]</a>';
            }

        }

        echo '</div></td>';
        echo '</tr></table></div>';


        echo '<div id="list-children" class="list-group grey-list indent2">';

        foreach ($child_entities as $u) {
            echo echo_u($u, 2);
        }
        if ($entity['u__children_count'] > count($child_entities)) {
            echo_next_u(1, $this->config->item('items_per_page'), $entity['u__children_count']);
        }

        //Input to add new parents:
        echo '<div id="new-children" class="list-group-item list_input grey-input">
        <div class="input-group">
            <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search bottom-add" data-lpignore="true" placeholder="Add ' . stripslashes($entity['u_full_name']) . '"></div>
            <span class="input-group-addon">
                <a class="badge badge-secondary new-btn" href="javascript:tr_add(0,' . $entity['u_id'] . ', 0);">ADD</a>
            </span>
        </div>
    </div>';
        echo '</div>';


        //Only show if data exists (users cannot modify this anyways)
        if (count($ws) > 0) {
            //Show these subscriptions:
            echo '<h5 class="badge badge-h indent1" style="display: inline-block;"><i class="fas fa-comment-plus"></i> ' . count($ws) . ($limit == count($ws) ? '+' : '') . ' Subscriptions</h5>';
            echo '<div class="list-group list-grey indent1" style="margin-bottom:10px;">';
            foreach ($ws as $w) {
                echo echo_w_console($w);
            }
            echo '</div>';
        }


        //URLs
        echo '<h5 class="badge badge-h indent1"><i class="fas fa-atlas"></i> <span class="li-urls-count">' . count($entity['u__urls']) . '</span> URLs</h5>';
        echo '<div id="list-urls" class="list-group indent1 grey-list" style="margin-bottom:40px;">';
        foreach ($entity['u__urls'] as $x) {
            echo echo_x($entity, $x);
        }

        //Add new Reference:
        echo '<div class="list-group-item list_input grey-input">
            <div class="input-group">
                <div class="form-group is-empty"><input type="url" class="form-control" data-lpignore="true" id="add_url_input" placeholder="Paste URL here..."></div>
                <span class="input-group-addon">
                    <a class="badge badge-secondary" id="add_url_btn" href="javascript:x_add();">ADD</a>
                </span>
            </div>
        </div>';
        echo '</div>';


        ?>
    </div>

    <div class="col-xs-6 cols ">


        <div id="modifybox" class="fixed-box hidden" entity-id="0" entity-link-id="0">

            <h5 class="badge badge-h"><i class="fas fa-cog"></i> Modify Entity</h5>
            <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;"><a href="javascript:void(0)"
                                                                                         onclick="$('#modifybox').addClass('hidden')"><i
                            class="fas fa-times-circle"></i></a></div>

            <div class="grey-box">


                <div class="row">
                    <div class="col-md-6">

                        <div class="title" style="margin-bottom:0; padding-bottom:0; margin-top:15px;"><h4><i
                                        class="fas fa-fingerprint"></i> Name [<span
                                        style="margin:0 0 10px 0; font-size:0.8em;"><span
                                            id="charNameNum">0</span>/<?= $this->config->item('en_name_max') ?></span>]
                            </h4></div>
                        <input type="text" id="u_full_name" value="" onkeyup="u_full_name_word_count()"
                               maxlength="<?= $this->config->item('en_name_max') ?>" data-lpignore="true"
                               placeholder="Name" class="form-control border">


                        <div class="title" style="margin-top:15px;"><h4><i class="fas fa-sliders-h"></i> Entity Status
                            </h4></div>
                        <select class="form-control" id="u_status">
                            <?php
                            foreach (echo_status('en') as $status_id => $status) {
                                echo '<option value="' . $status_id . '" title="' . $status['s_desc'] . '">' . $status['s_name'] . '</option>';
                            }
                            ?>
                        </select>

                    </div>
                    <div class="col-md-6">

                        <div class="title" style="margin-bottom:0; padding-bottom:0; margin-top:15px;"><h4><i
                                        class="fas fa-code"></i> Icon [<span
                                        style="margin:0 0 10px 0; font-size:0.8em;"><span
                                            id="charu_iconNum">0</span>/<?= $this->config->item('en_name_max') ?></span>]
                            </h4></div>
                        <input type="text" id="u_icon" value="" onkeyup="u_icon_word_count()"
                               maxlength="<?= $this->config->item('en_name_max') ?>" data-lpignore="true" placeholder=""
                               class="form-control border">


                        <div class="li_component" style="margin-top:15px;">
                            <div class="title"><h4><i class="fas fa-atlas"></i> Transaction Status</h4></div>
                            <select class="form-control" id="tr_status">
                                <?php
                                foreach (echo_status('tr_status') as $status_id => $status) {
                                    echo '<option value="' . $status_id . '" title="' . $status['s_desc'] . '">' . $status['s_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="li_component" style="margin-top:15px;">
                    <div class="title" style="margin-bottom:0; padding-bottom:0;"><h4><i class="fas fa-file-alt"></i>
                            Link Notes [<span style="margin:0 0 10px 0; font-size:0.8em;"><span
                                        id="chartr_contentNum">0</span>/<?= $this->config->item('tr_content_max') ?></span>]
                        </h4></div>
                    <textarea class="form-control text-edit border msg" id="tr_content" onkeyup="tr_content_word_count()"
                              maxlength="<?= $this->config->item('tr_content_max') ?>" data-lpignore="true"
                              style="height:66px;"></textarea>
                </div>


                <table width="100%" style="margin-top:10px;">
                    <tr>
                        <td class="save-td"><a href="javascript:u_save_modify();" class="btn btn-secondary">Save</a>
                        </td>
                        <td><span class="save_entity_changes"></span></td>
                        <td style="width:100px; text-align:right;">
                            <div class="unlink-entity"><a href="javascript:tr_unlink();" data-toggle="tooltip"
                                                          title="Only remove entity link while NOT Archiving the entity itself"
                                                          data-placement="left" style="text-decoration:none;"><i
                                            class="fas fa-unlink"></i> Unlink</a></div>

                        </td>
                    </tr>
                </table>
            </div>

        </div>


        <div id="message-frame" class="fixed-box hidden" entity-id="">

            <h5 class="badge badge-h" data-toggle="tooltip"
                title="Message management can only be done using Intents. Entity messages are listed below for view-only"
                data-placement="bottom"><i class="fas fa-comment-dots"></i> Entity Messages <i class="fas fa-lock"></i>
            </h5>
            <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;"><a href="javascript:void(0)"
                                                                                         onclick="$('#message-frame').addClass('hidden');$('#loaded-messages').html('');"><i
                            class="fas fa-times-circle"></i></a></div>
            <div class="grey-box">
                <div id="loaded-messages"></div>
            </div>

        </div>


        <?php $this->load->view('actionplans/actionplan_right_col'); ?>


    </div>
</div>