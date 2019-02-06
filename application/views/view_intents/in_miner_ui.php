<?php
if (isset($orphan_ins)) {
    $in['in_id'] = 0;
} else {
    $metadata = unserialize($in['in_metadata']);
}

?>

<script>
    //Define some global variables:
    var in_focus_id = <?= $in['in_id'] ?>;
    var en_all_4486 = <?= json_encode($this->config->item('en_all_4486')) ?>;
    var en_all_4331 = <?= json_encode($this->config->item('en_all_4331')) ?>;
</script>
<script src="/js/custom/intent-manage-js.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>





<div class="row">
    <div class="col-md-6 cols">

        <?php
        echo '<h5 class="badge badge-h indent1">Intent #'.$in['in_id'].'</h5>';
        echo '<div class="list-group indent1">';
        echo fn___echo_in($in, 1);
        echo '</div>';
        ?>



        <?php
        //Are we showing Orphans?
        if (isset($orphan_ins)) {

            echo '<h5 class="badge badge-h"><i class="fas fa-unlink"></i> Orphan Intents</h5>';
            echo '<div class="list-group">';
            foreach ($orphan_ins as $oc) {
                echo fn___echo_in($oc, 1);
            }
            echo '</div>';

        } else {

            //Count orphans only IF we are in the top parent root:
            if ($this->config->item('in_tactic_id') == $in['in_id']) {
                $orphans_count = count($this->Database_model->fn___in_fetch(array(
                    ' NOT EXISTS (SELECT 1 FROM table_ledger WHERE in_id=tr_in_child_id AND tr_status>=0) ' => null,
                )));
                if ($orphans_count > 0) {
                    echo '<span style="padding-left:8px; display: inline-block;"><a href="/intents/fn___in_orphans">' . $orphans_count . ' Orphans &raquo;</a></span>';
                }
            }







            //Start with parents:
            echo '<h5 class="badge badge-h"><i class="fas fa-sign-in-alt"></i> <span class="li-parent-count parent-counter-' . $in['in_id'] . '">' . count($in['in__parents']) . '</span> Parent' . fn___echo__s(count($in['in__parents'])) . '</h5>';

            if (count($in['in__parents']) > 0) {
                echo '<div class="list-group list-level-2">';
                foreach ($in['in__parents'] as $parent_in) {
                    echo fn___echo_in($parent_in, 2, 0, true);
                }
                echo '</div>';
            } else {
                echo '<div class="alert alert-info" role="alert" style="margin-top: 0;"><i class="fas fa-exclamation-triangle"></i> No parent intents linked yet</div>';
            }

        }
        ?>

        <?php
        //Expand/Contract buttons
        echo '<div class="indent2">';
        echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-sign-out-alt rotate90"></i> <span class="li-children-count children-counter-' . $in['in_id'] . '">' . (isset($metadata['in__tree_in_active_count']) ? $metadata['in__tree_in_active_count'] : '') . '</span> Children</h5>';

        echo '<div id="expand_intents" style="padding-left:8px; display: inline-block;">';
        echo '<i class="fas fa-plus-square expand_all" style="font-size: 1.2em;"></i> &nbsp;';
        echo '<i class="fas fa-minus-square close_all" style="font-size: 1.2em;"></i>';
        echo '</div>';


        echo '</div>';

        echo '<div id="in_children_errors indent2"></div>'; //Show potential errors detected in the Action Plan via our JS functions...

        echo '<div id="list-in-' . $in['in_id'] . '" class="list-group list-is-children list-level-2 indent2">';
        foreach ($in['in__children'] as $child_in) {
            echo fn___echo_in($child_in, 2, $in['in_id']);
        }

        //Enable Miner to add child intents:
        echo '<div class="list-group-item list_input grey-block">
                <div class="input-group">
                    <div class="form-group is-empty" style="margin: 0; padding: 0;">
                        <input type="text"
                               class="form-control intentadder-level-2 algolia_search bottom-add"
                               maxlength="' . $this->config->item('in_outcome_max') . '"
                               intent-id="' . $in['in_id'] . '"
                               id="addintent-c-' . $in['in_id'] . '"
                               placeholder="Add #Intent">
                    </div>
                    <span class="input-group-addon" style="padding-right:8px;">
                        <span id="add_in_btn" data-toggle="tooltip" title="or press ENTER ;)"
                              data-placement="top" class="badge badge-primary pull-right"
                              style="cursor:pointer; margin: 1px 2px 0 6px;">
                            <div><i class="fas fa-plus"></i></div>
                        </span>
                    </span>
                </div>
            </div>';

        echo '</div>';
        ?>

    </div>


    <div class="col-md-6 cols">




        <div id="modifybox" class="fixed-box hidden" intent-id="0" intent-tr-id="0" level="0">

            <h5 class="badge badge-h edit-header" style="display: inline-block;"><i class="fas fa-cog"></i> Modify</h5>
            <span id="hb_598" class="help_button bold-header" intent-id="598"></span>
            <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
                <a href="javascript:void(0)" onclick="$('#modifybox').addClass('hidden')"><i
                            class="fas fa-times-circle"></i></a>
            </div>
            <div class="grey-box">

                <div class="loadbox hidden"><i class="fas fa-spinner fa-spin"></i> Loading...</div>

                <div class="row loadcontent">

                    <div class="col-md-12">
                        <div class="help_body" id="content_598"></div>
                    </div>

                    <div class="col-md-6 inlineform">


                        <div class="title"><h4><i class="fas fa-bullseye-arrow"></i> Primary Outcome [<span
                                        style="margin:0 0 10px 0; font-size:0.8em;"><span
                                            id="charNameNum">0</span>/<?= $this->config->item('in_outcome_max') ?></span>]
                                </h4></div>

                        <div class="inline-box">
                            <div class="form-group label-floating is-empty" style="height: 40px !important;">
                                <div class="input-group border">
                                <span class="input-group-addon addon-lean addon-grey"
                                      style="color:#2f2739; font-weight: 300;">To</span>
                                    <textarea class="form-control text-edit msg" id="in_outcome" onkeyup="fn___in_outcome_counter()"
                                              maxlength="<?= $this->config->item('in_outcome_max') ?>" style="height:58px !important; min-height: auto !important;margin-bottom: -9px !important;"></textarea>
                                </div>
                            </div>
                        </div>




                        <div class="title" style="margin-top:15px;"><h4><i
                                        class="fas fa-hashtag"></i> Intent Settings</h4></div>

                        <div class="inline-box" style="margin-bottom: 15px;">

                            <div class="form-group label-floating is-empty" style="margin-bottom: 0; padding-bottom: 0; display:block !important;">
                                <?php
                                foreach (fn___echo_status('in_is_any') as $in_val => $intent_type) {
                                    echo '<div class="radio" style="display:inline-block; border-bottom:1px dotted #999; margin-top: 0 !important;" data-toggle="tooltip" title="' . $intent_type['s_desc'] . '" data-placement="right">
                                        <label style="display:inline-block;">
                                            <input type="radio" id="in_is_any_' . $in_val . '" name="in_is_any" value="' . $in_val . '" />
                                            ' . $intent_type['s_icon'] . ' ' . $intent_type['s_name'] . '
                                        </label>
                                    </div>';
                                }
                                ?>
                            </div>


                            <div class="and-settings">
                                <div class="form-group label-floating is-empty">
                                    <p style="margin: 0; padding: 0; font-size: 0.9em;">Response requires:</p>
                                    <div class="checkbox is_task" style="margin:0 0 10px 0;">
                                        <?php
                                        //List all the input options and allow user to pick between them:
                                        foreach ($this->config->item('en_all_4331') as $en_id => $m) {
                                            echo '<label style="font-size: 0.9em !important; margin-left:8px;"><input type="checkbox" class="in_input_requirements" id="require__' . $en_id . '" req-en-id="' . $en_id . '" /><span class="underdot" data-toggle="tooltip" title="Accepts ' . $m['m_name'] . ' to be marked as complete" data-placement="right">' . $m['m_icon'] . ' ' . $m['m_name'] . '</span></label><br />';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="or-settings hidden">
                                <p>Student chooses one of the children as their pathway.</p>
                            </div>


                            <select class="form-control border" id="in_status" original-status="" data-toggle="tooltip" title="Intent Status" data-placement="top" style="display: inline-block !important;">
                                <?php
                                foreach (fn___echo_status('in_status') as $status_id => $status) {
                                    echo '<option value="' . $status_id . '" title="' . $status['s_desc'] . '">' . $status['s_name'] . '</option>';
                                }
                                ?>
                            </select>
                            <span class="checkbox apply-recursive inline-block hidden">
                                <label style="display:inline-block !important; font-size: 0.9em !important; margin-left:8px;">
                                    <input type="checkbox" id="apply_recursively"/>
                                    <span class="underdot" data-toggle="tooltip" data-placement="top"
                                          title="If chcecked will also apply the new status recursively down (children, grandchildren, etc...) that have the same original status">Recursive
                                    </span>
                                </label>
                            </span>

                            <div class="notify_in_remove hidden">
                                <div class="alert alert-warning" style="margin:5px 0px; padding:7px;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Saving will remove intent and unlink from all parents/children
                                </div>
                            </div>

                        </div>





                        <div class="title" style="margin-top: 15px;"><h4><i class="fal fa-cloud-upload"></i> Webhook</h4></div>
                        <div class="inline-box" style="margin-bottom: 15px;">
                            <div class="form-group label-floating is-empty">
                                <div class="input-group border">
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;"><?= $this->config->item('in_webhook_prefix') ?></span>
                                    <input style="padding-left:0;" type="text" id="in_webhook" class="form-control">
                                </div>
                            </div>
                        </div>





                    </div>

                    <div class="col-md-6">

                        <div class="title">
                            <h4>
                                <i class="fal fa-money-bill-wave"></i>
                                Completion Cost
                            </h4>
                        </div>

                        <div class="inline-box">

                            <div class="form-group label-floating is-empty" style="max-width:160px;">
                                <div class="input-group border">
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;"><i
                                                class="fal fa-clock"></i></span>
                                    <input style="padding-left:3px;" type="number" step="1" min="0"
                                           max="<?= $this->config->item('in_seconds_max') ?>" id="in_seconds" value=""
                                           class="form-control">
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;">Seconds</span>
                                </div>
                            </div>

                            <div class="form-group label-floating is-empty" style="max-width:160px; margin-bottom:1px;">
                                <div class="input-group border">
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;"><i
                                                class="fal fa-usd-circle"></i></span>
                                    <input style="padding-left:3px;" type="number" step="0.01" min="0" max="5000"
                                           id="in_usd" value="" class="form-control">
                                    <span class="input-group-addon addon-lean addon-grey"
                                          style="color:#2f2739; font-weight: 300;">USD</span>
                                </div>
                            </div>

                        </div>





                        <div class="title" style="margin-top:15px;">
                            <h4>
                                <i class="fal fa-tachometer-fast"></i>
                                Assessment Points
                            </h4>
                        </div>
                        <div class="inline-box">
                            <select class="form-control border" id="in_points">
                                <?php
                                foreach ($this->config->item('in_points_options') as $point) {
                                    echo '<option value="' . $point . '">' . ($point == 0 ? 'None' : $point . ' Point' . fn___echo__s($point)) . '</option>';
                                }
                                ?>
                            </select>
                        </div>







                        <div class="title" style="margin-top: 15px;">
                            <h4>
                                <i class="fas fa-atlas"></i>
                                Ledger Transaction
                            </h4>
                        </div>


                        <div class="inline-box" style="margin-bottom:0px;">

                            <div class="in-no-tr hidden">
                                <p>No transaction available as your viewing the intent itself.</p>
                            </div>

                            <div class="in-has-tr">
                                <div class="form-group label-floating is-empty">
                                    <?php
                                    foreach ($this->config->item('en_all_4486') as $en_id => $m) {
                                        echo '<div class="radio" style="display:inline-block; border-bottom:1px dotted #999; margin-top: 0 !important;" data-toggle="tooltip" title="' . $m['m_desc'] . '" data-placement="top">
                                        <label style="display:inline-block;">
                                            <input type="radio" id="tr_type_en_id_' . $en_id . '" name="tr_type_en_id" value="' . $en_id . '" />
                                            '.$m['m_icon'].' ' . $m['m_name'] . '
                                        </label>
                                    </div>';
                                    }
                                    ?>
                                </div>

                                <div class="score_range_box hidden">
                                    <div class="form-group label-floating is-empty"
                                         style="max-width:230px; margin:1px 0 10px;">
                                        <div class="input-group border">
                                            <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;">IF Scores </span>
                                            <input style="padding-left:0; padding-right:0; text-align:right;" type="text"
                                                   maxlength="3" id="tr__conditional_score_min" value="" class="form-control">
                                            <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300; border-left: 1px solid #ccc;"><i
                                                        class="fal fa-fas fa-percentage"></i> to </span>
                                            <input style="padding-left:3px; padding-right:0; text-align:right;" type="text"
                                                   maxlength="3" id="tr__conditional_score_max" value="" class="form-control">
                                            <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300; border-left: 1px solid #ccc; border-right:0px solid #FFF;"><i
                                                        class="fal fa-fas fa-percentage"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <select class="form-control border" data-toggle="tooltip" title="Transaction Status" data-placement="top" id="tr_status" style="display: inline-block !important;">
                                    <?php
                                    foreach (fn___echo_status('tr_status') as $status_id => $status) {
                                        if($status_id < 3){ //No need to verify intent links!
                                            echo '<option value="' . $status_id . '" title="' . $status['s_desc'] . '">' . $status['s_name'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>

                                <div class="notify_in_unlink hidden">
                                    <div class="alert alert-warning" style="margin:5px 0px; padding:7px;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Saving will unlink intent
                                    </div>
                                </div>

                                <span class="tr-last-updated"></span>

                            </div>

                        </div>




                        <div class="save-btn-spot">&nbsp;</div>

                    </div>
                </div>

                <table class="save-btn-box loadcontent">
                    <tr>
                        <td class="save-result-td"><span class="save_intent_changes"></span></td>
                        <td class="save-td"><a href="javascript:fn___in_modify_save();" class="btn btn-primary">Save</a></td>
                    </tr>
                </table>

            </div>

        </div>

        <?php $this->load->view('view_ledger/tr_actionplan_right_column'); ?>


    </div>
</div>