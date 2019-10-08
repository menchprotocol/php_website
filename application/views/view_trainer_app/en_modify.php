<?php $en_all_7368 = $this->config->item('en_all_7368'); //Trainer App ?>
<?php $en_all_6206 = $this->config->item('en_all_6206'); //Entity Table ?>
<?php $en_all_4341 = $this->config->item('en_all_4341'); //Link Table ?>

<div id="modifybox" class="fixed-box hidden" entity-id="0" entity-link-id="0">

    <h5 class="badge badge-h edit-header"><i class="fas fa-cog"></i> Modify</h5>
    <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
        <a href="#" onclick="modify_cancel()"><i class="fas fa-times-circle"></i></a>
    </div>
    <div class="grey-box">

        <div class="row">
            <div class="col-xs-6">

                <div class="title"><h4><?= $en_all_7368[4536]['m_icon'].' Entity Settings' ?></h4></div>
                <div class="inline-box" style="margin-bottom: 15px;">


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
                    <span class="mini-header" style="margin-top:20px;"><?= $en_all_6206[6197]['m_icon'].' '.$en_all_6206[6197]['m_name'] ?> [<span style="margin:0 0 10px 0;"><span id="charNameNum">0</span>/<?= $this->config->item('en_name_max_length') ?></span>]</span>
                    <span class="white-wrapper">
                                <textarea class="form-control text-edit border" id="en_name"
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
                                   maxlength="<?= $this->config->item('en_name_max_length') ?>" data-lpignore="true" placeholder=""
                                   class="form-control">
                            <span class="input-group-addon addon-lean addon-grey icon-demo" style="color:#070707; font-weight: 300; padding-left:7px !important; padding-right:6px !important;"><i class="fas fa-at grey-at"></i></span>
                        </div>
                    </div>

                </div>

            </div>
            <div class="col-xs-6 en-has-tr">

                <div>

                    <div class="title"><h4><?= $en_all_7368[6205]['m_icon'].' Link Settings' ?></h4></div>

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
                            <span class="mini-header" style="margin-top: 20px;"><?= $en_all_4341[4372]['m_icon'].' '.$en_all_4341[4372]['m_name'] ?> [<span style="margin:0 0 10px 0;"><span id="charln_contentNum">0</span>/<?= $this->config->item('ln_content_max_length') ?></span>]</span>
                            <span class="white-wrapper">
                                    <textarea class="form-control text-edit border" id="ln_content"
                                              maxlength="<?= $this->config->item('ln_content_max_length') ?>" data-lpignore="true"
                                              placeholder="Write Message, Drop a File or Paste URL"
                                              style="height:126px; min-height:126px;">
                                    </textarea>
                                </span>

                            <span style="padding: 0; font-size: 0.8em; line-height: 100%; display: block; margin: -8px 0 0 0px; float: right;"><input class="box__file inputfile" type="file" name="file" id="file" /><label class="textarea_buttons" for="file" data-toggle="tooltip" title="Upload files up to <?= $this->config->item('max_file_mb_size') ?> MB" data-placement="top"><i class="fal fa-cloud-upload"></i> Upload</label></span>
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