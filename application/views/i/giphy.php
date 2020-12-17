<?php
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
?>

<!-- GIF Modal -->
<div class="modal fade" id="modal14073" tabindex="-1" role="dialog" aria-labelledby="modal14073Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title css__title <?= extract_icon_color($e___11035[14073]['m__icon']) ?>" id="modal14073Label"><?= $e___11035[14073]['m__icon'] ?>&nbsp;&nbsp;<img class="giphy_logo" src="https://s3foundation.s3-us-west-2.amazonaws.com/5d8ebb9a080502d42a05e175265130d4.png" /></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="modal_i__id" value="0" />
                <input type="hidden" id="modal_x__type" value="0" />
                <input type="text" class="form-control text-edit border css__title white-border" id="gif_query" name="gif_query" placeholder="Search GIFs..." onkeyup="gif_search('')" data-lpignore="true" />
                <div id="gif_results" class="margin-top-down hideIfEmpty"></div>

            </div>
        </div>
    </div>
</div>
