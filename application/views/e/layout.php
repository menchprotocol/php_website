<?php

$e___6206 = $this->config->item('e___6206'); //MENCH SOURCE
$e___4341 = $this->config->item('e___4341'); //Transaction Table
$e___6177 = $this->config->item('e___6177'); //Source Status
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
$miner_is_e = miner_is_e($e['e__id']);

?>


<style>
    /* For a cleaner UI hide the current focused source parent */
    .e_child_icon_<?= $e['e__id'] ?>{ display:none; }
</style>

<script>
    //Set global variables:
    var e_focus_filter = -1; //No filter, show all
    var e_focus_id = <?= $e['e__id'] ?>;
</script>

<script src="/application/views/e/layout.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container e-ui">

    <?php
    //SOURCE NAME
    echo '<div class="itemsource">'.view_input_text(6197, $e['e__title'], $e['e__id'], ($miner_is_e && in_array($e['e__status'], $this->config->item('n___7358'))), 0, true, '<span class="e_ui_icon_'.$e['e__id'].'">'.view_e__icon($e['e__icon']).'</span>', extract_icon_color($e['e__icon'])).'</div>';

    ?>

    <div id="modifybox" class="fixed-box hidden" e-id="0" e-x-id="0" style="padding: 5px;">

        <h5 class="badge badge-h edit-header"><i class="fas fa-pen-square"></i> Modify</h5>
        <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
            <a href="javascript:void(0);" onclick="modify_cancel()"><i class="fas fa-times"></i></a>
        </div>

        <div class="grey-box">
            <div class="row">
                <div class="col-md-6">
                    <div class="inline-box">

                        <!-- Miner Status -->
                        <span class="mini-header"><?= $e___6206[6177]['m_icon'].' '.$e___6206[6177]['m_name'] ?></span>
                        <select class="form-control border" id="e__status">
                            <?php
                            foreach($this->config->item('e___6177') /* Source Status */ as $x__type => $m){
                                echo '<option value="' . $x__type . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <div class="notify_e_delete hidden">

                            <input type="hidden" id="e_x_count" value="0" />
                            <div class="alert alert-danger"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Saving will delete this source and UNLINK ALL <span class="e_delete_stats" style="display:inline-block; padding: 0;"></span> transactions</div>

                            <span class="mini-header"><span class="tr_i_x_title"></span> Merge Source Into:</span>
                            <input style="padding-left:3px;" type="text" class="form-control algolia_search border e_text_search" id="e_merge" value="" placeholder="Search source to merge..." />

                        </div>



                        <!-- Miner Name -->
                        <span class="mini-header" style="margin-top:20px;"><?= $e___6206[6197]['m_icon'].' '.$e___6206[6197]['m_name'] ?> [<span style="margin:0 0 10px 0;"><span id="charEnNum">0</span>/<?= config_var(6197) ?></span>]</span>
                        <span class="white-wrapper">
                                <textarea class="form-control text-edit border montserrat doupper" id="e__title"
                                          onkeyup="e__title_word_count()" data-lpignore="true"
                                          style="height:66px; min-height:66px;">
                                </textarea>
                            </span>



                        <!-- Miner Icon -->
                        <span class="mini-header"><?= $e___6206[6198]['m_icon'].' '.$e___6206[6198]['m_name'] ?>

                                <a href="javascript:void(0);" style="margin-left: 5px;" onclick="$('#e__icon').val($('#e__icon').val() + '<i class=&quot;fas fa-&quot;></i>' )" data-toggle="tooltip" title="Insert blank Font-Awesome HTML code" data-placement="top"><i class="far fa-edit"></i><b>FA</b></a>

                                <a href="https://fontawesome.com/icons" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Visit Font-Awesome website for a full list of icons and their HTML code" data-placement="top"><i class="fas fa-external-x"></i></a>

                            </span>
                        <div class="form-group label-floating is-empty"
                             style="margin:1px 0 10px;">
                            <div class="input-group border">
                                <input type="text" id="e__icon" value=""
                                       maxlength="<?= config_var(6197) ?>" data-lpignore="true" placeholder=""
                                       class="form-control">
                                <span class="input-group-addon addon-lean addon-grey icon-demo icon-block"></span>
                            </div>
                        </div>



                    </div>

                </div>
                <div class="col-md-6 en-has-tr">

                    <div>

                        <div class="inline-box">


                            <span class="mini-header"><?= $e___4341[6186]['m_icon'].' '.$e___4341[6186]['m_name'] ?></span>
                            <select class="form-control border" id="x__status">
                                <?php
                                foreach($this->config->item('e___6186') /* Transaction Status */ as $x__type => $m){
                                    echo '<option value="' . $x__type . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                                }
                                ?>
                            </select>

                            <div class="notify_unx_e hidden">
                                <div class="alert alert-warning"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Saving will remove source</div>
                            </div>




                            <form class="drag-box" method="post" enctype="multipart/form-data">
                                <span class="mini-header" style="margin-top: 20px;"><?= $e___4341[4372]['m_icon'].' '.$e___4341[4372]['m_name'] ?></span>
                                <span class="white-wrapper">
                                    <textarea class="form-control text-edit border" id="x__message"
                                              data-lpignore="true"
                                              placeholder="Write, Drop a File or Paste URL"
                                              style="height:126px; min-height:126px;">
                                    </textarea>
                                </span>

                                <span><input class="inputfile" type="file" name="file" id="enFile" /><label class="" for="enFile" data-toggle="tooltip" title="Upload files up to <?= config_var(11063) ?> MB" data-placement="top"><i class="fal fa-cloud-upload"></i> Upload</label></span>
                            </form>


                            <span class="mini-header"><?= $e___4341[4593]['m_icon'].' '.$e___4341[4593]['m_name'] ?></span>
                            <span id="x__type_preview"></span>
                            <p id="e_x_preview" class="hideIfEmpty"></p>



                        </div>

                    </div>

                </div>

            </div>

            <table>
                <tr>
                    <td class="save-td"><a href="javascript:e_update();" class="btn btn-e btn-save">Save</a></td>
                    <td class="save-result-td"><span class="save_e_changes"></span></td>
                </tr>
            </table>

        </div>

    </div>

    <?php



    //FOR EDITING ONLY:
    echo '<div class="hidden">'.view_e($e).'</div>';



    //NAME & STATUS
    echo '<div class="doclear">&nbsp;</div>';
    echo '<div class="pull-right inline-block" style="margin:8px 0 -40px 0;">';


    //SOURCE DRAFTING?
    echo '<span class="icon-block e__status_' . $e['e__id'] . ( in_array($e['e__status'], $this->config->item('n___7357')) ? ' hidden ' : '' ).'"><span data-toggle="tooltip" data-placement="bottom" title="'.$e___6177[$e['e__status']]['m_name'].': '.$e___6177[$e['e__status']]['m_desc'].'">' . $e___6177[$e['e__status']]['m_icon'] . '</span></span>';

    //Modify
    echo '<a href="javascript:void(0);" onclick="e_modify_load(' . $e['e__id'] . ',0)" class="icon-block grey '.superpower_active(13422).'" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$e___11035[12275]['m_name'].'">'.$e___11035[12275]['m_icon'].'</a>';


    //ADMIN MENU
    if(superpower_assigned(12703)){
        $e___4527 = $this->config->item('e___4527'); //Platform Memory
        echo '<ul class="nav nav-tabs nav-sm" style="display: inline-block; border: 0; margin: 0;">';
        echo view_caret(12887, $e___4527[12887], $e['e__id']);
        echo '</ul>';
    }


    echo '</div>';
    echo '<div class="doclear">&nbsp;</div>';

    //Display Both tabs:
    view_e_tabs(11089, $e, $session_e, $miner_is_e);
    view_e_tabs(13522, $e, $session_e, $miner_is_e);

    ?>

</div>