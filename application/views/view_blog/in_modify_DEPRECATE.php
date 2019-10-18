
<?php $en_all_6103 = $this->config->item('en_all_6103'); //Link Metadata ?>
<?php $en_all_6201 = $this->config->item('en_all_6201'); //Intent Table ?>
<?php $en_all_4341 = $this->config->item('en_all_4341'); //Link Table ?>
<?php $en_all_7368 = $this->config->item('en_all_7368'); //Trainer App ?>




<div id="modifybox" class="fixed-box hidden" intent-id="0" intent-tr-id="0" level="0">

    <div class="grey-box">

        <div class="loadbox hidden"><i class="far fa-yin-yang fa-spin"></i> <?= echo_random_message('loading_notify') ?></div>

        <div class="row loadcontent">

            <div class="col-md-6 inlineform">

                <div class="title"><h4><?= $en_all_7368[4535]['m_icon'].' Intent Settings' ?></h4></div>

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

                    <div class="notify_in_remove hidden">
                        <div class="alert alert-danger" style="margin:5px 0px; padding:7px;">
                            <i class="fas fa-exclamation-triangle"></i>
                            Saving will archive this intent and UNLINK ALL links
                        </div>
                    </div>




                    <span class="mini-header" style="margin-top: 20px;"><?= $en_all_6201[7585]['m_icon'].' '.$en_all_6201[7585]['m_name'] ?></span>
                    <select class="form-control border" id="in_completion_method_entity_id" style="margin-bottom: 12px;">
                        <?php
                        foreach ($this->config->item('en_all_7585') as $en_id => $m) {
                            echo '<option value="' . $en_id . '">' . $m['m_name'] . '</option>';
                        }
                        ?>
                    </select>






                    <span class="mini-header" style="margin-top: 20px;"><?= $en_all_6201[4736]['m_icon'].' '.$en_all_6201[4736]['m_name'] ?>* [<span
                                style="margin:0 0 10px 0;"><span
                                    id="charNameNum">0</span>/<?= config_value(11071) ?></span>]</span>
                    <div class="form-group label-floating is-empty" style="height: 40px !important; margin-bottom: 0;">
                        <span class="white-wrapper"><textarea class="form-control text-edit msg main-box border" id="in_outcome" onkeyup="in_outcome_counter()"></textarea></span>
                    </div>
                    <span class="mini-header" style="margin-top:5px;">* Start with a <a href="/play/5008" data-toggle="tooltip" data-placement="top" title="Browse all supporting verbs"><b>verb</b></a> or <i class="fas fa-equals"></i> sign</span>





                    <span class="mini-header" style="margin-top:20px;"><?= $en_all_6201[4356]['m_icon'].' '.$en_all_6201[4356]['m_name'] ?></span>
                    <div class="form-group label-floating is-empty">
                        <div class="input-group border" style="width:132px;">
                            <input style="padding-left:3px;" type="number" step="1" min="0" id="in_completion_seconds" class="form-control">
                            <span class="input-group-addon addon-lean addon-grey" style="color:#070707; font-weight: 300;">Seconds</span>
                        </div>
                    </div>




                </div>

            </div>

            <div class="col-md-6 in-has-tr">


                    <div class="title"><h4><?= $en_all_7368[6205]['m_icon'].' Link Settings' ?></h4></div>

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



                    <div class="<?= require_superpower(10989 /* PEGASUS */) ?>">

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
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#070707; font-weight: 300;">IF Scores </span>
                                    <input style="padding-left:0; padding-right:0; text-align:right;" type="number" step="1" data-lpignore="true"
                                           maxlength="3" id="tr__conditional_score_min" value="" class="form-control">
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#070707; font-weight: 300; border-left: 1px solid #ccc;"><i
                                                class="fal fa-fas fa-percentage"></i> to </span>
                                    <input style="padding-left:3px; padding-right:0; text-align:right;" type="number" step="1" data-lpignore="true"
                                           maxlength="3" id="tr__conditional_score_max" value="" class="form-control">
                                    <span class="input-group-addon addon-lean addon-grey" style="color:#070707; font-weight: 300; border-left: 1px solid #ccc; border-right:0px solid #FFF;"><i
                                                class="fal fa-fas fa-percentage"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="score_points hidden">
                            <span class="mini-header" style="margin-top: 20px;"><?= $en_all_6103[4358]['m_icon'].' '.$en_all_6103[4358]['m_name'] ?></span>
                            <input class="form-control border" id="tr__assessment_points" value="">
                        </div>

                    </div>
                </div>


                <div class="save-btn-spot">&nbsp;</div>

            </div>
        </div>

        <table class="loadcontent">
            <tr>
                <td class="save-td"><a href="javascript:in_modify_save();" class="btn btn-blog">Save</a></td>
                <td class="save-result-td"><span class="save_intent_changes"></span></td>
            </tr>
        </table>

    </div>

</div>