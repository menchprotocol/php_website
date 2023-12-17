
</div><!-- Container -->

<?php

$member_e = superpower_unlocked();
if($member_e && ( !isset($basic_header_footer) || !$basic_header_footer )){

    $e___11035 = $this->config->item('e___11035'); //NAVIGATION

    //This is a HACK! 12 is a fixed number of max dynamic variables that is fixed in save_i() & save_e()
    $dynamic_edit = '';
    for ($p = 1; $p <= view_memory(6404,42206); $p++) {
        $dynamic_edit .= '<div class="dynamic_item hidden dynamic_' . $p . '">';
        $dynamic_edit .= '<h3 class="main__title mini-font"></h3>';
        $dynamic_edit .= '<input type="text" class="form-control unsaved_warning save_dynamic_'.$p.'" value="">';
        $dynamic_edit .= '</div>';
    }

    //Apply to All Sources
    if(superpower_unlocked(12703)){
        ?>
        <div class="modal fade" id="modal4997" tabindex="-1" role="dialog" aria-labelledby="modal4997Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content slim_flat">

                    <div class="modal-header">
                        <h5 class="modal-title main__title" id="modal4997Label"><?= $e___11035[4997]['m__cover'].' '.$e___11035[4997]['m__title'] ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="<?= view_app_link(27196) ?>?focus_id=12274">
                    <input type="hidden" name="s__id" value="" />
                    <div class="modal-body">
                            <?php

                            //Mass Editor:
                            $dropdown_options = '';
                            $input_options = '';
                            $editor_counter = 0;

                            foreach($this->config->item('e___4997') as $action_e__id => $e_list_action) {


                                $editor_counter++;
                                $dropdown_options .= '<option value="' . $action_e__id . '" title="'.$e_list_action['m__message'].'">' .$e_list_action['m__title'] . '</option>';
                                $is_upper = ( in_array($action_e__id, $this->config->item('n___12577') /* SOURCE UPDATER UPPERCASE */) ? ' main__title ' : false );


                                //Start with the input wrapper:
                                $input_options .= '<span title="'.$e_list_action['m__message'].'" class="mass_id_'.$action_e__id.' inline-block '. ( $editor_counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';




                                if(in_array($action_e__id, array(5000, 5001, 10625))){

                                    //String Find and Replace:

                                    //Find:
                                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'" placeholder="Search" class="form-control border '.$is_upper.'">';

                                    //Replace:
                                    $input_options .= '<input type="text" name="mass_value2_'.$action_e__id.'" placeholder="Replace" class="form-control border '.$is_upper.'">';


                                } elseif(in_array($action_e__id, array(5981, 12928, 12930, 5982, 13441, 26149))){

                                    //Member search box:

                                    //String command:
                                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search sources..." class="form-control algolia_search e_text_search border '.$is_upper.'">';

                                    //We don't need the second value field here:
                                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" placeholder="Search Source" />';


                                } elseif($action_e__id==11956){

                                    //IF HAS THIS
                                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="IF THIS SOURCE..." class="form-control algolia_search e_text_search border '.$is_upper.'">';

                                    //ADD THIS
                                    $input_options .= '<input type="text" name="mass_value2_'.$action_e__id.'"  placeholder="ADD THIS SOURCE..." class="form-control algolia_search e_text_search border '.$is_upper.'">';


                                } elseif($action_e__id==5003){

                                    //Member Status update:

                                    //Find:
                                    $input_options .= '<select name="mass_value1_'.$action_e__id.'" class="form-control border">';
                                    $input_options .= '<option value="*">Update All Statuses</option>';
                                    foreach($this->config->item('e___6177') /* Source Status */ as $x__type3 => $m3){
                                        $input_options .= '<option value="'.$x__type3.'">Update All '.$m3['m__title'].'</option>';
                                    }
                                    $input_options .= '</select>';

                                    //Replace:
                                    $input_options .= '<select name="mass_value2_'.$action_e__id.'" class="form-control border">';
                                    $input_options .= '<option value="">Set New Status...</option>';
                                    foreach($this->config->item('e___6177') /* Source Status */ as $x__type3 => $m3){
                                        $input_options .= '<option value="'.$x__type3.'">Set to '.$m3['m__title'].'</option>';
                                    }
                                    $input_options .= '</select>';


                                } elseif($action_e__id==5865){

                                    //Transaction Status update:

                                    //Find:
                                    $input_options .= '<select name="mass_value1_'.$action_e__id.'" class="form-control border">';
                                    $input_options .= '<option value="*">Update All Statuses</option>';
                                    foreach($this->config->item('e___6186') /* Transaction Status */ as $x__type3 => $m3){
                                        $input_options .= '<option value="'.$x__type3.'">Update All '.$m3['m__title'].'</option>';
                                    }
                                    $input_options .= '</select>';

                                    //Replace:
                                    $input_options .= '<select name="mass_value2_'.$action_e__id.'" class="form-control border">';
                                    $input_options .= '<option value="">Set New Status...</option>';
                                    foreach($this->config->item('e___6186') /* Transaction Status */ as $x__type3 => $m3){
                                        $input_options .= '<option value="'.$x__type3.'">Set to '.$m3['m__title'].'</option>';
                                    }
                                    $input_options .= '</select>';


                                } else {

                                    //String command:
                                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="String..." class="form-control border '.$is_upper.'">';

                                    //We don't need the second value field here:
                                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

                                }

                                $input_options .= '</span>';

                            }

                            //Drop Down
                            echo '<select class="form-control border mass_action_toggle" name="mass_action_toggle">';
                            echo $dropdown_options;
                            echo '</select>';

                            echo $input_options;

                            ?>
                            <div class="mass_apply_preview"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default">APPLY TO ALL</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }


    //Apply to All Ideas
    if(superpower_unlocked(12700)){
        ?>
        <div class="modal fade" id="modal12589" tabindex="-1" role="dialog" aria-labelledby="modal12589Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content slim_flat">
                    <div class="modal-header">
                        <h5 class="modal-title main__title" id="modal12589Label"><?= $e___11035[12589]['m__cover'].' '.$e___11035[12589]['m__title'] ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="<?= view_app_link(27196) ?>?focus_id=12273">
                    <input type="hidden" name="s__id" value="" />
                    <div class="modal-body">
                        <?php

                        //IDEA LIST EDITOR
                        $dropdown_options = '';
                        $input_options = '';
                        $this_counter = 0;

                        foreach($this->config->item('e___12589') as $action_e__id => $e_list_action) {

                            $this_counter++;
                            $dropdown_options .= '<option value="' . $action_e__id . '">' .$e_list_action['m__title'] . '</option>';


                            //Start with the input wrapper:
                            $input_options .= '<span title="'.$e_list_action['m__message'].'" class="mass_id_'.$action_e__id.' inline-block '. ( $this_counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';

                            if(in_array($action_e__id, array(12591,27080,27985,27082,27084,27086))){

                                //Source search box:

                                //String command:
                                $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search Sources..." class="form-control algolia_search e_text_search border main__title">';

                                //We don't need the second value field here:
                                $input_options .= '<input type="text" name="mass_value2_'.$action_e__id.'" value="" />';

                            } elseif(in_array($action_e__id, array(12592,27081,27986,27083,27085,27087))){

                                //Source search box:

                                //String command:
                                $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search Sources..." class="form-control algolia_search e_text_search border main__title">';

                                //We don't need the second value field here:
                                $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

                            } elseif(in_array($action_e__id, array(12611,12612,27240,28801))){

                                //String command:
                                $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search Ideas..." class="form-control algolia_search i_text_search border main__title">';

                                //We don't need the second value field here:
                                $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

                            }

                            $input_options .= '</span>';

                        }

                        //Drop Down
                        echo '<select class="form-control border mass_action_toggle" name="mass_action_toggle">';
                        echo $dropdown_options;
                        echo '</select>';

                        echo $input_options;

                        ?>
                        <div class="mass_apply_preview"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-default">APPLY TO ALL</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
    }

    ?>


    <!-- Edit Idea Modal -->
    <div class="modal fade" id="modal31911" tabindex="-1" role="dialog" aria-labelledby="modal31911Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content slim_flat">

                <div class="modal-header">
                    <h5 class="modal-title main__title" id="modal31911Label"><?= $e___11035[31911]['m__cover'].' '.$e___11035[31911]['m__title'] ?> <span class="grey show_id" title="Idea ID"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" class="save_i__id" value="0" />
                    <input type="hidden" class="save_x__id" value="0" />
                    <input type="hidden" class="link_i__id" value="0" />

                    <div class="save_results hideIfEmpty zq6255 alert alert-danger" style="margin:8px 0;"></div>

                    <div class="dynamic_editing_loading hidden"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Loading...</div>

                    <div class="row">
                        <div class="col-12 col-md-8">

                            <div class="add_notes_form">
                            <form class="box box4736" method="post" enctype="multipart/form-data">

                            <!-- Idea Hashtag -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">#</span>
                                <input type="text" class="form-control unsaved_warning save_i__hashtag" placeholder="<?= $e___11035[32337]['m__title'] ?>" maxlength="<?= view_memory(6404,41985) ?>">
                            </div>

                            <!-- Idea Message -->
                            <textarea class="form-control note-textarea algolia_search new-note editing-mode unsaved_warning save_i__message" placeholder="<?= $e___11035[4736]['m__title'] ?>" style="margin:0; width:100%; background-color: #FFFFFF !important;"></textarea>

                            <?php

                            //UPLOAD
                            echo '<input class="inputfile hidden" type="file" name="file" id="fileIdeaType4736" />';
                            echo '<label class="hidden"></label>';
                            echo '<label class="btn inline-block btn-compact file_label_4736" for="fileIdeaType4736" title="'.$e___11035[13572]['m__title'].' '.$e___11035[13572]['m__message'].'"><span class="icon-block">'.$e___11035[13572]['m__cover'].'</span></label>';

                            //GIF
                            //echo '<a class="btn btn-compact inline-block" href="javascript:void(0);" onclick="image_api_search()" title="'.$e___11035[14073]['m__title'].'"><span class="icon-block">'.$e___11035[14073]['m__cover'].'</span></a>';

                            ?>

                            <!-- Link Message -->
                            <textarea class="form-control text-edit border hidden unsaved_warning save_x__message" data-lpignore="true" placeholder="Idea Link Message"></textarea>




                            <div class="dynamic_editing_input"><?= $dynamic_edit ?></div>


                            </form>
                            </div>

                        </div>
                        <div class="col-12 col-md-4">
                            <div class="dynamic_editing_radio"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="save_i()" class="btn btn-default">SAVE</button>
                </div>

            </div>
        </div>
    </div>




    <!-- Edit Source Modal -->
    <div class="modal fade" id="modal31912" tabindex="-1" role="dialog" aria-labelledby="modal31912Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content slim_flat">

                <div class="modal-header">
                    <h5 class="modal-title main__title" id="modal31912Label"><?= $e___11035[31912]['m__cover'].' '.$e___11035[31912]['m__title'] ?> <span class="grey show_id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" class="save_e__id" value="0" />
                    <input type="hidden" class="save_x__id" value="0" />
                    <div class="save_results hideIfEmpty zq6255 alert alert-danger" style="margin:8px 0;"></div>

                    <div class="dynamic_editing_loading hidden"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Loading...</div>

                    <div class="row">
                        <div class="col-12 col-md-7">

                            <!-- Source Handle -->
                            <div class="input-group mb-3">
                                <span class="input-group-text">@</span>
                                <input type="text" class="form-control unsaved_warning save_e__handle" placeholder="Source Handle" maxlength="<?= view_memory(6404,41985) ?>">
                            </div>

                            <!-- Source Title -->
                            <input type="text" required placeholder="Source Title" class="form-control main__title unsaved_warning save_e__title" />


                            <!-- Source Cover -->
                            <input type="text" value="" data-lpignore="true" placeholder="Emoji, Image URL or Icon Code" class="form-control border-dotted unsaved_warning save_e__cover" style="margin-top: 5px;">
                            <table style="width: 100%; margin-bottom: 21px;">
                                <tr>
                                    <td style="width: 100%;">
                                        <!-- SEARCH -->
                                        <input id="search_cover" type="text" style="padding-left: 0; padding-right: 0;" class="form-control text-edit border-dotted cover_query algolia_search" placeholder="Search Covers..." data-lpignore="true" />
                                    </td>
                                    <td>
                                        <!-- DELETE -->
                                        <a class="icon-block" href="javascript:void(0);" title="Clear Cover" onclick="update__cover('')"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                    <td>
                                        <!-- UPLOAD -->
                                        <input class="inputfile" type="file" name="file" id="coverUpload" />
                                        <label class="icon-block" for="coverUpload"><?= $e___11035[25990]['m__cover'] ?></label>
                                    </td>
                                </tr>
                            </table>


                            <div id="upload_results" class="center"></div>
                            <div id="img_results_emojis" class="icons_small"></div>
                            <div class="doclear">&nbsp;</div>
                            <div id="return_covers" class="icons_small"></div>
                            <div id="img_results_icons" class="icons_small"></div>
                            <div class="doclear">&nbsp;</div>
                            <div id="img_results_local" class="icons_large"></div>
                            <div id="img_results_tenor" class="icons_large"></div>
                            <div id="img_results_unsplash" class="icons_large"></div>
                            <div class="doclear">&nbsp;</div>


                            <!-- Link Message -->
                            <textarea class="form-control text-edit border hidden unsaved_warning save_x__message" data-lpignore="true" placeholder="Source Link Message"></textarea>

                            <div class="dynamic_editing_input"><?= $dynamic_edit ?></div>


                        </div>
                        <div class="col-12 col-md-5">

                            <!-- IMAGE DROP -->
                            <div class="coverUploader">
                                <form class="box coverUpload" method="post" enctype="multipart/form-data">
                                    <a name="preview_cover" style="height: 1px;">&nbsp;</a>
                                    <div class="card_cover demo_cover" style="width: 233px !important; margin:-55px auto 21px !important;">
                                        <div class="cover-wrapper"><div class="black-background-obs cover-link" style=""><div class="cover-btn"></div></div></div>
                                        <!-- <div class="cover-content"><div class="inner-content"><span></span></div></div> -->
                                    </div>
                                </form>
                            </div>

                            <div class="dynamic_editing_radio"></div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="save_e()" class="btn btn-default">SAVE</button>
                </div>
            </div>
        </div>
    </div>





    <!-- GIF Modal -->
    <div class="modal fade" id="modal14073" tabindex="-1" role="dialog" aria-labelledby="modal14073Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content slim_flat">
                <div class="modal-header">
                    <h5 class="modal-title main__title" id="modal14073Label"><?= $e___11035[14073]['m__cover'].' '.$e___11035[14073]['m__title'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control text-edit border main__title images_query" placeholder="Search GIFs..." onkeyup="images_search($('.images_query').val())" data-lpignore="true" />
                    <div class="row new_images margin-top-down hideIfEmpty"></div>
                </div>
            </div>
        </div>
    </div>


    <?php

}

?>


</body>
</html>