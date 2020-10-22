<?php
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
?>

<!-- GIF Modal -->
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

                <input type="hidden" class="modal_i__id" value="0" />
                <input type="hidden" class="modal_x__type" value="0" />
                <div class="headline no-left-padding"><?= '<span class="icon-block">'.$e___11035[14074]['m__icon'].'</span>'.$e___11035[14074]['m__title'] ?></div>
                <input type="text" class="form-control text-edit border montserrat" id="gif_query" name="gif_query" onkeyup="gif_search()" data-lpignore="true" />
                <div class="gif_results margin-top-down hideIfEmpty"></div>

            </div>
        </div>
    </div>
</div>
