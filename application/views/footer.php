
</div><!-- Container -->

<?php
$member_e = superpower_unlocked();
if($member_e && ( !isset($basic_header_footer) || !$basic_header_footer )){

    $e___11035 = $this->config->item('e___11035'); //NAVIGATION
    $e___14393 = $this->config->item('e___14393'); //SUGGEST
    $e___13571 = $this->config->item('e___13571'); //SOURCE EDITOR
    $e___14937 = $this->config->item('e___14937'); //SOURCE ICON

    //Apply to All Sources
    if(superpower_active(12703, true)){
        ?>
        <div class="modal fade indifferent" id="modal4997" tabindex="-1" role="dialog" aria-labelledby="modal4997Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title css__title" id="modal4997Label"><?= $e___11035[4997]['m__cover'].' '.$e___11035[4997]['m__title'] ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/-27196?e__id=12274">
                    <input type="hidden" name="coin__id" value="" />
                    <div class="modal-body">
                            <?php

                            //Mass Editor:
                            $dropdown_options = '';
                            $input_options = '';
                            $editor_counter = 0;

                            foreach($this->config->item('e___4997') as $action_e__id => $e_list_action) {


                                $editor_counter++;
                                $dropdown_options .= '<option value="' . $action_e__id . '" title="'.$e_list_action['m__message'].'">' .$e_list_action['m__title'] . '</option>';
                                $is_upper = ( in_array($action_e__id, $this->config->item('n___12577') /* SOURCE UPDATER UPPERCASE */) ? ' css__title ' : false );


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


                                } elseif($action_e__id == 11956){

                                    //IF HAS THIS
                                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="IF THIS SOURCE..." class="form-control algolia_search e_text_search border '.$is_upper.'">';

                                    //ADD THIS
                                    $input_options .= '<input type="text" name="mass_value2_'.$action_e__id.'"  placeholder="ADD THIS SOURCE..." class="form-control algolia_search e_text_search border '.$is_upper.'">';


                                } elseif($action_e__id == 5003){

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


                                } elseif($action_e__id == 5865){

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
                            <div class="apply_preview"></div>
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
    if(superpower_active(12700, true)){
        ?>
        <div class="modal fade indifferent" id="modal12589" tabindex="-1" role="dialog" aria-labelledby="modal12589Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title css__title" id="modal12589Label"><?= $e___11035[12589]['m__cover'].' '.$e___11035[12589]['m__title'] ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="/-27196?e__id=12273">
                    <input type="hidden" name="coin__id" value="" />
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

                            if(in_array($action_e__id, array(12591,12592,27080,27985,27081,27986,27082,27083,27084,27085,27086,27087))){

                                //Source search box:

                                //String command:
                                $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search Sources..." class="form-control algolia_search e_text_search border css__title">';

                                //We don't need the second value field here:
                                $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

                            } elseif(in_array($action_e__id, array(12611,12612,27240,28801))){

                                //String command:
                                $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search Ideas..." class="form-control algolia_search i_text_search border css__title">';

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
                        <div class="apply_preview"></div>
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

    <!-- ACCOUNT SETTINGS Modal -->
    <div class="modal fade indifferent" id="modal6225" tabindex="-1" role="dialog" aria-labelledby="modal6225Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title" id="modal6225Label"><?= $e___11035[6225]['m__cover'].' '.$e___11035[6225]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= view_e_settings(6225, false) ?>
                </div>
            </div>
        </div>
    </div>






    <!-- Edit Idea Message Modal -->
    <div class="modal fade indifferent" id="modal27963" tabindex="-1" role="dialog" aria-labelledby="modal27963Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title" id="modal27963Label"><?= $e___11035[27963]['m__cover'].' '.$e___11035[27963]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control msg note-textarea indifferent algolia_search new-note power_editor editing-mode emoji-input input_note_4231" x__type="4231" placeholder="Write..." style="margin:0; width:100%;"></textarea>
                    <div class="note_error_4231 hideIfEmpty zq6255 msg alert alert-danger indifferent" style="margin:8px 0;"></div>
                </div>
                <div class="modal-footer">
                    <table>
                        <tr>
                            <td style="width: 100%;">
                                <?php

                                //CONTROLLER
                                echo '<div class="no-padding add_notes_4231">';
                                echo '<div class="add_notes_form note_pad indifferent">';
                                echo '<form class="box box4231" method="post" enctype="multipart/form-data">';

                                //UPLOAD
                                echo '<input class="inputfile hidden" type="file" name="file" id="fileIdeaType4231" />';
                                echo '<label class="hidden"></label>';
                                echo '<label class="btn inline-block btn-compact file_label_4231" for="fileIdeaType4231" title="'.$e___11035[13572]['m__title'].' '.$e___11035[13572]['m__message'].'"><span class="icon-block">'.$e___11035[13572]['m__cover'].'</span></label>';

                                //GIF
                                echo '<a class="btn btn-compact inline-block" href="javascript:void(0);" onclick="images_modal(4231)" title="'.$e___11035[14073]['m__title'].'"><span class="icon-block">'.$e___11035[14073]['m__cover'].'</span></a>';

                                //EMOJI
                                echo '<span class="btn btn-compact inline-block" id="emoji_pick_type4231" title="'.$e___11035[14038]['m__title'].'"><span class="icon-block">'.$e___11035[14038]['m__cover'].'</span></span>';


                                echo '</form>';
                                echo '</div>';
                                echo '</div>';
                                ?>
                            </td>
                            <td style="width: 50px;">
                                <?= '<div class="save_button inline-block save_button_4231 hidden"><a href="javascript:save_message_27963()" class="btn btn-default save_notes_4231" style="width:104px;" title="Shortcut: Ctrl + Enter">'.$e___11035[14422]['m__cover'].' '.$e___11035[14422]['m__title'].'</a></div>' ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>









    <!-- SUGGEST Modal -->
    <div class="modal fade indifferent" id="modal14393" tabindex="-1" role="dialog" aria-labelledby="modal14393Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title" id="modal14393Label"><?= $e___11035[14393]['m__cover'].' '.$e___11035[14393]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <?php

                    //Current URL:
                    echo '<div class="headline"><span class="icon-block">'.$e___14393[14927]['m__cover'].'</span>'.$e___14393[14927]['m__title'].'</div>';
                    echo '<div class="current_url padded hideIfEmpty inline-block"></div>';



                    //Type
                    echo '<div class="headline top-margin"><span class="icon-block">'.$e___14393[14394]['m__cover'].'</span>'.$e___14393[14394]['m__title'].'</div>';
                    $counter_options = 0;
                    foreach($this->config->item('e___14394') /* SUGGESTION TYPE */ as $x__type => $m){
                        $counter_options++;
                        echo '<div class="form-check">
                    <input class="form-check-input" type="radio" name="sugg_type" id="formRadio'.$x__type.'" value="'.$x__type.'" '.( $counter_options==1 ? ' checked ' : '' ).'>
                    <label class="form-check-label" for="formRadio'.$x__type.'">' . $m['m__title'] . '</label>
                </div>';
                    }

                    //Details
                    echo '<div class="headline top-margin"><span class="icon-block">'.$e___14393[14395]['m__cover'].'</span>'.$e___14393[14395]['m__title'].'</div>';
                    echo '<div class="padded"><textarea class="form-control text-edit border" id="sugg_note" data-lpignore="true" placeholder="More details here..."></textarea></div>';

                    ?>


                </div>
                <div class="modal-footer">
                    <button type="button" onclick="x_suggestion()" class="btn btn-default">SUBMIT</button>
                </div>
            </div>
        </div>
    </div>







    <!-- SHARE Modal -->
    <div class="modal fade" id="modal13024" tabindex="-1" role="dialog" aria-labelledby="modal13024Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title" id="modal13024Label"><?= $e___11035[13024]['m__cover'].' '.$e___11035[13024]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <?php
                    //URL
                    $current_link = current_link();
                    echo '<div class="headline"><span class="icon-block">'.$e___14393[14927]['m__cover'].'</span>'.$e___14393[14927]['m__title'].'</div>';
                    echo '<div class="padded"><input type="url" class="form-control border share_url" value="'.$current_link.'"></div>';
                    ?>

                </div>
            </div>
        </div>
    </div>




    <!-- APP Modal -->
    <div class="modal fade indifferent" id="modal6287" tabindex="-1" role="dialog" aria-labelledby="modal6287Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title" id="modal14393Label"><?= $e___11035[6287]['m__cover'].' '.$e___11035[6287]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Loads dynamically -->
                    <i class="far fa-yin-yang fa-spin"></i> Loading...
                </div>
            </div>
        </div>
    </div>




    <!-- EDIT MESSAGE Modal -->
    <div class="modal fade" id="modal13571" tabindex="-1" role="dialog" aria-labelledby="modal13571Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title" id="modal13571Label"><?= $e___11035[13571]['m__cover'].' '.$e___11035[13571]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control text-edit border" id="x__message" name="x__message" data-lpignore="true" placeholder="<?= $e___13571[4372]['m__message'] ?>"></textarea>
                    <div id="x__message_preview" class="hideIfEmpty" style="width: 377px;"></div>
                    <div class="save_results margin-top-down-half hideIfEmpty"></div>
                    <input type="hidden" class="modal_x__id" value="0" />
                </div>
                <div class="modal-footer">
                    <table style="width: 100%;">
                        <tr>
                            <td width="100%">
                                <div id="x__type_preview" class="hideIfEmpty"></div>
                            </td>
                            <td>
                                <button type="button" onclick="x_message_save()" class="btn btn-default">SAVE</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>





    <!-- SET COVER Modal -->
    <div class="modal fade" id="modal14937" tabindex="-1" role="dialog" aria-labelledby="modal14937Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title" id="modal14393Label"><?= $e___11035[14937]['m__cover'].' '.$e___11035[14937]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body block_search_results">

                    <!-- IMAGE DROP STARTS -->
                    <div class="coverUploader">
                    <form class="box coverUpload" method="post" enctype="multipart/form-data">


                    <!-- COVER PREVIEW -->
                    <a name="preview_cover" style="height: 1px;">&nbsp;</a>
                    <div class="coin_cover demo_cover" style="width: 255px !important; margin:-21px auto 74px !important;">
                        <div class="cover-wrapper"><div class="black-background-obs cover-link" style=""><div class="cover-btn"></div></div></div>
                        <div class="cover-content"><div class="inner-content"><span><textarea placeholder="Title" id="coin__title" class="form-control css__title inline-block texttype__lg center" style="overflow: hidden;overflow-wrap: break-word;height: 42px;"></textarea></span></div></div>
                    </div>

                    <!-- Power Edit (Required Superpower) -->
                    <div class="<?= superpower_active(14003) ?>"><input type="text" id="coin__cover" value="" data-lpignore="true" placeholder="Emoji, Image URL or Icon Code" class="form-control border-dotted" style="margin-top: 5px;"></div>
                    <input type="hidden" id="coin__type" value="0" />
                    <input type="hidden" id="coin__id" value="0" />


                    <!-- CONTROLLER -->
                    <table style="width: 100%; margin-bottom: 34px;">
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
                            <td>
                                <!-- SAVE -->
                                <button type="button" onclick="coin__save()" class="btn btn-default">SAVE</button>
                            </td>
                        </tr>
                    </table>


                    <!-- IMAGE DROP ENDS -->
                    </div>
                    </form>


                    <div id="upload_results" class="center"></div>
                    <div id="img_results_emojis" class="icons_small"></div>
                    <div class="doclear">&nbsp;</div>
                    <div id="icon_suggestions" class="icons_small"></div>
                    <div id="img_results_icons" class="icons_small"></div>
                    <div class="doclear">&nbsp;</div>
                    <div id="img_results_local" class="icons_large"></div>
                    <div id="img_results_tenor" class="icons_large"></div>
                    <div id="img_results_unsplash" class="icons_large"></div>
                    <div class="doclear">&nbsp;</div>

                </div>
            </div>
        </div>
    </div>





    <!-- GIF Modal -->
    <div class="modal fade" id="modal14073" tabindex="-1" role="dialog" aria-labelledby="modal14073Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title" id="modal14073Label"><?= $e___11035[14073]['m__cover'].' '.$e___11035[14073]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal_i__id" value="0" />
                    <input type="hidden" id="modal_x__type" value="0" />
                    <input type="text" class="form-control text-edit border css__title images_query" placeholder="Search GIFs..." onkeyup="images_search($('.images_query').val())" data-lpignore="true" />
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