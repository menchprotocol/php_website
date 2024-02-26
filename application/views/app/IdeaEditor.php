<?php
$e___6201 = $this->config->item('e___6201'); //IDEA Cache
$e___11035 = $this->config->item('e___11035'); //Encyclopedia
?>

<!-- Edit Idea Modal -->
<div class="i_footer_note hidden">Idea saved. <a href=""><b>View</b></a></div>
<div class="modal fade" id="modal31911" tabindex="-1" role="dialog" aria-labelledby="modal31911Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content long_flat">

            <div class="modal-header">
                <div class="initial_header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <!-- Unlink -->
                    <div class="dynamic_editing_input no_padded idea_link_unlink hidden">
                        <a class="icon-block" href="javascript:void(0);" onclick="i_editor_switch()" title="Unlink Idea / Publish a Standalone idea"><i class="far fa-unlink"></i></a>
                    </div>

                    <!-- Toggle Direction -->
                    <div class="dynamic_editing_input no_padde idea_link_direction hidden hidden_superpower__42817">
                        <a class="icon-block" href="javascript:void(0);" onclick="" title="Switch Direction"><i class="far fa-arrow-up-arrow-down"></i></a>
                    </div>

                    <!-- Idea Links -->
                    <div class="dynamic_editing_input idea_link_type hidden hidden_superpower__10939" style="margin: 0 !important;">
                        <div class="dynamic_selector"><?= view_single_select_form(4486, 4228, false, false); ?></div>
                    </div>
                </div>
                <button type="button" class="btn btn-default i_editor_save post_button" onclick="i_editor_save()">SAVE</button>
            </div>

            <div class="modal-body">

                <div class="save_results hideIfEmpty alert alert-danger" style="margin:8px 0;"></div>

                <input type="hidden" class="created_i__id" value="0" />
                <input type="hidden" class="save_i__id" value="0" />
                <input type="hidden" class="save_x__id" value="0" />
                <input type="hidden" class="next_i__id" value="0" />
                <input type="hidden" class="previous_i__id" value="0" />

                <div class="idea_list_next cover-text hideIfEmpty"></div>
                <div class="doclear">&nbsp;</div>


                <!-- Idea Creator(s) -->
                <div class="creator_box">
                    <?php
                    foreach($this->X_model->fetch(array(
                        'x__following' => $player_e['e__id'],
                        'x__type' => 41011, //PINNED FOLLOWER
                        'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    ), array('x__follower'), 0, 0, array('x__weight' => 'ASC', 'x__id' => 'DESC')) as $x_pinned) {
                        echo '<div class="creator_headline"><span class="icon-block">'.view_cover($x_pinned['e__cover']).'</span><b>'.$x_pinned['e__title'].'</b><span class="grey mini-font mini-padded mini-frame">@'.$x_pinned['e__handle'].'</span></div>';
                        //TODO maybe give the option to remove?
                    }

                    //Always append current user:
                    echo '<div class="creator_headline first_headline"><span class="icon-block">'.view_cover($player_e['e__cover']).'</span></div>';
                    ?>
                </div>

                <!-- Idea Message -->
                <div class="dynamic_editing_input" style="margin: 0 !important;">
                    <textarea class="form-control nodte-textarea algolia_finder new-note editing-mode unsaved_warning algolia__e algolia__i save_i__message" placeholder="<?= ( strlen($e___6201[4736]['m__message']) ? $e___6201[4736]['m__message'] : $e___6201[4736]['m__title'].'...' ) ?>" style="margin:0; width:100%; background-color: #FFFFFF !important;"></textarea>
                    <div class="media_outer_frame hideIfEmpty">
                        <div id="media_frame" class="media_frame hideIfEmpty"></div>
                        <div class="doclear">&nbsp;</div>
                    </div>
                </div>

                <div class="inner_message left_padded">
                    <div class="idea_list_previous hideIfEmpty"></div>
                </div>

                <div class="inner_message left_padded">

                    <!-- EMOJI -->
                    <div class="dynamic_editing_input no_padded float_right">
                        <div class="dropdown emoji_selector">
                            <button type="button" class="btn no-left-padding no-right-padding icon-block" id="emoji_i" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="far fa-face-smile"></i></button>
                            <div class="dropdown-menu emoji_i" aria-labelledby="emoji_i"></div>
                        </div>
                    </div>

                    <!-- Upload -->
                    <div class="dynamic_editing_input no_padded float_right">
                        <a class="uploader_13572 icon-block" href="javascript:void(0);" title="<?= $e___11035[13572]['m__title'] ?>"><?= $e___11035[13572]['m__cover'] ?></a>
                    </div>


                    <!-- Idea Privacy -->
                    <div class="dynamic_editing_input" style="margin: 0 !important;">
                        <div class="dynamic_selector"><?= view_single_select_form(31004, 31005, false, true); ?></div>
                    </div>

                    <!-- Idea Type -->
                    <div class="dynamic_editing_input hidden_superpower__10939" style="margin: 0 !important;">
                        <div class="dynamic_selector"><?= view_single_select_form(4737, 6677, false, true); ?></div>
                    </div>

                    <div class="doclear">&nbsp;</div>

                </div>


                <div class="hidden_superpower__10939 left_padded">

                    <!-- Dynamic Loader -->
                    <div class="dynamic_editing_loading hidden"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Loading</div>

                    <!-- Dynamic Inputs -->
                    <div class="dynamic_frame"><?= $dynamic_edit ?></div>

                    <!-- Idea Hashtag -->
                    <div class="dynamic_editing_input single_line hash_group" title="<?= $e___6201[32337]['m__title'] ?>">
                        <h3 class="mini-font"><span class="icon-block-sm"><?= $e___6201[32337]['m__cover']  ?></span></h3>
                        <input type="text" class="form-control unsaved_warning save_i__hashtag" placeholder="<?= $e___6201[32337]['m__title'] ?>" maxlength="<?= view_memory(6404,41985) ?>">
                    </div>

                    <!-- Link Note -->
                    <div class="dynamic_editing_input save_x__frame hidden">
                        <h3 class="mini-font"><?= '<span class="icon-block-sm">'.$e___11035[4372]['m__cover'].'</span>'.$e___11035[4372]['m__title'].': ';  ?></h3>
                        <textarea class="form-control border unsaved_warning save_x__message" data-lpignore="true" placeholder="..."></textarea>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
