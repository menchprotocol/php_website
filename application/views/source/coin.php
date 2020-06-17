
<?php

$sources__6206 = $this->config->item('sources__6206'); //MENCH SOURCE
$sources__4341 = $this->config->item('sources__4341'); //Link Table
$sources__2738 = $this->config->item('sources__2738'); //MENCH
$sources__6177 = $this->config->item('sources__6177'); //Source Status
$sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
$sources__11089 = $this->config->item('sources__11089'); //SOURCE LAYOUT
$sources__10957 = $this->config->item('sources__10957'); //SUPERPOWERS
$is_public = in_array($source['source__status'], $this->config->item('sources_id_7357'));
$is_active = in_array($source['source__status'], $this->config->item('sources_id_7358'));
$superpower_10967 = superpower_active(10967, true);
$superpower_any = ( $session_source ? count($this->session->userdata('session_superpowers_assigned')) : 0 );
$is_source = source_is_idea_source($source['source__id']);

?>


<style>
    /* For a cleaner UI hide the current focused source parent */
    .source_child_icon_<?= $source['source__id'] ?>{ display:none; }
</style>

<script>
    //Set global variables:
    var source_focus_filter = -1; //No filter, show all
    var source_focus_id = <?= $source['source__id'] ?>;
</script>

<script src="/application/views/source/coin.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container source-ui">

    <?php
    //SOURCE NAME
    echo '<div class="itemsource">'.view_input_text(6197, $source['source__title'], $source['source__id'], ($is_source && $is_active), 0, true, '<span class="source_ui_icon_'.$source['source__id'].'">'.view_source__icon($source['source__icon']).'</span>', extract_icon_color($source['source__icon'])).'</div>';

    ?>

    <div id="modifybox" class="fixed-box hidden" source-id="0" source-link-id="0" style="padding: 5px;">

        <h5 class="badge badge-h edit-header"><i class="fas fa-pen-square"></i> Modify</h5>
        <div style="text-align:right; font-size: 22px; margin:-32px 3px -20px 0;">
            <a href="javascript:void(0);" onclick="modify_cancel()"><i class="fas fa-times"></i></a>
        </div>

        <div class="grey-box">
            <div class="row">
                <div class="col-md-6">
                    <div class="inline-box">

                        <!-- Player Status -->
                        <span class="mini-header"><?= $sources__6206[6177]['m_icon'].' '.$sources__6206[6177]['m_name'] ?></span>
                        <select class="form-control border" id="source__status">
                            <?php
                            foreach($this->config->item('sources__6177') /* Source Status */ as $read__type => $m){
                                echo '<option value="' . $read__type . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <div class="notify_source_delete hidden">

                            <input type="hidden" id="source_link_count" value="0" />
                            <div class="alert alert-danger"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>Saving will delete this source and UNLINK ALL <span class="source_delete_stats" style="display:inline-block; padding: 0;"></span> links</div>

                            <span class="mini-header"><span class="tr_idea_link_title"></span> Merge Source Into:</span>
                            <input style="padding-left:3px;" type="text" class="form-control algolia_search border source_text_search" id="source_merge" value="" placeholder="Search source to merge..." />

                        </div>



                        <!-- Player Name -->
                        <span class="mini-header" style="margin-top:20px;"><?= $sources__6206[6197]['m_icon'].' '.$sources__6206[6197]['m_name'] ?> [<span style="margin:0 0 10px 0;"><span id="charEnNum">0</span>/<?= config_var(6197) ?></span>]</span>
                        <span class="white-wrapper">
                                <textarea class="form-control text-edit border montserrat doupper" id="source__title"
                                          onkeyup="source__title_word_count()" data-lpignore="true"
                                          style="height:66px; min-height:66px;">
                                </textarea>
                            </span>



                        <!-- Player Icon -->
                        <span class="mini-header"><?= $sources__6206[6198]['m_icon'].' '.$sources__6206[6198]['m_name'] ?>

                                <a href="javascript:void(0);" style="margin-left: 5px;" onclick="$('#source__icon').val($('#source__icon').val() + '<i class=&quot;fas fa-&quot;></i>' )" data-toggle="tooltip" title="Insert blank Font-Awesome HTML code" data-placement="top"><i class="far fa-edit"></i><b>FA</b></a>

                                <a href="https://fontawesome.com/icons" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Visit Font-Awesome website for a full list of icons and their HTML code" data-placement="top"><i class="fas fa-external-link"></i></a>

                            </span>
                        <div class="form-group label-floating is-empty"
                             style="margin:1px 0 10px;">
                            <div class="input-group border">
                                <input type="text" id="source__icon" value=""
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


                            <span class="mini-header"><?= $sources__4341[6186]['m_icon'].' '.$sources__4341[6186]['m_name'] ?></span>
                            <select class="form-control border" id="read__status">
                                <?php
                                foreach($this->config->item('sources__6186') /* Read Status */ as $read__type => $m){
                                    echo '<option value="' . $read__type . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                                }
                                ?>
                            </select>

                            <div class="notify_unlink_source hidden">
                                <div class="alert alert-warning"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>Saving will unlink source</div>
                            </div>




                            <form class="drag-box" method="post" enctype="multipart/form-data">
                                <span class="mini-header" style="margin-top: 20px;"><?= $sources__4341[4372]['m_icon'].' '.$sources__4341[4372]['m_name'] ?></span>
                                <span class="white-wrapper">
                                    <textarea class="form-control text-edit border" id="read__message"
                                              data-lpignore="true"
                                              placeholder="Write, Drop a File or Paste URL"
                                              style="height:126px; min-height:126px;">
                                    </textarea>
                                </span>

                                <span><input class="inputfile" type="file" name="file" id="enFile" /><label class="" for="enFile" data-toggle="tooltip" title="Upload files up to <?= config_var(11063) ?> MB" data-placement="top"><i class="fal fa-cloud-upload"></i> Upload</label></span>
                            </form>


                            <span class="mini-header"><?= $sources__4341[4593]['m_icon'].' '.$sources__4341[4593]['m_name'] ?></span>
                            <span id="read__type_preview"></span>
                            <p id="source_link_preview" class="hideIfEmpty"></p>



                        </div>

                    </div>

                </div>

            </div>

            <table>
                <tr>
                    <td class="save-td"><a href="javascript:source_update();" class="btn btn-source btn-save">Save</a></td>
                    <td class="save-result-td"><span class="save_source_changes"></span></td>
                </tr>
            </table>

        </div>

    </div>

    <?php



    //FOR EDITING ONLY:
    echo '<div class="hidden">'.view_source($source).'</div>';



    //NAME & STATUS
    echo '<div class="doclear">&nbsp;</div>';
    echo '<div class="pull-right inline-block" style="margin-bottom: -28px;">';

    //REFERENCES
    if(superpower_active(12701, true)){
        echo '<div class="inline-block '.superpower_active(12701).'">'.join('',source_count_connections($source['source__id'])).'</div>';
    }

    //SOURCE DRAFTING?
    echo '<span class="icon-block source__status_' . $source['source__id'] . ( $is_public ? ' hidden ' : '' ).'"><span data-toggle="tooltip" data-placement="bottom" title="'.$sources__6177[$source['source__status']]['m_name'].': '.$sources__6177[$source['source__status']]['m_desc'].'">' . $sources__6177[$source['source__status']]['m_icon'] . '</span></span>';

    //Modify
    echo '<a href="javascript:void(0);" onclick="source_modify_load(' . $source['source__id'] . ',0)" class="icon-block grey '.superpower_active(10967).'" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$sources__11035[12275]['m_name'].'">'.$sources__11035[12275]['m_icon'].'</a>';


    //ADMIN MENU
    if(superpower_assigned(12703)){
        $sources__4527 = $this->config->item('sources__4527'); //Platform Memory
        echo '<ul class="nav nav-pills nav-sm" style="display: inline-block; border: 0; margin: 0;">';
        echo view_caret(12887, $sources__4527[12887], $source['source__id']);
        echo '</ul>';
    }


    echo '</div>';
    echo '<div class="doclear">&nbsp;</div>';





    //Print Play Layout
    foreach($sources__11089 as $read__type => $m){

        //Don't show empty tabs:
        $superpower_actives = array_intersect($this->config->item('sources_id_10957'), $m['m_parents']);
        $has_superpower = ( !count($superpower_actives) || superpower_active(end($superpower_actives), true) );
        $this_tab = null;
        $counter = 0;
        $auto_expand_tab = in_array($read__type, $this->config->item('sources_id_12571'));


        //SOURCE
        if($read__type==6225){

            //Account Setting
            if(!$session_source || $session_source['source__id']!=$source['source__id']){
                continue;
            }

            $this_tab .= '<div class="accordion" id="MyAccountAccordion" style="margin-bottom:34px;">';

            //Display account fields ordered with their SOURCE LINKS:
            foreach($this->config->item('sources__6225') as $acc_source__id => $acc_detail) {

                //Do they have any assigned? Skip this section if not:
                if($acc_source__id == 10957 /* Superpowers */ && !$superpower_any){
                    continue;
                }

                //Print header:
                $this_tab .= '<div class="card">
<div class="card-header" id="heading' . $acc_source__id . '">
<button class="btn btn-block" type="button" data-toggle="collapse" data-target="#openEn' . $acc_source__id . '" aria-expanded="false" aria-controls="openEn' . $acc_source__id . '">
  <span class="icon-block">' . $acc_detail['m_icon'] . '</span><b class="montserrat doupper ' . extract_icon_color($acc_detail['m_icon']) . '">' . $acc_detail['m_name'] . '</b><span class="pull-right icon-block"><i class="fas fa-chevron-down"></i></span>
</button>
</div>

<div class="doclear">&nbsp;</div>

<div id="openEn' . $acc_source__id . '" class="collapse" aria-labelledby="heading' . $acc_source__id . '" data-parent="#MyAccountAccordion">
<div class="card-body">';


                //Show description if any:
                $this_tab .= (strlen($acc_detail['m_desc']) > 0 ? '<p>' . $acc_detail['m_desc'] . '</p>' : '');


                //Print account fields that are either Single Selectable or Multi Selectable:
                $is_multi_selectable = in_array(6122, $acc_detail['m_parents']);
                $is_single_selectable = in_array(6204, $acc_detail['m_parents']);

                if ($acc_source__id == 12289) {

                    $source__icon_parts = explode(' ',one_two_explode('class="', '"', $session_source['source__icon']));

                    $this_tab .= '<div class="'.superpower_active(10939).'"><div class="doclear">&nbsp;</div><div class="btn-group avatar-type-group pull-right" role="group" style="margin:0 0 10px 0;">
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'far\')" class="btn btn-far '.( $source__icon_parts[0]=='far' ? ' active ' : '' ).'"><i class="far fa-paw source"></i></a>
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'fad\')" class="btn btn-fad '.( $source__icon_parts[0]=='fad' ? ' active ' : '' ).'"><i class="fad fa-paw source"></i></a>
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'fas\')" class="btn btn-fas '.( $source__icon_parts[0]=='fas' ? ' active ' : '' ).'"><i class="fas fa-paw source"></i></a>
                </div><div class="doclear">&nbsp;</div></div>';


                    //List avatars:
                    foreach($this->config->item('sources__12279') as $read__type3 => $m3) {

                        $avatar_icon_parts = explode(' ',one_two_explode('class="', '"', $m3['m_icon']));
                        $avatar_type_match = ($source__icon_parts[0] == $avatar_icon_parts[0]);
                        $superpower_actives3 = array_intersect($this->config->item('sources_id_10957'), $m3['m_parents']);

                        $this_tab .= '<span class="'.( count($superpower_actives3) ? superpower_active(end($superpower_actives3)) : '' ).'">';
                        $this_tab .= '<a href="javascript:void(0);" onclick="account_update_avatar_icon(\'' . $avatar_icon_parts[0] . '\', \'' . $avatar_icon_parts[1] . '\')" icon-css="' . $avatar_icon_parts[1] . '" class="list-group-item itemsource avatar-item item-square avatar-type-'.$avatar_icon_parts[0].' avatar-name-'.$avatar_icon_parts[1].' ' .( $avatar_type_match ? '' : ' hidden ' ). ( $avatar_type_match && $source__icon_parts[1] == $avatar_icon_parts[1] ? ' active ' : '') . '"><div class="avatar-icon">' . $m3['m_icon'] . '</div></a>';
                        $this_tab .= '</span>';

                    }

                } elseif ($acc_source__id == 10957 /* Superpowers */) {

                    if($superpower_any >= 2){
                        //Mass Toggle Option:
                        $this_tab .= '<div class="btn-group pull-right" role="group" style="margin:0 0 10px 0;">
                  <a href="javascript:void(0)" onclick="account_toggle_all(1)" class="btn btn-far"><i class="fas fa-toggle-on"></i></a>
                  <a href="javascript:void(0)" onclick="account_toggle_all(0)" class="btn btn-fad"><i class="fas fa-toggle-off"></i></a>
                </div><div class="doclear">&nbsp;</div>';
                    }


                    //List avatars:
                    $this_tab .= '<div class="list-group">';
                    foreach($sources__10957 as $superpower_source__id => $m3){

                        //What is the superpower requirement?
                        if(!superpower_assigned($superpower_source__id)){
                            continue;
                        }

                        $extract_icon_color = extract_icon_color($m3['m_icon']);
                        $this_tab .= '<a class="list-group-item itemsetting btn-superpower superpower-frame-'.$superpower_source__id.' '.( in_array($superpower_source__id, $this->session->userdata('session_superpowers_activated')) ? ' active ' : '' ).'" en-id="'.$superpower_source__id.'" href="javascript:void();" onclick="account_toggle_superpower('.$superpower_source__id.')"><span class="icon-block '.$extract_icon_color.'" title="Source @'.$superpower_source__id.'">'.$m3['m_icon'].'</span><b class="montserrat '.$extract_icon_color.'">'.$m3['m_name'].'</b> '.$m3['m_desc'].'</a>';

                    }
                    $this_tab .= '</div>';

                } elseif ($acc_source__id == 3288 /* Email */) {

                    $user_emails = $this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                        'read__down' => $session_source['source__id'],
                        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                        'read__up' => 3288, //Mench Email
                    ));

                    $this_tab .= '<span class="white-wrapper"><input type="email" id="source_email" class="form-control border dotransparent" value="' . (count($user_emails) > 0 ? $user_emails[0]['read__message'] : '') . '" placeholder="you@gmail.com" /></span>
                <a href="javascript:void(0)" onclick="account_update_email()" class="btn btn-source">Save</a>
                <span class="saving-account save_email"></span>';

                } elseif ($acc_source__id == 3286 /* Password */) {

                    $this_tab .= '<span class="white-wrapper"><input type="password" id="input_password" class="form-control border dotransparent" data-lpignore="true" autocomplete="new-password" placeholder="New Password..." /></span>
                <a href="javascript:void(0)" onclick="account_update_password()" class="btn btn-source">Save</a>
                <span class="saving-account save_password"></span>';

                } elseif ($is_multi_selectable || $is_single_selectable) {

                    $this_tab .= view_radio_sources($acc_source__id, $session_source['source__id'], ($is_multi_selectable ? 1 : 0));

                }

                //Print footer:
                $this_tab .= '<div class="doclear">&nbsp;</div>';
                $this_tab .= '</div></div></div>';

            }

            $this_tab .= '</div>'; //End of accordion

        } elseif($read__type==11030){

            //SOURCE PROFILE
            //FETCH ALL PARENTS
            $source__profiles = $this->READ_model->fetch(array(
                'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
                'read__down' => $source['source__id'],
            ), array('read__up'), 0, 0, array('source__weight' => 'DESC'));

            $counter = count($source__profiles);
            if(!$counter && !$superpower_10967){
                continue;
            }

            $this_tab .= '<div id="list-parent" class="list-group ">';
            foreach($source__profiles as $source_profile) {
                $this_tab .= view_source($source_profile,true, null, true, $is_source);
            }

            //Input to add new parents:
            $this_tab .= '<div id="new-parent" class="list-group-item list-adder itemsource no-side-padding '.superpower_active(10967).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$sources__2738[4536]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control source form-control-thick montserrat doupper algolia_search dotransparent add-input"
                           maxlength="' . config_var(6197) . '"
                           placeholder="NEW SOURCE">
                </div><div class="algolia_pad_search hidden pad_expand"></div></div>';

            $this_tab .= '</div>';

        } elseif($read__type==11029){

            //SOURCE PORTFOLIO
            $source__portfolio_count = $this->READ_model->fetch(array(
                'read__up' => $source['source__id'],
                'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
            ), array('read__down'), 0, 0, array(), 'COUNT(source__id) as totals');
            $counter = $source__portfolio_count[0]['totals'];
            $source__portfolios = array(); //Fetch some


            if(!$counter && !$superpower_10967){
                continue;
            }

            if($counter){

                //Determine how to order:
                if($counter > config_var(11064)){
                    $order_columns = array('source__weight' => 'DESC');
                } else {
                    $order_columns = array('read__sort' => 'ASC', 'source__title' => 'ASC');
                }

                //Fetch Portfolios
                $source__portfolios = $this->READ_model->fetch(array(
                    'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                    'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                    'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
                    'read__up' => $source['source__id'],
                ), array('read__down'), config_var(11064), 0, $order_columns);

            }


            //SOURCE MASS EDITOR
            if($superpower_10967){

                //Mass Editor:
                $dropdown_options = '';
                $input_options = '';
                $editor_counter = 0;

                foreach($this->config->item('sources__4997') as $action_source__id => $source_list_action) {


                    $editor_counter++;
                    $dropdown_options .= '<option value="' . $action_source__id . '">' .$source_list_action['m_name'] . '</option>';
                    $is_upper = ( in_array($action_source__id, $this->config->item('sources_id_12577') /* SOURCE UPDATER UPPERCASE */) ? ' montserrat doupper ' : false );


                    //Start with the input wrapper:
                    $input_options .= '<span id="mass_id_'.$action_source__id.'" title="'.$source_list_action['m_desc'].'" class="inline-block '. ( $editor_counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';




                    if(in_array($action_source__id, array(5000, 5001, 10625))){

                        //String Find and Replace:

                        //Find:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_source__id.'" placeholder="Search" class="form-control border '.$is_upper.'">';

                        //Replace:
                        $input_options .= '<input type="text" name="mass_value2_'.$action_source__id.'" placeholder="Replace" class="form-control border '.$is_upper.'">';


                    } elseif(in_array($action_source__id, array(5981, 12928, 12930, 5982))){

                        //Player search box:

                        //String command:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_source__id.'"  placeholder="Search sources..." class="form-control algolia_search source_text_search border '.$is_upper.'">';

                        //We don't need the second value field here:
                        $input_options .= '<input type="hidden" name="mass_value2_'.$action_source__id.'" value="" />';


                    } elseif($action_source__id == 11956){

                        //IF HAS THIS
                        $input_options .= '<input type="text" name="mass_value1_'.$action_source__id.'"  placeholder="IF THIS SOURCE..." class="form-control algolia_search source_text_search border '.$is_upper.'">';

                        //ADD THIS
                        $input_options .= '<input type="text" name="mass_value2_'.$action_source__id.'"  placeholder="ADD THIS SOURCE..." class="form-control algolia_search source_text_search border '.$is_upper.'">';


                    } elseif($action_source__id == 5003){

                        //Player Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_source__id.'" class="form-control border">';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($this->config->item('sources__6177') /* Source Status */ as $read__type3 => $m3){
                            $input_options .= '<option value="'.$read__type3.'">Update All '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_source__id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($this->config->item('sources__6177') /* Source Status */ as $read__type3 => $m3){
                            $input_options .= '<option value="'.$read__type3.'">Set to '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';


                    } elseif($action_source__id == 5865){

                        //Read Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_source__id.'" class="form-control border">';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($this->config->item('sources__6186') /* Read Status */ as $read__type3 => $m3){
                            $input_options .= '<option value="'.$read__type3.'">Update All '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_source__id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($this->config->item('sources__6186') /* Read Status */ as $read__type3 => $m3){
                            $input_options .= '<option value="'.$read__type3.'">Set to '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';


                    } else {

                        //String command:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_source__id.'"  placeholder="String..." class="form-control border '.$is_upper.'">';

                        //We don't need the second value field here:
                        $input_options .= '<input type="hidden" name="mass_value2_'.$action_source__id.'" value="" />';

                    }

                    $input_options .= '</span>';

                }

                $this_tab .= '<div class="pull-right grey" style="margin:-25px 5px 0 0;">'.( superpower_active(10967, true) && sources_currently_sorted($source['source__id']) ? '<span class="sort_reset hidden icon-block" title="'.$sources__11035[13007]['m_name'].'" data-toggle="tooltip" data-placement="top"><a href="javascript:void(0);" onclick="source_sort_reset()">'.$sources__11035[13007]['m_icon'].'</a></span>' : '').'<a href="javascript:void(0);" onclick="$(\'.source_editor\').toggleClass(\'hidden\');" title="'.$sources__11035[4997]['m_name'].'" data-toggle="tooltip" data-placement="top">'.$sources__11035[4997]['m_icon'].'</a></div>';
                $this_tab .= '<div class="doclear">&nbsp;</div>';
                $this_tab .= '<div class="source_editor hidden">';
                $this_tab .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important; margin-left: 33px;">';
                $this_tab .= '<div class="inline-box">';

                //Drop Down
                $this_tab .= '<select class="form-control border" name="mass_action_source__id" id="set_mass_action">';
                $this_tab .= $dropdown_options;
                $this_tab .= '</select>';

                $this_tab .= $input_options;

                $this_tab .= '<div><input type="submit" value="APPLY" class="btn btn-source inline-block"></div>';

                $this_tab .= '</div>';
                $this_tab .= '</form>';

                //Also add invisible child IDs for quick copy/pasting:
                $this_tab .= '<div style="color:transparent;" class="hideIfEmpty">';
                foreach($source__portfolios as $source_portfolio) {
                    $this_tab .= $source_portfolio['source__id'].',';
                }
                $this_tab .= '</div>';

                $this_tab .= '</div>';







                //Source Status Filters:
                if(superpower_active(12701, true)){

                    $source_count = $this->SOURCE_model->child_count($source['source__id'], $this->config->item('sources_id_7358') /* ACTIVE */);
                    $child_source_filters = $this->READ_model->fetch(array(
                        'read__up' => $source['source__id'],
                        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                        'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                        'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
                    ), array('read__down'), 0, 0, array('source__status' => 'ASC'), 'COUNT(source__id) as totals, source__status', 'source__status');

                    //Only show filtering UI if we find child sources with different Status (Otherwise no need to filter):
                    if (count($child_source_filters) > 0 && $child_source_filters[0]['totals'] < $source_count) {

                        //Load status definitions:
                        $sources__6177 = $this->config->item('sources__6177'); //Source Status

                        //Add 2nd Navigation to UI
                        $this_tab .= '<div class="nav nav-pills nav-sm">';

                        //Show fixed All button:
                        $this_tab .= '<li class="nav-item"><a href="#" onclick="source_filter_status(-1)" class="nav-link en-status-filter active en-status--1" data-toggle="tooltip" data-placement="top" title="View all sources"><i class="fas fa-asterisk source"></i><span class="source">&nbsp;' . $source_count . '</span><span class="show-max source">&nbsp;TOTAL</span></a></li>';

                        //Show each specific filter based on DB counts:
                        foreach($child_source_filters as $c_c) {
                            $st = $sources__6177[$c_c['source__status']];
                            $extract_icon_color = extract_icon_color($st['m_icon']);
                            $this_tab .= '<li class="nav-item"><a href="#status-' . $c_c['source__status'] . '" onclick="source_filter_status(' . $c_c['source__status'] . ')" class="nav-link en-status-filter en-status-' . $c_c['source__status'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['m_desc'] . '">' . $st['m_icon'] . '<span class="' . $extract_icon_color . '">&nbsp;' . $c_c['totals'] . '</span><span class="show-max '.$extract_icon_color.'">&nbsp;' . $st['m_name'] . '</span></a></li>';
                        }

                        $this_tab .= '</div>';

                    }
                }
            }

            $this_tab .= '<div id="source__portfolio" class="list-group">';

            foreach($source__portfolios as $source_portfolio) {
                $this_tab .= view_source($source_portfolio,false, null, true, $is_source);
            }
            if ($counter > count($source__portfolios)) {
                $this_tab .= view_source_load_more(1, config_var(11064), $counter);
            }

            //Input to add new child:
            $this_tab .= '<div id="new_portfolio" current-count="'.$counter.'" class="list-group-item list-adder itemsource no-side-padding '.superpower_active(10967).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$sources__2738[4536]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control source form-control-thick montserrat doupper algolia_search dotransparent add-input"
                           maxlength="' . config_var(6197) . '"
                           placeholder="NEW SOURCE">
                </div><div class="algolia_pad_search hidden pad_expand"></div></div>';

            $this_tab .= '</div>';

        } elseif(in_array($read__type, $this->config->item('sources_id_12467'))){

            $counter = read_coins_source($read__type, $source['source__id']);
            if($has_superpower){
                $this_tab = read_coins_source($read__type, $source['source__id'], 1);
            }

        } elseif($read__type==13046){

            //Fetch Ideas First:
            $counter = 0; //Unless we find some:
            $idea__ids = array();
            foreach($this->READ_model->fetch(array(
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
                '(read__up = '.$source['source__id'].' OR read__down = '.$source['source__id'].')' => null,
            ), array(), config_var(11064), 0, array(), 'read__right') as $item) {
                array_push($idea__ids, $item['read__right']);
            }

            //Also Show Related Sources:
            if(count($idea__ids) > 0){

                $already_included = array($source['source__id']);
                foreach ($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
                    'read__right IN (' . join(',', $idea__ids) . ')' => null,
                    '(read__up > 0 OR read__down > 0)' => null, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
                ), array(), 0) as $fetched_source){

                    foreach(array('read__up','read__down') as $source_ref_field) {
                        if($fetched_source[$source_ref_field] > 0){

                            if(in_array($fetched_source[$source_ref_field], $already_included)){
                                continue;
                            }

                            $counter++;
                            array_push($already_included, $fetched_source[$source_ref_field]);

                            $ref_sources = $this->SOURCE_model->fetch(array(
                                'source__id' => $fetched_source[$source_ref_field],
                            ));

                            $this_tab .= view_source($ref_sources[0]);

                        }
                    }
                }

                if($counter > 0){
                    //Wrap list:
                    $this_tab = '<div class="list-group">' . $this_tab . '</div>';
                }

            }

        } elseif(in_array($read__type, $this->config->item('sources_id_4485'))){

            //IDEA NOTES
            $idea_notes_filters = array(
                'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
                'read__type' => $read__type,
                'read__up' => $source['source__id'],
            );

            //COUNT ONLY
            $item_counters = $this->READ_model->fetch($idea_notes_filters, array('read__right'), 0, 0, array(), 'COUNT(idea__id) as totals');
            $counter = $item_counters[0]['totals'];

            //SHOW LASTEST 100
            if($has_superpower){
                if($counter>0){

                    $idea_notes_query = $this->READ_model->fetch($idea_notes_filters, array('read__right'), config_var(11064), 0, array('idea__weight' => 'DESC'));
                    $this_tab .= '<div class="list-group">';
                    foreach($idea_notes_query as $count => $idea_notes) {
                        $this_tab .= view_idea($idea_notes, 0, false, false, $idea_notes['read__message'], null, false);
                    }
                    $this_tab .= '</div>';

                } else {

                    $this_tab .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> No '.$sources__11089[$read__type]['m_name'].' yet</div>';

                }
            }

        } elseif($read__type == 12969 /* Reads */){

            $idea_reads_filters = array(
                'read__player' => $source['source__id'],
                'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            );
            $player_reads = $this->READ_model->fetch($idea_reads_filters, array('read__left'), 1, 0, array(), 'COUNT(read__id) as totals');
            $counter = $player_reads[0]['totals'];

            if($has_superpower){
                if($counter > 0){
                    $idea_reads_query = $this->READ_model->fetch($idea_reads_filters, array('read__left'), config_var(11064), 0, array('read__sort' => 'ASC'));
                    $this_tab .= '<div class="list-group">';
                    foreach($idea_reads_query as $count => $idea_notes) {
                        $this_tab .= view_idea($idea_notes);
                    }
                    $this_tab .= '</div>';
                } else {
                    $this_tab .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> No '.$sources__11089[$read__type]['m_name'].' yet</div>';
                }
            }

        }

        if(!$counter && (!in_array($read__type, $this->config->item('sources_id_12574')) || !$session_source)){
            continue;
        }


        //HEADER
        echo '<div class="'.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'">';

        echo '<div class="read-topic"><a href="javascript:void(0);" onclick="$(\'.contentTab'.$read__type.'\').toggleClass(\'hidden\')" title="'.number_format($counter, 0).' '.$m['m_name'].'"><span class="icon-block"><i class="far fa-plus-circle contentTab'.$read__type.( $auto_expand_tab ? ' hidden ' : '' ).'"></i><i class="far fa-minus-circle contentTab'.$read__type.( $auto_expand_tab ? '' : ' hidden ' ).'"></i></span>'.( $counter>0 ? '<span class="'.( in_array($read__type, $this->config->item('sources_id_13004')) ? superpower_active(10967) : '' ).'" title="'.number_format($counter, 0).'"><span class="counter_'.$read__type.'">'.view_number($counter).'</span>&nbsp;</span>' : '' ).$m['m_name'].'</a></div>';

        //BODY
        echo '<div class="contentTab'.$read__type.( $auto_expand_tab ? '' : ' hidden ' ).'" style="padding-bottom:34px;">';
        if($this_tab) {
            echo $this_tab;
        } elseif(!$has_superpower){
            $superpower = $sources__10957[end($superpower_actives)];
            echo '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> Missing <span class="'.extract_icon_color($superpower['m_icon']).'">'. $superpower['m_icon'] . '&nbsp;' . $superpower['m_name'] . '</span></div>';
        }
        echo '</div>';
        echo '</div>';

    }

    ?>

</div>