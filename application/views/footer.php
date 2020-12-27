
<?php
$user_e = superpower_unlocked();
if($user_e && ( !isset($basic_header_footer) || !$basic_header_footer )){

    $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
    $e___14393 = $this->config->item('e___14393'); //SUGGEST
    $e___13571 = $this->config->item('e___13571'); //SOURCE EDITOR
    $e___6198 = $this->config->item('e___6198'); //SOURCE ICON

    ?>

    <!-- ACCOUNT Modal -->
    <div class="modal fade indifferent" id="modal6225" tabindex="-1" role="dialog" aria-labelledby="modal6225Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title <?= extract_icon_color($e___11035[6225]['m__icon']) ?>" id="modal6225Label"><?= $e___11035[6225]['m__icon'].' '.$e___11035[6225]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    foreach($this->config->item('e___6225') as $e__id => $m) {
                        echo '<div class="headline"><span class="icon-block">'.$m['m__icon'].'</span>'.$m['m__title'].'</div>';
                        echo view_e_settings($e__id, true);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <!-- SUGGEST Modal -->
    <div class="modal fade indifferent" id="modal14393" tabindex="-1" role="dialog" aria-labelledby="modal14393Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title <?= extract_icon_color($e___11035[14393]['m__icon']) ?>" id="modal14393Label"><?= $e___11035[14393]['m__icon'].' '.$e___11035[14393]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <?php

                    //Current URL:
                    echo '<div class="headline"><span class="icon-block">'.$e___14393[14532]['m__icon'].'</span>'.$e___14393[14532]['m__title'].'</div>';
                    echo '<div id="current_url" class="padded hideIfEmpty"></div>';


                    //Type
                    echo '<div class="headline top-margin"><span class="icon-block">'.$e___14393[14394]['m__icon'].'</span>'.$e___14393[14394]['m__title'].'</div>';
                    $counter_options = 0;
                    foreach($this->config->item('e___14394') /* SUGGESTION TYPE */ as $x__type => $m){
                        $counter_options++;
                        echo '<div class="form-check">
                    <input class="form-check-input" type="radio" name="sugg_type" id="formRadio'.$x__type.'" value="'.$x__type.'" '.( $counter_options==1 ? ' checked ' : '' ).'>
                    <label class="form-check-label" for="formRadio'.$x__type.'">' . $m['m__title'] . '</label>
                </div>';
                    }

                    //Details
                    echo '<div class="headline top-margin"><span class="icon-block">'.$e___14393[14395]['m__icon'].'</span>'.$e___14393[14395]['m__title'].'</div>';
                    echo '<textarea class="form-control text-edit border" id="sugg_note" data-lpignore="true" placeholder="More details here..."></textarea>';

                    ?>


                </div>
                <div class="modal-footer">
                    <button type="button" onclick="x_suggestion()" class="btn btn-source">SUBMIT</button>
                </div>
            </div>
        </div>
    </div>






    <!-- SOURCE EDITOR Modal -->
    <div class="modal fade" id="modal13571" tabindex="-1" role="dialog" aria-labelledby="modal13571Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title <?= extract_icon_color($e___11035[13571]['m__icon']) ?>" id="modal13571Label"><?= $e___11035[13571]['m__icon'].' '.$e___11035[13571]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input type="hidden" class="modal_e__id" value="0" />
                    <input type="hidden" class="modal_x__id" value="0" />
                    <div class="save_results margin-top-down-half hideIfEmpty"></div>


                    <?php

                    //Source URL:
                    echo '<div class="headline"><span class="icon-block">'.$e___13571[13433]['m__icon'].'</span>'.$e___13571[13433]['m__title'].'</div>';
                    echo '<div class="padded"><a id="source_url" href="#" target="_blank"></a></div>';

                    ?>

                    <div class="<?= superpower_active(13422) ?>">

                        <!-- Source Status -->
                        <div class="headline no-left-padding"><?= '<span class="icon-block">'.$e___13571[6177]['m__icon'].'</span>'.$e___13571[6177]['m__title'] ?></div>
                        <select class="form-control border" id="e__type" name="e__type">
                            <?php
                            foreach($this->config->item('e___6177') /* Source Status */ as $x__type => $m){
                                echo '<option value="' . $x__type . '" title="' . $m['m__message'] . '">' . $m['m__title'] . '</option>';
                            }
                            ?>
                        </select>
                        <div class="notify_e_delete hidden">

                            <input type="hidden" id="e_x_count" value="0" />
                            <div class="msg alert alert-danger"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Saving will delete source & <span class="e_delete_stats" style="display:inline-block; padding: 0;"></span> links</div>

                        </div>


                        <!-- Source Icon -->
                        <div class="headline no-left-padding"><?= '<span class="icon-block">'.$e___13571[6198]['m__icon'].'</span>'.$e___13571[6198]['m__title'] ?>

                            <a href="javascript:void(0);" style="margin-left: 5px;" onclick="$('#e__icon').val( '<img src=&quot;https://mench.com/img/mench.png&quot; />' );update_demo_icon();" title="<?= $e___6198[4260]['m__title'].': '.$e___6198[4260]['m__message'] ?>"><?= $e___6198[4260]['m__icon'] ?></a>

                            <a href="javascript:void(0);" style="margin-left: 5px;" onclick="$('#e__icon').val( '<i class=&quot;fas fa-laugh&quot;></i>' );update_demo_icon();" title="<?= $e___6198[13577]['m__title'].': '.$e___6198[13577]['m__message'] ?>"><?= $e___6198[13577]['m__icon'] ?></a>

                            <a href="https://fontawesome.com/icons" style="margin-left: 5px;" target="_blank" title="<?= $e___6198[13578]['m__title'].': '.$e___6198[13578]['m__message'] ?>"><?= $e___6198[13578]['m__icon'] ?></a>

                        </div>
                        <div class="form-group" style="margin:0 0 13px; border-radius: 10px;">
                            <div class="input-group border">
                                <input type="text" id="e__icon" name="e__icon" value="" data-lpignore="true" placeholder="" class="form-control" style="margin-bottom: 0;">
                                <span class="input-group-addon addon-lean addon-grey icon-demo icon-block" style="padding-top:8px;"></span>
                            </div>
                        </div>


                    </div>




                    <!-- Source Title -->
                    <div class="headline no-left-padding"><?= '<span class="icon-block">'.$e___13571[6197]['m__icon'].'</span>'.$e___13571[6197]['m__title'] ?> [<span style="margin:0 0 10px 0;"><span id="charEnNum">0</span>/<?= view_memory(6404,6197) ?></span>]</div>
                    <textarea class="form-control text-edit border css__title doupper" id="e__title" name="e__title" onkeyup="e__title_word_count()" data-lpignore="true"></textarea>



                    <div class="e_has_link">

                        <div class="headline no-left-padding"><?= '<span class="icon-block">'.$e___13571[6186]['m__icon'].'</span>'.$e___13571[6186]['m__title'] ?></div>
                        <select class="form-control border" id="x__status" name="x__status">
                            <?php
                            foreach($this->config->item('e___6186') /* Transaction Status */ as $x__type => $m){
                                echo '<option value="' . $x__type . '" title="' . $m['m__message'] . '">' . $m['m__title'] . '</option>';
                            }
                            ?>
                        </select>

                        <div class="notify_unx_e hidden">
                            <div class="msg alert alert-warning"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Saving will remove source</div>
                        </div>



                        <!-- Transaction Message -->
                        <div class="headline no-left-padding" style="margin-top: 20px;"><?= '<span class="icon-block">'.$e___13571[4372]['m__icon'].'</span>'.$e___13571[4372]['m__title'] ?></div>
                        <form class="drag-box" method="post" enctype="multipart/form-data">

                            <textarea class="form-control text-edit border" id="x__message" name="x__message" data-lpignore="true" placeholder="<?= $e___13571[4372]['m__message'] ?>"></textarea>

                            <div class="pull-left">
                                <div id="x__type_preview" class="hideIfEmpty"></div>
                                <div id="x__message_preview" class="hideIfEmpty" style="width: 377px;"></div>
                            </div>

                            <div class="pull-right">
                                <input class="inputfile" type="file" name="file" id="enFile" /><label class="" for="enFile" title="<?= $e___11035[13572]['m__message'] ?>"><?= $e___11035[13572]['m__icon'] ?></label>
                            </div>

                            <div class="doclear">&nbsp;</div>

                        </form>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" onclick="e_modify_save()" class="btn btn-source">SAVE</button>
                </div>
            </div>
        </div>
    </div>





    <!-- GIF Modal -->
    <div class="modal fade" id="modal14073" tabindex="-1" role="dialog" aria-labelledby="modal14073Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title <?= extract_icon_color($e___11035[14073]['m__icon']) ?>" id="modal14073Label"><?= $e___11035[14073]['m__icon'].' '.$e___11035[14073]['m__title'] ?></h5> <!-- &nbsp;&nbsp;<img class="giphy_logo" src="https://s3foundation.s3-us-west-2.amazonaws.com/5d8ebb9a080502d42a05e175265130d4.png" /> -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal_i__id" value="0" />
                    <input type="hidden" id="modal_x__type" value="0" />
                    <input type="text" class="form-control text-edit border css__title" id="gif_query" name="gif_query" placeholder="Search GIFs..." onkeyup="gif_search('')" data-lpignore="true" />
                    <div id="gif_results" class="margin-top-down hideIfEmpty"></div>
                </div>
            </div>
        </div>
    </div>




    <!-- APP Modal -->
    <div class="modal fade indifferent" id="modal6287" tabindex="-1" role="dialog" aria-labelledby="modal6287Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title <?= extract_icon_color($e___11035[6287]['m__icon']) ?>" id="modal14393Label"><?= $e___11035[6287]['m__icon'].' '.$e___11035[6287]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    if(superpower_active(12699, true)){
                        echo '<div class="list-group">';
                        foreach($this->config->item('e___6287') as $e__id => $m) {
                            echo '<a href="/app/'.$e__id.'" class="list-group-item no-side-padding">';
                            echo '<span class="icon-block">' . view_e__icon($m['m__icon']) . '</span>';
                            echo '<b class="css__title '.extract_icon_color($m['m__icon']).'">'.$m['m__title'].'</b>';
                            echo ( strlen($m['m__message']) ? '&nbsp;'.$m['m__message'] : '' );
                            echo '</a>';
                        }
                        echo '</div>';
                    } else {
                        echo '<p><a href="">Refresh your browser</a> to load all apps.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php

}

?>

</body>