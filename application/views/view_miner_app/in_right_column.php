
<?php $en_all_6103 = $this->config->item('en_all_6103'); //Link Metadata ?>
<?php $en_all_6201 = $this->config->item('en_all_6201'); //Intent Table ?>
<?php $en_all_4341 = $this->config->item('en_all_4341'); //Link Table ?>


<script>
    //Define some global variables:
    var in_system_lock = <?= json_encode($this->config->item('in_system_lock')) ?>;

    //Include some cached entities:
    var js_en_all_4486 = <?= json_encode($this->config->item('en_all_4486')) ?>; // Intent Links
    var js_en_all_7585 = <?= json_encode($this->config->item('en_all_7585')) ?>; // Intent Types
    var js_en_all_7596 = <?= json_encode($this->config->item('en_all_7596')) ?>; // Intent Start Types
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


                    <div class="in_status_entity_id_lock hidden">
                        <b data-toggle="tooltip" title="Intent locked because its hard-coded in the Mench code base" data-placement="right" class="underdot" style="font-size: 0.85em; color: #FF0000;"><i class="fas fa-lock"></i> SYSTEM LOCK</b>
                    </div>

                    <span class="mini-header"><?= $en_all_6201[4737]['m_icon'].' '.$en_all_6201[4737]['m_name'] ?></span>
                    <select class="form-control border" id="in_status_entity_id" style="display: inline-block !important;">
                        <?php
                        foreach($this->config->item('en_all_4737') /* Intent Statuses */ as $en_id => $m){
                            echo '<option value="' . $en_id . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
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







                    <span class="<?= advance_mode() ?>">
                        <span class="mini-header" style="margin-top: 20px;"><?= $en_all_6201[7596]['m_icon'].' '.$en_all_6201[7596]['m_name'] ?></span>
                        <select class="form-control border" id="in_start_mode_entity_id" style="margin-bottom: 12px;">
                            <?php
                            foreach ($this->config->item('en_all_7596') as $en_id => $m) {
                                echo '<option value="' . $en_id . '">' . $m['m_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </span>




                    <span class="mini-header" style="margin-top: 20px;"><?= $en_all_6201[7585]['m_icon'].' '.$en_all_6201[7585]['m_name'] ?></span>
                    <select class="form-control border" id="in_type_entity_id" style="margin-bottom: 12px;">
                        <?php
                        foreach ($this->config->item('en_all_7585') as $en_id => $m) {
                            echo '<option value="' . $en_id . '">' . $m['m_name'] . '</option>';
                        }
                        ?>
                    </select>






                    <span class="mini-header" style="margin-top: 20px;"><?= $en_all_6201[4736]['m_icon'].' '.$en_all_6201[4736]['m_name'] ?> [<span
                                style="margin:0 0 10px 0;"><span
                                    id="charNameNum">0</span>/<?= $this->config->item('in_outcome_max') ?></span>]<span class="<?= advance_mode() ?>">[<a href="/entities/5008" data-toggle="tooltip" title="See (and manage) list of supporting verbs that intent outcomes can start with" data-placement="right" target="_blank"><b>Verbs</b></a>]</span></span>
                    <div class="form-group label-floating is-empty" style="height: 40px !important;">
                        <span class="white-wrapper"><textarea class="form-control text-edit msg main-box border" id="in_outcome" onkeyup="in_outcome_counter()"></textarea></span>
                    </div>





                    <div class="time-estimate-box">
                        <span class="mini-header" style="margin-top:20px;"><?= $en_all_6201[4356]['m_icon'].' '.$en_all_6201[4356]['m_name'] ?></span>
                        <div class="form-group label-floating is-empty">
                            <div class="input-group border" style="width:132px;">
                                <input style="padding-left:3px;" type="number" step="1" min="0" id="in_completion_seconds" class="form-control">
                                <span class="input-group-addon addon-lean addon-grey" style="color:#2f2739; font-weight: 300;">Seconds</span>
                            </div>
                        </div>
                    </div>




                </div>

            </div>

            <div class="col-md-6 in-has-tr">

                <div class="title">
                    <h4>
                        <i class="fas fa-link"></i> Link Settings
                    </h4>
                </div>


                <div class="inline-box" style="margin-bottom:0px;">


                    <span class="mini-header"><?= $en_all_4341[6186]['m_icon'].' '.$en_all_4341[6186]['m_name'] ?></span>
                    <select class="form-control border" id="ln_status_entity_id" style="display: inline-block !important;">
                        <?php
                        foreach($this->config->item('en_all_6186') /* Link Statuses */ as $en_id => $m){
                            echo '<option value="' . $en_id . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                        }
                        ?>
                    </select>


                    <div class="notify_unlink_in hidden">
                        <div class="alert alert-warning" style="margin:5px 0px; padding:7px;">
                            <i class="fas fa-exclamation-triangle"></i>
                            Saving will unlink intent
                        </div>
                    </div>



                    <div id="link-type-settings" class="<?= advance_mode() ?>">

                        <span class="mini-header" style="margin-top: 20px;"><?= $en_all_4341[4593]['m_icon'].' '.$en_all_4341[4593]['m_name'] ?></span>
                        <select class="form-control border" id="ln_type_entity_id" style="margin-bottom: 12px;">
                            <?php
                            foreach ($this->config->item('en_all_4486') as $en_id => $m) {
                                echo '<option value="' . $en_id . '">' . $m['m_name'] . '</option>';
                            }
                            ?>
                        </select>



                        <div class="score_range_box hidden">
                            <span class="mini-header" style="margin-top: 20px;"><?= $en_all_6103[6402]['m_icon'].' '.$en_all_6103[6402]['m_name'] ?></span>
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
                            <span class="mini-header" style="margin-top: 20px;"><?= $en_all_6103[4358]['m_icon'].' '.$en_all_6103[4358]['m_name'] ?></span>
                            <select class="form-control border" id="tr__assessment_points" style="margin-bottom:12px;">
                                <?php
                                foreach ($this->config->item('in_completion_marks') as $mark) {
                                    echo '<option value="' . $mark . '">' . $mark . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                    </div>

                </div>




                <div class="save-btn-spot">&nbsp;</div>

            </div>
        </div>

        <table class="loadcontent">
            <tr>
                <td class="save-td"><a href="javascript:in_modify_save();" class="btn btn-primary">Save</a></td>
                <td class="save-result-td"><span class="save_intent_changes"></span></td>
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