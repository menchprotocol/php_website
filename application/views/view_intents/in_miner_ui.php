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
    <div class="col-xs-7 cols">
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
            if ($this->config->item('in_tactic_id') == $in['in_id'] && 0) {
                $orphans_count = count($this->Database_model->fn___in_fetch(array(
                    ' NOT EXISTS (SELECT 1 FROM table_ledger WHERE in_id=tr_child_intent AND tr_status>=0) ' => null,
                )));
                if ($orphans_count > 0) {
                    echo '<span style="padding-left:8px; display: inline-block;"><a href="/intents/fn___in_orphans">' . $orphans_count . ' Orphans &raquo;</a></span>';
                }
            }




            //Start with parents:
            echo '<h5 class="badge badge-h"><span class="li-parent-count parent-counter-' . $in['in_id'] . '">' . count($in['in__parents']) . '</span> Parent' . fn___echo__s(count($in['in__parents'])) . '</h5>';

            echo '<div id="list-in-' . $in['in_id'] . '-1" class="list-group list-level-2">';

            foreach ($in['in__parents'] as $parent_in) {
                echo fn___echo_in($parent_in, 2, 0, true);
            }

            //Enable Miner to add child intents:
            echo '<div class="list-group-item list_input grey-block">
                    <div class="form-group is-empty" style="margin: 0; padding: 0;">
                        <input type="text"
                               class="form-control intentadder-level-2 algolia_search bottom-add"
                               maxlength="' . $this->config->item('in_outcome_max') . '"
                               intent-id="' . $in['in_id'] . '"
                               is-parent="1"
                               id="addintent-c-' . $in['in_id'] . '-1"
                               placeholder="Add #Intent">
                    </div>
                   
            </div>';


            echo '</div>';



        }

        //The intent it-self:
        echo '<h5 class="badge badge-h indent1" style="display: inline-block;">Intent #'.$in['in_id'].'</h5>';
        echo '<a class="secret" href="/cron/fn___in_metadata_update/' . $in['in_id'] . '/1?redirect=/' . $in['in_id'] . '" style="margin-left: 5px;" onclick="fn___turn_off()"><i class="fal fa-sync-alt" data-toggle="tooltip" title="Updates intent tree cache" data-placement="top"></i></a>';

        echo '<div class="list-group indent1">';
        echo fn___echo_in($in, 1);
        echo '</div>';



        //Expand/Contract buttons
        echo '<div class="indent2">';
        echo '<h5 class="badge badge-h" style="display: inline-block;"><span class="li-children-count children-counter-' . $in['in_id'] . '">' . (isset($metadata['in__tree_in_active_count']) ? intval($metadata['in__tree_in_active_count'])-1 : '') . '</span> Children</h5>';

        echo '<div id="expand_intents" style="padding-left:8px; display: inline-block;">';
        echo '<i class="fas fa-plus-circle expand_all" style="font-size: 1.2em;"></i> &nbsp;';
        echo '<i class="fas fa-minus-circle close_all" style="font-size: 1.2em;"></i>';
        echo '</div>';


        echo '</div>';

        echo '<div id="in_children_errors indent2"></div>'; //Show potential errors detected in the Action Plan via our JS functions...

        echo '<div id="list-in-' . $in['in_id'] . '-0" class="list-group list-is-children list-level-2 indent2">';
        foreach ($in['in__children'] as $child_in) {
            echo fn___echo_in($child_in, 2, $in['in_id']);
        }

        //Enable Miner to add child intents:
        echo '<div class="list-group-item list_input grey-block">
                    <div class="form-group is-empty" style="margin: 0; padding: 0;">
                        <input type="text"
                               class="form-control intentadder-level-2 algolia_search bottom-add"
                               maxlength="' . $this->config->item('in_outcome_max') . '"
                               intent-id="' . $in['in_id'] . '"
                               is-parent="0"
                               id="addintent-c-' . $in['in_id'] . '-0"
                               placeholder="Add #Intent">
                    </div>
                   
            </div>';

        echo '</div>';
        ?>

    </div>


    <div class="col-xs-5 cols">




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


                        <div class="title"><h4><i
                                        class="fas fa-hashtag"></i> Intent Settings
                            </h4></div>


                        <div class="inline-box" style="margin-bottom: 15px;">

                            <span class="mini-header">Primary Outcome: [<span
                                        style="margin:0 0 10px 0;"><span
                                            id="charNameNum">0</span>/<?= $this->config->item('in_outcome_max') ?></span>]</span>
                            <div class="form-group label-floating is-empty" style="height: 40px !important;">
                                <div class="input-group border">
                                <span class="input-group-addon addon-lean addon-grey"
                                      style="color:#2f2739; font-weight: 300;">To</span>
                                    <textarea class="form-control text-edit msg main-box" id="in_outcome" onkeyup="fn___in_outcome_counter()"
                                              maxlength="<?= $this->config->item('in_outcome_max') ?>"></textarea>
                                </div>
                            </div>



                            <span class="mini-header">Intent Type:</span>
                            <div class="form-group label-floating is-empty" style="margin-bottom: 0; padding-bottom: 0; display:block !important;">
                                <?php
                                foreach (fn___echo_status('in_is_any') as $in_val => $intent_type) {
                                    echo '<span class="radio" style="display:inline-block; margin-top: 0 !important;" data-toggle="tooltip" title="' . $intent_type['s_desc'] . '" data-placement="top">
                                        <label class="underdot" style="display:inline-block;">
                                            <input type="radio" id="in_is_any_' . $in_val . '" name="in_is_any" value="' . $in_val . '" />
                                            ' . $intent_type['s_icon'] . ' ' . $intent_type['s_name'] . '
                                        </label>
                                    </span>';
                                }
                                ?>
                            </div>


                            <span class="mini-header">Completion Response:</span>
                            <select class="form-control border" id="in_requirement_entity" data-toggle="tooltip" title="Intent Completion Requirements" data-placement="top" style="margin-bottom: 12px;">
                                <option value="0">No Response Required</option>
                                <?php
                                foreach ($this->config->item('en_all_4331') as $en_id => $m) {
                                    echo '<option value="' . $en_id . '">Require ' . $m['m_name'] . ' Response</option>';
                                }
                                ?>
                            </select>



                            <span class="mini-header">Completion Cost:</span>
                            <div class="form-group label-floating is-empty">
                                <div class="input-group border" style="width: 155px;">
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;"><i
                                                class="fal fa-clock"></i></span>
                                    <input style="padding-left:3px;" type="number" step="1" min="0"
                                           max="<?= $this->config->item('in_seconds_cost_max') ?>" id="in_seconds_cost" value=""
                                           class="form-control">
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;">Seconds</span>
                                </div>
                            </div>

                            <div class="form-group label-floating is-empty">
                                <div class="input-group border" style="margin-top:1px; width: 155px;">
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;"><i
                                                class="fal fa-usd-circle"></i></span>
                                    <input style="padding-left:3px;" type="number" step="0.01" min="0" max="5000"
                                           id="in_dollar_cost" value="" class="form-control">
                                    <span class="input-group-addon addon-lean addon-grey"
                                          style="color:#2f2739; font-weight: 300;">USD</span>
                                </div>
                            </div>



                            <span class="mini-header">Intent Status:</span>
                            <select class="form-control border" id="in_status" original-status="" data-toggle="tooltip" title="Intent Status" data-placement="top" style="display: inline-block !important;">
                                <?php
                                foreach (fn___echo_status('in_status') as $status_id => $status) {
                                    echo '<option value="' . $status_id . '" title="' . $status['s_desc'] . '">' . $status['s_name'] . '</option>';
                                }
                                ?>
                            </select>
                            <span class="checkbox apply-recursive inline-block hidden">
                                <label style="display:inline-block !important; font-size: 0.9em !important; margin-left:5px;">
                                    <input type="checkbox" id="apply_recursively"/>
                                    <span class="underdot" data-toggle="tooltip" data-placement="top"
                                          title="If chcecked will also apply the new status recursively down (children, grandchildren, etc...) that have the same original status">Recursive
                                    </span>
                                </label>
                            </span>

                            <div class="notify_in_remove hidden">
                                <div class="alert alert-danger" style="margin:5px 0px; padding:7px;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Saving will archive intent and unlink all parents and children
                                </div>
                            </div>




                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="title">
                            <h4>
                                <i class="fas fa-atlas"></i> Transaction Settings
                            </h4>
                        </div>


                        <div class="inline-box" style="margin-bottom:0px;">

                            <div class="in-no-tr hidden">
                                <p>No transaction available as your viewing the intent itself.</p>
                            </div>

                            <div class="in-has-tr">


                                <div class="modify_parent_in hidden">
                                    <span class="mini-header"><span class="tr_in_link_title"></span> Linked Intent:</span>
                                    <input style="padding-left:3px;" type="text" class="form-control algolia_search border in_quick_search" id="tr_in_link_update" value="" placeholder="Search replacement intent..." />
                                </div>


                                <span class="mini-header">Link Type: [<a href="javscript:void(0);" onclick="$('.modify_parent_in').toggleClass('hidden')" data-toggle="tooltip" title="Modify Linked Intent" data-placement="top"><u>EDIT</u></a>]</span>
                                <div class="form-group label-floating is-empty">

                                    <?php
                                    foreach ($this->config->item('en_all_4486') as $en_id => $m) {
                                        echo '<div class="radio" style="display:block; margin-top: 0 !important; width:190px;" data-toggle="tooltip" title="' . $m['m_desc'] . '" data-placement="top">
                                            <label class="underdot">
                                                <input type="radio" id="tr_type_entity_' . $en_id . '" name="tr_type_entity" value="' . $en_id . '" />
                                                '.$m['m_icon'].' ' . $m['m_name'] . '
                                            </label>
                                        </div>';
                                    }
                                    ?>

                                </div>

                                <div class="score_range_box hidden">
                                    <span class="mini-header">Assessment Score:</span>
                                    <div class="form-group label-floating is-empty"
                                         style="max-width:230px; margin:1px 0 10px;" data-toggle="tooltip" title="Min/Max assessment score between 0-100%" data-placement="top">
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

                                <div class="score_points hidden">
                                    <span class="mini-header">Completion Points:</span>
                                    <select class="form-control border" id="tr__assessment_points" data-toggle="tooltip" title="Points adjusted when student completes intent" data-placement="top" style="margin-bottom:12px;">
                                        <?php
                                        foreach (array(-233, -144, -89, -55, -34, -21, -13, -8, -5, -3, -2, -1, 0, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144, 233) as $point) {
                                            echo '<option value="' . $point . '">' . ( $point>=0 ? 'Award ' : 'Subtract ' ) . ($point == 0 ? 'No Points' : abs($point) . ' Point' . fn___echo__s($point)) . '</option>';
                                        }//bottom-add
                                        ?>
                                    </select>
                                </div>


                                <span class="mini-header">Transaction Status:</span>
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