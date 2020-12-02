
<?php
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
?>

<!-- SUGGEST Modal -->
<div class="modal fade" id="modal14073" tabindex="-1" role="dialog" aria-labelledby="modal14073Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title montserrat <?= extract_icon_color($e___11035[14073]['m__icon']) ?>" id="modal14073Label"><?= $e___11035[14073]['m__icon'].' '.$e___11035[14073]['m__title'] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


                <!-- Source Status -->
                <div class="headline no-left-padding"><?= '<span class="icon-block">'.$e___13571[6177]['m__icon'].'</span>'.$e___13571[6177]['m__title'] ?></div>
                <select class="form-control border" id="e__type" name="e__type">
                    <?php
                    foreach($this->config->item('e___6177') /* Source Status */ as $x__type => $m){
                        echo '<option value="' . $x__type . '" title="' . $m['m__message'] . '">' . $m['m__title'] . '</option>';
                    }
                    ?>
                </select>

                <!-- Source Title -->
                <div class="headline no-left-padding"><?= '<span class="icon-block">'.$e___13571[6197]['m__icon'].'</span>'.$e___13571[6197]['m__title'] ?> [<span style="margin:0 0 10px 0;"><span id="charEnNum">0</span>/<?= view_memory(6404,6197) ?></span>]</div>
                <textarea class="form-control text-edit border montserrat doupper" id="e__title" name="e__title" onkeyup="e__title_word_count()" data-lpignore="true"></textarea>



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
    </div>
</div>


</body>