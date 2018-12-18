<?php
if (isset($orphan_ins)) {
    $in['in_id'] = 0;
}
?>

<script>
    //Define some global variables:
    var in_focus_id = <?= $in['in_id'] ?>;
</script>
<script src="/js/custom/intent-manage-js.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>


<div class="row">
    <div class="col-xs-6 cols">
        <?php
        if (isset($orphan_ins)) {

            echo '<div id="bootcamp-objective" class="list-group">';
            foreach ($orphan_ins as $oc) {
                echo echo_c($oc, 1);
            }
            echo '</div>';

        } else {

            //Start with parents:
            echo '<h5 class="badge badge-h"><i class="fas fa-sign-in-alt"></i> <span class="li-parent-count parent-counter-' . $in['in_id'] . '">' . count($in['in__parents']) . '</span> Parent' . echo__s(count($in['in__parents'])) . '</h5>';

            if (count($in['in__parents']) > 0) {
                echo '<div class="list-group list-level-2">';
                foreach ($in['in__parents'] as $sub_intent) {
                    echo echo_c($sub_intent, 2, 0, true);
                }
                echo '</div>';
            } else {
                echo '<div class="alert alert-info" role="alert" style="margin-top: 0;"><i class="fas fa-exclamation-triangle"></i> No parent intents linked yet</div>';
            }


            echo '<h5 class="badge badge-h indent1"><i class="fas fa-hashtag"></i> Intent</h5>';
            echo '<div id="bootcamp-objective" class="list-group indent1">';
            echo echo_c($in, 1);
            echo '</div>';


            //Expand/Contract buttons
            echo '<div class="indent2">';
            echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-sign-out-alt rotate90"></i> <span class="li-children-count children-counter-' . $in['in_id'] . '">' . $in['in__tree_in_count'] . '</span> Children</h5>';

            echo '<div id="expand_intents" style="padding-left:8px; display: inline-block;">';
            echo '<i class="fas fa-plus-square expand_all" style="font-size: 1.2em;"></i> &nbsp;';
            echo '<i class="fas fa-minus-square close_all" style="font-size: 1.2em;"></i>';
            echo '</div>';


            //Count orphans IF we are in the top parent root:
            if ($this->config->item('in_primary_id') == $in['in_id']) {
                $orphans_count = count($this->Database_model->in_fetch(array(
                    ' NOT EXISTS (SELECT 1 FROM table_ledger WHERE in_id=tr_in_child_id AND tr_status>=0) ' => null,
                )));
                if ($orphans_count > 0) {
                    echo '<span style="padding-left:8px; display: inline-block;"><a href="/intents/fn___in_orphans">' . $orphans_count . ' Orphans &raquo;</a></span>';
                }
            }

            echo '</div>';

            echo '<div id="outs_error indent2"></div>'; //Show potential errors detected in the Action Plan via our JS functions...

            echo '<div id="list-c-' . $in['in_id'] . '" class="list-group list-is-children list-level-2 indent2">';
            foreach ($in['in__children'] as $sub_intent) {
                echo echo_c($sub_intent, 2, $in['in_id']);
            }

            echo '<div class="list-group-item list_input grey-block">
                <div class="input-group">
                    <div class="form-group is-empty" style="margin: 0; padding: 0;">
                        <input type="text"
                               class="form-control intentadder-level-2 algolia_search bottom-add"
                               maxlength="'.$this->config->item('in_outcome_max').'"
                               intent-id="'.$in['in_id'].'"
                               id="addintent-c-'.$in['in_id'].'"
                               placeholder="Add #Intent">
                    </div>
                    <span class="input-group-addon" style="padding-right:8px;">
                        <span id="add_in_btn" data-toggle="tooltip" title="or press ENTER ;)"
                              data-placement="top" class="badge badge-primary pull-right"
                              style="cursor:pointer; margin: 1px 3px 0 6px;">
                            <div><i class="fas fa-plus"></i></div>
                        </span>
                    </span>
                </div>
            </div>';

            echo '</div>';

        }
        ?>

    </div>


    <div class="col-xs-6 cols">


        <div id="modifybox" class="fixed-box hidden" intent-id="0" intent-link-id="0" level="0">

            <h5 class="badge badge-h"><i class="fas fa-cog"></i> Modify Intent</h5>
            <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;"><a href="javascript:void(0)"
                                                                                         onclick="$('#modifybox').addClass('hidden')"><i
                            class="fas fa-times-circle"></i></a></div>

            <div class="grey-box">


                <div>
                    <div class="title"><h4><i class="fas fa-bullseye-arrow"></i> Target Outcome [<span
                                    style="margin:0 0 10px 0; font-size:0.8em;"><span
                                        id="charNameNum">0</span>/<?= $this->config->item('in_outcome_max') ?></span>]
                            <span id="hb_598" class="help_button" intent-id="598"></span></h4></div>
                    <div class="help_body maxout" id="content_598"></div>

                    <div class="form-group label-floating is-empty">
                        <div class="input-group border">
                            <span class="input-group-addon addon-lean"
                                  style="color:#2f2739; font-weight: 300;">To</span>
                            <input style="padding-left:0;" type="text" id="in_outcome" onkeyup="in_outcome_counter()"
                                   maxlength="<?= $this->config->item('in_outcome_max') ?>" value=""
                                   class="form-control">
                        </div>
                    </div>
                </div>


                <div class="title" style="margin-top:15px;"><h4><i class="fas fa-comment-edit"></i> Trigger Statements
                        <span id="hb_7724" class="help_button" intent-id="7724"></span></h4></div>
                <div class="help_body maxout" id="content_7724"></div>
                <textarea class="form-control text-edit border msg" id="c_trigger_statements"
                          style="height:56px; background-color:#FFFFFF !important;"></textarea>


                <div class="row">
                    <div class="col-md-6 inlineform">

                        <div class="title" style="margin-top:15px;"><h4><i
                                        class="fas fa-hashtag"></i> Intent Status</h4></div>
                        <select class="form-control" id="in_status" style="display: inline-block !important;">
                            <?php
                            foreach (echo_status('in') as $status_id => $status) {
                                echo '<option value="' . $status_id . '" title="' . $status['s_desc'] . '">' . $status['s_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <span class="checkbox" style="display: inline-block !important;">
                            <label style="display:inline-block !important; font-size: 0.9em !important; margin-left:8px;"><input
                                        type="checkbox" id="apply_recurively"/><span class="underdot"
                                                                                     data-toggle="tooltip"
                                                                                     title="Applies the new status recursively down to all children/grandchildren that have the same starting status. Page will refresh after saving."
                                                                                     data-placement="top">Apply Recursively <i
                                            class="fas fa-info-circle"></i></span></label>
                        </span>


                        <div class="title" style="margin-top:15px;"><h4><i class="fas fa-check-square"></i> Completion
                                Method</h4></div>
                        <div class="form-group label-floating is-empty">
                            <?php
                            foreach (echo_status('in_is_any') as $in_val => $intent_type) {
                                echo '<div class="radio" style="display:inline-block; border-bottom:1px dotted #999; margin-top: 0 !important;" data-toggle="tooltip" title="' . $intent_type['s_desc'] . '" data-placement="right">
                                    <label style="display:inline-block;">
                                        <input type="radio" id="in_is_any_' . $in_val . '" name="in_is_any" value="' . $in_val . '" />
                                        <i class="' . $intent_type['s_icon'] . '"></i> ' . $intent_type['s_name'] . '
                                    </label>
                                </div>';
                            }
                            ?>
                        </div>

                        <div class="form-group label-floating is-empty completion-settings">
                            <div class="checkbox is_task">
                                <p>Require Any Of:</p>
                                <?php
                                //List all the input options and allow user to pick between them:
                                foreach ($this->config->item('en_all_4331') as $en_id => $en) {
                                    echo '<label ' . (strlen($en['tr_content']) > 0 ? ' class="underdot" data-toggle="tooltip" title="' . stripslashes($en['tr_content']) . '" data-placement="top" ' : '') . ' style="display: block; font-size: 0.9em !important; margin-left:8px;"><input type="checkbox" id="require__' . $en_id . '" />' . $en['en_icon'] . ' ' . $en['en_name'] . '</label>';
                                }
                                ?>
                                <p style="color: #999;">(No Requirements if none selected)</p>
                            </div>
                        </div>


                        <div class="title" style="margin-top:15px;"><h4><i class="fas fa-weight"></i> Completion Points
                            </h4></div>
                        <select class="form-control" id="c_points">
                            <?php
                            foreach ($this->config->item('in_points_options') as $point) {
                                echo '<option value="' . $point . '">' . ($point == 0 ? 'Disabled' : $point . ' Point' . echo__s($point)) . '</option>';
                            }
                            ?>
                        </select>

                    </div>

                    <div class="col-md-6" style="margin-top:15px;">

                        <div id="c_link_access" class="hidden">
                            <div class="title"><h4><i
                                            class="fas fa-atlas"></i> Transaction Status
                                </h4></div>
                            <select class="form-control" id="tr_status" style="display: inline-block !important;">
                                <?php
                                foreach (echo_status('tr_status') as $status_id => $status) {
                                    echo '<option value="' . $status_id . '" title="' . $status['s_desc'] . '">' . $status['s_name'] . '</option>';
                                }
                                ?>
                            </select>
                            <div class="notify_cr_delete hidden">
                                <div class="alert alert-warning" style="margin:5px 0px; padding:7px;"><i
                                            class="fas fa-exclamation-triangle"></i> Warning: You are about to remove
                                    this link
                                </div>
                            </div>
                            <div class="form-group label-floating is-empty score_range_box hidden"
                                 style="max-width:230px;">
                                <div class="input-group border">
                                    <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;">If scores </span>
                                    <input style="padding-left:0; padding-right:0; text-align:right;" type="text"
                                           maxlength="3" id="cr_condition_min" value="" class="form-control">
                                    <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;"><i
                                                class="fal fa-fas fa-percentage"></i> to </span>
                                    <input style="padding-left:0; padding-right:0; text-align:right;" type="text"
                                           maxlength="3" id="cr_condition_max" value="" class="form-control">
                                    <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;"><i
                                                class="fal fa-fas fa-percentage"></i></span>
                                </div>
                            </div>
                        </div>


                        <div class="title" style="margin-top:15px;"><h4><i class="fas fa-piggy-bank"></i> Resources</h4>
                        </div>
                        <div class="form-group label-floating is-empty" style="max-width:150px;">
                            <div class="input-group border">
                                <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;"><i
                                            class="fas fa-clock"></i></span>
                                <input style="padding-left:0;" type="number" step="1" min="0"
                                       max="<?= $this->config->item('in_seconds_max') ?>" id="in_seconds" value=""
                                       class="form-control">
                                <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;">Minutes</span>
                            </div>
                        </div>

                        <div id="child-hours" style="margin-left:6px;"></div>

                        <div class="form-group label-floating is-empty" style="max-width:150px;">
                            <div class="input-group border">
                                <span class="input-group-addon addon-lean" style="color:#2f2739; font-weight: 300;"><i
                                            class="fas fa-usd-circle"></i></span>
                                <input style="padding-left:0;" type="number" step="0.01" min="0" max="5000"
                                       id="c_cost_estimate" value="" class="form-control">
                                <span class="input-group-addon addon-lean"
                                      style="color:#2f2739; font-weight: 300;">USD</span>
                            </div>
                        </div>


                    </div>
                </div>


                <table width="100%" style="margin-top:10px;">
                    <tr>
                        <td class="save-td"><a href="javascript:c_save_modify();" class="btn btn-primary">Save</a></td>
                        <td><span class="save_intent_changes"></span></td>
                        <td style="width:80px; text-align:right;"></td>
                    </tr>
                </table>
            </div>

        </div>

        <?php $this->load->view('view_ledger/tr_actionplan_right_column'); ?>


    </div>
</div>