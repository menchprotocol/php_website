
<script>
    //Define some global variables:
    var in_system_lock = <?= json_encode($this->config->item('in_system_lock')) ?>;
    var en_all_4486 = <?= json_encode($this->config->item('en_all_4486')) ?>;
    var en_all_6676 = <?= json_encode($this->config->item('en_all_6676')) ?>; //AND/OR Branch
    var en_all_6192 = <?= json_encode($this->config->item('en_all_6192')) ?>; //AND Children
    var en_all_6193 = <?= json_encode($this->config->item('en_all_6193')) ?>; //OR Children
</script>
<script src="/js/custom/intent-right-column.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>



<div id="modifybox" class="fixed-box hidden" intent-id="0" intent-tr-id="0" level="0">

    <h5 class="badge badge-h edit-header" style="display: inline-block;"><i class="fas fa-cog"></i> Modify</h5>
    <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
        <a href="#" onclick="modify_cancel()"><i class="fas fa-times-circle"></i></a>
    </div>
    <div class="grey-box">

        <div class="loadbox hidden"><i class="fas fa-spinner fa-spin"></i> Loading...</div>

        <div class="row loadcontent">

            <div class="col-md-6 inlineform">


                <div class="title"><h4><i
                            class="fas fa-hashtag"></i> Intent Settings
                    </h4></div>


                <div class="inline-box" style="margin-bottom: 15px;">


                    <div class="in_status_lock hidden">
                        <b data-toggle="tooltip" title="Intent locked because its hard-coded in the Mench code base" data-placement="top" class="underdot" style="font-size: 0.85em; color: #FF0000;"><i class="fas fa-lock"></i> SYSTEM LOCK</b>
                    </div>

                    <span class="mini-header">Intent Status:</span>
                    <select class="form-control border" id="in_status" style="display: inline-block !important;">
                        <?php
                        foreach (echo_fixed_fields('in_status') as $status_id => $status) {
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
                            Saving will remove intent and unlink all parents and children
                        </div>
                    </div>



                    <span class="mini-header" style="margin-top: 20px;">Outcome: [<span
                            style="margin:0 0 10px 0;"><span
                                id="charNameNum">0</span>/<?= $this->config->item('in_outcome_max') ?></span>][<a href="/entities/5008" data-toggle="tooltip" title="See (and manage) list of supporting verbs that intent outcomes can start with" data-placement="right" target="_blank"><b>Verbs</b></a>]</span>
                    <div class="form-group label-floating is-empty" style="height: 40px !important;">
                        <span class="white-wrapper"><textarea class="form-control text-edit msg main-box border" id="in_outcome" onkeyup="in_outcome_counter()"></textarea></span>
                    </div>



                    <span class="mini-header" style="margin-top: 20px;">Intent Type:</span>
                    <div class="form-group label-floating is-empty" style="margin-bottom:-5px; padding-bottom: 0; display:block !important;">
                        <?php
                        //Either 6192 AND or 6193 OR:
                        foreach ($this->config->item('en_all_6676') as $en_id => $m) {
                            echo '<span class="radio" style="display:inline-block; margin-right: 7px; margin-top: 0 !important;">
                                        <label style="display:inline-block;">
                                            <input type="radio" name="in_6676_type" id="parent__type_'.$en_id.'" value="' . $en_id . '" />
                                            ' . $m['m_icon'] . ' ' . $m['m_name'] . '
                                        </label>
                                    </span>';
                        }
                        ?>
                    </div>

                    <!-- AND Intents -->
                    <div class="show-all-types show-for-6192">
                        <select class="form-control border intent-sub-type" id="in_6192_type" style="margin-bottom: 12px;">
                            <?php
                            foreach ($this->config->item('en_all_6192') as $en_id => $m) {
                                echo '<option value="' . $en_id . '">' . $m['m_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- OR Intents -->
                    <div class="show-all-types show-for-6193 hidden">
                        <select class="form-control border intent-sub-type" id="in_6193_type" style="margin-bottom: 12px;">
                            <?php
                            foreach ($this->config->item('en_all_6193') as $en_id => $m) {
                                echo '<option value="' . $en_id . '">' . $m['m_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>




                    <div class="time-estimate-box">
                        <span class="mini-header" style="margin-top:20px;">Completion Time:</span>
                        <div class="form-group label-floating is-empty">
                            <div class="input-group border" style="width: 155px;">
                                        <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;"><i
                                                    class="fal fa-clock"></i></span>
                                <input style="padding-left:3px;" type="number" step="1" min="0"
                                       max="<?= $this->config->item('in_max_seconds') ?>" id="in_completion_seconds" value=""
                                       class="form-control">
                                <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;">Seconds</span>
                            </div>
                        </div>
                    </div>



                </div>

            </div>

            <div class="col-md-6">

                <div class="title">
                    <h4>
                        <i class="fas fa-link"></i> Link Settings
                    </h4>
                </div>


                <div class="inline-box" style="margin-bottom:0px;">

                    <div class="in-no-tr hidden">
                        <p>Not applicable because you are viewing the intent itself.</p>
                    </div>

                    <div class="in-has-tr">


                        <div class="modify_parent_in hidden">
                            <span class="mini-header"><span class="tr_in_link_title"></span> Linked Intent:</span>
                            <input style="padding-left:3px;" type="text" class="form-control algolia_search border in_quick_search" id="tr_in_link_update" value="" placeholder="Search replacement intent..." />
                        </div>


                        <span class="mini-header">Link Type: <span class="<?= echo_advance() ?>">[<a href="javscript:void(0);" onclick="$('.modify_parent_in').toggleClass('hidden')" data-toggle="tooltip" title="Modify Linked Intent" data-placement="top"><u>EDIT</u></a>]</span></span>
                        <div class="form-group label-floating is-empty">

                            <?php
                            foreach ($this->config->item('en_all_4486') as $en_id => $m) {
                                echo '<span class="radio" style="display:inline-block; margin-top: 0 !important;">
                                            <label>
                                                <input type="radio" id="ln_type_entity_id_' . $en_id . '" name="ln_type_entity_id" value="' . $en_id . '" />
                                                '.$m['m_icon'].' ' . $m['m_name'] . '
                                            </label>
                                        </span>';
                            }
                            ?>

                        </div>


                        <?php $en_all_6410 = $this->config->item('en_all_6410'); ?>
                        <div class="score_range_box hidden">
                            <span class="mini-header"><?= $en_all_6410[6402]['m_name'] ?>:</span>
                            <div class="form-group label-floating is-empty"
                                 style="max-width:230px; margin:1px 0 10px;">
                                <div class="input-group border">
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;">IF Scores </span>
                                    <input style="padding-left:0; padding-right:0; text-align:right;" type="number" step="1" data-lpignore="true"
                                           maxlength="3" id="tr__conditional_score_min" value="" class="form-control">
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300; border-left: 1px solid #ccc;"><i
                                            class="fal fa-fas fa-percentage"></i> to </span>
                                    <input style="padding-left:3px; padding-right:0; text-align:right;" type="number" step="1" data-lpignore="true"
                                           maxlength="3" id="tr__conditional_score_max" value="" class="form-control">
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300; border-left: 1px solid #ccc; border-right:0px solid #FFF;"><i
                                            class="fal fa-fas fa-percentage"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="score_points hidden">
                            <span class="mini-header"><?= $en_all_6410[4358]['m_name'] ?>:</span>
                            <select class="form-control border" id="tr__assessment_points" style="margin-bottom:12px;">
                                <?php
                                foreach ($this->config->item('in_completion_marks') as $mark) {
                                    echo '<option value="' . $mark . '">' . $mark . '</option>';
                                }
                                ?>
                            </select>
                        </div>


                        <span class="mini-header" style="margin-top: 20px;">Link Status:</span>
                        <select class="form-control border" id="ln_status" style="display: inline-block !important;">
                            <?php
                            foreach (echo_fixed_fields('ln_status') as $status_id => $status) {
                                if($status_id < 3){ //No need to verify intent links!
                                    echo '<option value="' . $status_id . '" title="' . $status['s_desc'] . '">' . $status['s_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>

                        <div class="notify_unlink_in hidden">
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
                <td class="save-td"><a href="javascript:in_modify_save();" class="btn btn-primary">Save</a></td>
            </tr>
        </table>

    </div>

</div>


<div id="load_messaging_frame" class="fixed-box hidden">
    <h5 class="badge badge-h badge-h-max"></h5>
    <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
        <a href="#" onclick="modify_cancel()"><i class="fas fa-times-circle"></i></a>
    </div>
    <div class="grey-box" style="padding-bottom: 10px;">
        <iframe class="ajax-frame hidden" id="ajax_messaging_iframe" src=""></iframe>
        <span class="frame-loader hidden"><i class="fas fa-spinner fa-spin"></i> Loading...</span></div>
</div>



<div id="load_action_plan_frame" class="fixed-box hidden">

    <h5 class="badge badge-h badge-h-max"></h5>

    <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
        <a href="#" onclick="modify_cancel()"><i class="fas fa-times-circle"></i></a>
    </div>

    <div class="grey-box" style="padding-bottom: 10px;">
        <div id="ap_matching_users"></div>
    </div>


</div>