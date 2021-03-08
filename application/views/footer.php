
<?php
$member_e = superpower_unlocked();
if($member_e && ( !isset($basic_header_footer) || !$basic_header_footer )){

    $e___11035 = $this->config->item('e___11035'); //NAVIGATION
    $e___14393 = $this->config->item('e___14393'); //SUGGEST
    $e___13571 = $this->config->item('e___13571'); //SOURCE EDITOR
    $e___14937 = $this->config->item('e___14937'); //SOURCE ICON

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
                    <?php
                    foreach($this->config->item('e___6225') as $e__id => $m) {
                        $hosted_domains = array_intersect($this->config->item('n___14870'), $m['m__profile']);
                        if(count($hosted_domains) && !in_array(get_domain_setting(0), $hosted_domains)){
                            continue;
                        }
                        echo '<div class="headline top-margin"><span class="icon-block">&nbsp;</span>'.$m['m__title'].'</div>';
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
                    <h5 class="modal-title css__title" id="modal14393Label"><?= $e___11035[14393]['m__cover'].' '.$e___11035[14393]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <?php

                    //Current URL:
                    echo '<div class="headline"><span class="icon-block">&nbsp;</span>'.$e___14393[14927]['m__title'].'</div>';
                    echo '<div class="current_url padded hideIfEmpty inline-block"></div>';



                    //Type
                    echo '<div class="headline top-margin"><span class="icon-block">&nbsp;</span>'.$e___14393[14394]['m__title'].'</div>';
                    $counter_options = 0;
                    foreach($this->config->item('e___14394') /* SUGGESTION TYPE */ as $x__type => $m){
                        $counter_options++;
                        echo '<div class="form-check">
                    <input class="form-check-input" type="radio" name="sugg_type" id="formRadio'.$x__type.'" value="'.$x__type.'" '.( $counter_options==1 ? ' checked ' : '' ).'>
                    <label class="form-check-label" for="formRadio'.$x__type.'">' . $m['m__title'] . '</label>
                </div>';
                    }

                    //Details
                    echo '<div class="headline top-margin"><span class="icon-block">&nbsp;</span>'.$e___14393[14395]['m__title'].'</div>';
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
                    echo '<div class="headline"><span class="icon-block">&nbsp;</span>'.$e___14393[14927]['m__title'].'</div>';
                    echo '<div class="padded"><a href="javascript:void();" onclick="copyTextToClipboard(\''.$current_link.'\');">'.$current_link.'&nbsp;&nbsp;<i class="fa fa-gif-wrap was_copied">COPY</i></a></div>';

                    //Add This
                    echo '<div class="addthis_inline_share_toolbox"></div>'; //Customize at www.addthis.com/dashboard
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
                    <?php
                    if(superpower_active(12699, true)){
                        echo '<div class="list-group">';
                        foreach($this->config->item('e___6287') as $e__id => $m) {
                            echo '<a href="/-'.$e__id.'" class="list-group-item no-side-padding">';
                            echo '<span class="icon-block">' . $m['m__cover'] . '</span>';
                            echo '<b class="css__title">'.$m['m__title'].'</b>';
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
                    <h5 class="modal-title css__title" id="modal14937Label"><?= $e___11035[14937]['m__cover'].' '.$e___11035[14937]['m__title'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- PREVIEW -->
                    <div class="row extra_info_box">
                        <div class="col-12">
                            <div class="coin_cover demo_cover" style="width: 255px !important; margin: -21px auto 50px !important;">
                                <div class="cover-wrapper"><div class="black-background cover-link" style=""><div class="cover-btn"></div></div></div>
                                <div class="cover-content"><div class="inner-content"><span><textarea placeholder="Title" id="coin__title" class="form-control css__title inline-block texttype__lg center" style="overflow: hidden;overflow-wrap: break-word;resize: none;height: 42px;"></textarea></span></div></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <?php
                            $tab_group = 14937;
                            $tab_content = '';
                            $tab_nav = '<ul class="nav nav-tabs nav-sm">';

                            foreach($this->config->item('e___'.$tab_group) as $x__type => $m){

                                $ui = '';
                                if(strlen($m['m__message']) > 0){
                                    $ui .= '<div style="padding-bottom: 13px;">'.$m['m__message'].'</div>';
                                }

                                if($x__type==14073) {

                                    //GIF
                                    //$default_active = $image_src && substr($image_src, -4)=='.gif';

                                    $ui .= '<input type="text" class="form-control text-edit border css__title gif_query" placeholder="Search GIFs..." onkeyup="gif_search(\'\')" data-lpignore="true" />';
                                    $ui .= '<div class="gif_results margin-top-down hideIfEmpty"></div>';

                                } elseif($x__type==14936){

                                    //UPLOAD IMAGE
                                    //$default_active = $image_src && substr($image_src, -4)=='.gif';
                                    $ui .= 'UPLOAD HERE';

                                } elseif($x__type==13577){

                                    //FONT AWESOME ICON
                                    //$default_active = substr($current_icon, 0, 3)=='<i ';
                                    $ui .= 'FONT AWESOME';

                                } elseif($x__type==14038){

                                    //EMOJI
                                    //$default_active = substr($current_icon, 0, 1)!='<' && strlen($current_icon)>0;


                                } elseif($x__type==14939){

                                    //REFERENCE
                                    //$default_active = !strlen($current_icon);


                                    //Find Current Reference:
                                    $ui .= 'HAS X Reference : Set this reference action button';

                                } else {

                                    //Not supported via here:
                                    continue;

                                }

                                $tab_nav .= '<li class="nav-item"><a href="javascript:void(0);" onclick="loadtab('.$tab_group.','.$x__type.')" class="nav-x tab-nav-'.$tab_group.' tab-head-'.$x__type.'" title="'.$m['m__title'].'" data-toggle="tooltip" data-placement="top">&nbsp;'.$m['m__cover'].'&nbsp;</a></li>'; //( $default_active ? ' active ' : '' )


                                $tab_content .= '<div class="tab-content tab-group-'.$tab_group.' tab-data-'.$x__type.' hidden">'; //( $default_active ? '' : ' hidden ' )
                                $tab_content .= $ui;
                                $tab_content .= '</div>';

                            }

                            $tab_nav .= '</ul>';

                            echo $tab_nav.$tab_content;

                            //JS $('#coin__cover').val( '<i class=&quot;fas fa-laugh&quot;></i>' );update_cover_main($('#coin__cover').val(), '.demo_cover');

                            ?>
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="padding: 0;">
                    <div class="form-group">
                        <div class="input-group border">
                            <input type="text" id="coin__cover" value="" data-lpignore="true" placeholder="Emoji, Image URL or Icon Code" class="form-control border-dotted" style="margin-top: 5px;">
                            <div style="padding: 10px; text-align: center;"><button type="button" onclick="coin__save()" class="btn btn-default">SAVE</button></div>

                            <input type="hidden" id="coin__type" value="0" />
                            <input type="hidden" id="coin__id" value="0" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>





    <!-- GIF Modal -->
    <div class="modal fade" id="modal14073" tabindex="-1" role="dialog" aria-labelledby="modal14073Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title css__title" id="modal14073Label"><?= $e___11035[14073]['m__cover'].' '.$e___11035[14073]['m__title'] ?></h5> <!-- &nbsp;&nbsp;<img class="giphy_logo" src="https://s3foundation.s3-us-west-2.amazonaws.com/5d8ebb9a080502d42a05e175265130d4.png" /> -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal_i__id" value="0" />
                    <input type="hidden" id="modal_x__type" value="0" />
                    <input type="text" class="form-control text-edit border css__title gif_query" placeholder="Search GIFs..." onkeyup="gif_search('')" data-lpignore="true" />
                    <div class="gif_results margin-top-down hideIfEmpty"></div>
                </div>
            </div>
        </div>
    </div>


    <?php

}

?>


</body>
</html>