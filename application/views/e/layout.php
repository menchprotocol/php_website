<?php
$e___6206 = $this->config->item('e___6206'); //MENCH SOURCE
$e___4341 = $this->config->item('e___4341'); //Transaction Table
$e___6177 = $this->config->item('e___6177'); //Source Status
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
$miner_is_e = miner_is_e($e['e__id']);

?>

<script>
    //Set global variables:
    var e_focus_filter = -1; //No filter, show all
    var e_focus_id = <?= $e['e__id'] ?>;
</script>

<script src="/application/views/e/layout.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container e-ui">

    <?php

    //SOURCE DRAFTING?
    echo '<div class="'.( in_array($e['e__status'], $this->config->item('n___7357')) ? ' hidden ' : '' ).'"><span class="icon-block e__status_' . $e['e__id'].'"><span data-toggle="tooltip" data-placement="bottom" title="'.$e___6177[$e['e__status']]['m_name'].': '.$e___6177[$e['e__status']]['m_desc'].'">' . $e___6177[$e['e__status']]['m_icon'] . '</span></span></div>';

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

                                <a href="https://fontawesome.com/icons" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Visit Font-Awesome website for a full list of icons and their HTML code" data-placement="top"><i class="fas fa-external-link"></i></a>

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

    //FOR EDITING ONLY, OTHERSWISE HIDDEN:
    echo '<div class="hidden">'.view_e($e).'</div>';


    //SOURCE MODIFY BUTTON
    echo '<div class="doclear">&nbsp;</div>';
    echo '<div class="pull-right inline-block" style="margin:8px 0 -40px 0;"><a href="javascript:void(0);" onclick="e_modify_load(' . $e['e__id'] . ',0)" class="icon-block grey '.superpower_active(13422).'" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$e___11035[12275]['m_name'].'">'.$e___11035[12275]['m_icon'].'</a></div>';
    echo '<div class="doclear">&nbsp;</div>';



    //Determine Focus Tab:
    $list_11029_count = $this->X_model->fetch(array(
        'x__up' => $e['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ), array('x__down'), 0, 0, array(), 'COUNT(e__id) as totals');
    $counter__11029 = $list_11029_count[0]['totals'];



    $counter__12273 = x_stats_count($x__type, $e['e__id']);



    $superpower_10939 = superpower_active(10939, true);
    $superpower_13422 = superpower_active(13422, true); //Advance Sourcing
    $superpower_any = ( $session_e ? count($this->session->userdata('session_superpowers_assigned')) : 0 );
    $e___12467 = $this->config->item('e___12467'); //MENCH
    $e___11035 = $this->config->item('e___11035'); //NAVIGATION

    $active_x__type = 11030;
    $tab_group = 11089;
    $tab_nav = '';
    $tab_content = '';
    foreach($this->config->item('e___'.$tab_group) as $x__type => $m) {

        $superpower_actives = array_intersect($this->config->item('n___10957'), $m['m_parents']);
        if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
            //Missing Superpower:
            continue;
        } elseif(in_array($x__type, $this->config->item('n___13424')) && $e['e__id']==$session_e['e__id']){
            //SOURCE LAYOUT HIDE IF SOURCE:
            continue;
        } elseif(in_array($x__type, $this->config->item('n___13425')) && $e['e__id']!=$session_e['e__id']){
            //SOURCE LAYOUT SHOW IF SOURCE:
            continue;
        }


        $counter = null;
        $focus_tab = null;

        //Is this a caret menu?
        if(in_array(11040 , $m['m_parents'])){

            $tab_nav .= view_caret($x__type, $m, $e['e__id']);
            continue;

        } elseif(in_array($x__type, $this->config->item('n___6194'))){

            //SOURCE REFERENCE:
            $e_count_6194 = e_count_6194($e['e__id'], $x__type);
            $counter = ( isset($e_count_6194[$x__type]) ? $e_count_6194[$x__type] : 0 );
            if(!$counter){
                continue;
            }
            $focus_tab = '<div><span class="icon-block">&nbsp;</span>Source referenced as '.$m['m_icon'].' '.$m['m_name'].' '.number_format($counter, 0).' times.</div>';

        } elseif(in_array($x__type, $this->config->item('n___12467'))){

            //MENCH COINS
            $counter = x_stats_count($x__type, $e['e__id']);
            if(!$counter){
                continue;
            }
            $focus_tab = x_stats_count($x__type, $e['e__id'], 1);

        } elseif($x__type==6225){

            //ACCOUNT SETTING
            $focus_tab = '<div class="accordion" id="MyAccountAccordion" style="margin-bottom:34px;">';

            //Display account fields ordered with their SOURCE LINKS:
            foreach($this->config->item('e___6225') as $acc_e__id => $acc_detail) {

                //Do they have any assigned? Skip this section if not:
                if($acc_e__id == 10957 /* Superpowers */ && !$superpower_any){
                    continue;
                }

                //Print header:
                $focus_tab .= '<div class="card">
<div class="card-header" id="heading' . $acc_e__id . '">
<button class="btn btn-block" type="button" data-toggle="collapse" data-target="#openEn' . $acc_e__id . '" aria-expanded="false" aria-controls="openEn' . $acc_e__id . '">
  <span class="icon-block">' . $acc_detail['m_icon'] . '</span><b class="montserrat doupper ' . extract_icon_color($acc_detail['m_icon']) . '">' . $acc_detail['m_name'] . '</b><span class="pull-right icon-block"><i class="fas fa-chevron-down"></i></span>
</button>
</div>

<div class="doclear">&nbsp;</div>

<div id="openEn' . $acc_e__id . '" class="collapse" aria-labelledby="heading' . $acc_e__id . '" data-parent="#MyAccountAccordion">
<div class="card-body">';


                //Show description if any:
                $focus_tab .= (strlen($acc_detail['m_desc']) > 0 ? '<p>' . $acc_detail['m_desc'] . '</p>' : '');


                //Print account fields that are either Single Selectable or Multi Selectable:
                $is_multi_selectable = in_array(6122, $acc_detail['m_parents']);
                $is_single_selectable = in_array(6204, $acc_detail['m_parents']);

                if ($acc_e__id == 12289) {

                    $e__icon_parts = explode(' ',one_two_explode('class="', '"', $session_e['e__icon']));

                    $focus_tab .= '<div class="'.superpower_active(10939).'"><div class="doclear">&nbsp;</div><div class="btn-group avatar-type-group pull-right" role="group" style="margin:0 0 10px 0;">';

                    foreach($CI->config->item('e___13533') as $e__id => $m) {

                    }

                    $focus_tab .= '<a href="javascript:void(0)" onclick="account_update_avatar_type(\'far\')" class="btn btn-far '.( $e__icon_parts[0]=='far' ? ' active ' : '' ).'"><i class="far fa-paw source"></i></a>
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'fad\')" class="btn btn-fad '.( $e__icon_parts[0]=='fad' ? ' active ' : '' ).'"><i class="fad fa-paw source"></i></a>
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'fas\')" class="btn btn-fas '.( $e__icon_parts[0]=='fas' ? ' active ' : '' ).'"><i class="fas fa-paw source"></i></a>';


                    $focus_tab .= '</div>';
                    $focus_tab .= '<div class="doclear">&nbsp;</div>';
                    $focus_tab .= '</div>';


                    //List avatars:
                    foreach($this->config->item('e___12279') as $x__type3 => $m3) {

                        $avatar_icon_parts = explode(' ',one_two_explode('class="', '"', $m3['m_icon']));
                        $avatar_type_match = ($e__icon_parts[0] == $avatar_icon_parts[0]);
                        $superpower_actives3 = array_intersect($this->config->item('n___10957'), $m3['m_parents']);

                        $focus_tab .= '<span class="'.( count($superpower_actives3) ? superpower_active(end($superpower_actives3)) : '' ).'">';
                        $focus_tab .= '<a href="javascript:void(0);" onclick="e_update_avatar(\'' . $avatar_icon_parts[0] . '\', \'' . $avatar_icon_parts[1] . '\')" icon-css="' . $avatar_icon_parts[1] . '" class="list-group-item itemsource avatar-item item-square avatar-type-'.$avatar_icon_parts[0].' avatar-name-'.$avatar_icon_parts[1].' ' .( $avatar_type_match ? '' : ' hidden ' ). ( $avatar_type_match && $e__icon_parts[1] == $avatar_icon_parts[1] ? ' active ' : '') . '"><div class="avatar-icon">' . $m3['m_icon'] . '</div></a>';
                        $focus_tab .= '</span>';

                    }

                } elseif ($acc_e__id == 10957 /* Superpowers */) {

                    if($superpower_any >= 2){
                        //Mass Toggle Option:
                        $focus_tab .= '<div class="btn-group pull-right" role="group" style="margin:0 0 10px 0;">
                  <a href="javascript:void(0)" onclick="account_toggle_all(1)" class="btn btn-far"><i class="fas fa-toggle-on"></i></a>
                  <a href="javascript:void(0)" onclick="account_toggle_all(0)" class="btn btn-fad"><i class="fas fa-toggle-off"></i></a>
                </div><div class="doclear">&nbsp;</div>';
                    }


                    //List avatars:
                    $focus_tab .= '<div class="list-group">';
                    foreach($this->config->item('e___10957') as $superpower_e__id => $m3){

                        //What is the superpower requirement?
                        if(!superpower_assigned($superpower_e__id)){
                            continue;
                        }

                        $extract_icon_color = extract_icon_color($m3['m_icon']);
                        $focus_tab .= '<a class="list-group-item itemsetting btn-superpower superpower-frame-'.$superpower_e__id.' '.( in_array($superpower_e__id, $this->session->userdata('session_superpowers_activated')) ? ' active ' : '' ).'" en-id="'.$superpower_e__id.'" href="javascript:void();" onclick="e_toggle_superpower('.$superpower_e__id.')"><span class="icon-block '.$extract_icon_color.'" title="Source @'.$superpower_e__id.'">'.$m3['m_icon'].'</span><b class="montserrat '.$extract_icon_color.'">'.$m3['m_name'].'</b> '.$m3['m_desc'].'</a>';

                    }
                    $focus_tab .= '</div>';

                } elseif ($acc_e__id == 3288 /* Email */) {

                    $miner_emails = $this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__down' => $session_e['e__id'],
                        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                        'x__up' => 3288, //Mench Email
                    ));

                    $focus_tab .= '<span class="white-wrapper"><input type="email" id="e_email" class="form-control border dotransparent" value="' . (count($miner_emails) > 0 ? $miner_emails[0]['x__message'] : '') . '" placeholder="you@gmail.com" /></span>
                <a href="javascript:void(0)" onclick="e_update_email()" class="btn btn-e">Save</a>
                <span class="saving-account save_email"></span>';

                } elseif ($acc_e__id == 3286 /* Password */) {

                    $focus_tab .= '<span class="white-wrapper"><input type="password" id="input_password" class="form-control border dotransparent" data-lpignore="true" autocomplete="new-password" placeholder="New Password..." /></span>
                <a href="javascript:void(0)" onclick="e_update_password()" class="btn btn-e">Save</a>
                <span class="saving-account save_password"></span>';

                } elseif ($is_multi_selectable || $is_single_selectable) {

                    $focus_tab .= view_radio_es($acc_e__id, $session_e['e__id'], ($is_multi_selectable ? 1 : 0));

                }

                //Print footer:
                $focus_tab .= '<div class="doclear">&nbsp;</div>';
                $focus_tab .= '</div></div></div>';

            }

            $focus_tab .= '</div>'; //End of accordion

        } elseif($x__type==11030){ //SOURCE PROFILE

            //FETCH ALL PARENTS
            $e__profiles = $this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                'x__down' => $e['e__id'],
            ), array('x__up'), 0, 0, array('e__weight' => 'DESC'));

            $counter = count($e__profiles);
            if(!$counter && !$superpower_13422){
                continue;
            }

            $focus_tab .= '<div id="list_11030" class="list-group ">';
            foreach($e__profiles as $e_profile) {
                $focus_tab .= view_e($e_profile,true, null, true, ($miner_is_e || ($session_e && ($session_e['e__id']==$e_profile['x__source']))));
            }

            //Input to add new parents:
            $focus_tab .= '<div id="new-parent" class="list-group-item list-adder itemsource no-side-padding '.superpower_active(13422).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$e___12467[12274]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control form-control-thick algolia_search dotransparent add-input"
                           maxlength="' . config_var(6197) . '"
                           placeholder="SOURCE TITLE/URL">
                </div><div class="algolia_pad_search hidden pad_expand"></div></div>';

            $focus_tab .= '</div>';

        } elseif($x__type==11029){

            //SOURCE PORTFOLIO
            $counter = $counter__11029;

            if($counter){

                //Determine how to order:
                if($counter > config_var(11064)){
                    $order_columns = array('e__weight' => 'DESC');
                } else {
                    $order_columns = array('x__sort' => 'ASC', 'e__title' => 'ASC');
                }

                //Fetch Portfolios
                $list_11029s = $this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                    'x__up' => $e['e__id'],
                ), array('x__down'), config_var(11064), 0, $order_columns);

            } else {

                //Maybe we allow them to be created:
                $list_11029s = array(); //Fetch some

            }


            //SOURCE MASS EDITOR
            if($superpower_13422){

                //Mass Editor:
                $dropdown_options = '';
                $input_options = '';
                $editor_counter = 0;

                foreach($this->config->item('e___4997') as $action_e__id => $e_list_action) {


                    $editor_counter++;
                    $dropdown_options .= '<option value="' . $action_e__id . '">' .$e_list_action['m_name'] . '</option>';
                    $is_upper = ( in_array($action_e__id, $this->config->item('n___12577') /* SOURCE UPDATER UPPERCASE */) ? ' montserrat doupper ' : false );


                    //Start with the input wrapper:
                    $input_options .= '<span id="mass_id_'.$action_e__id.'" title="'.$e_list_action['m_desc'].'" class="inline-block '. ( $editor_counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';




                    if(in_array($action_e__id, array(5000, 5001, 10625))){

                        //String Find and Replace:

                        //Find:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'" placeholder="Search" class="form-control border '.$is_upper.'">';

                        //Replace:
                        $input_options .= '<input type="text" name="mass_value2_'.$action_e__id.'" placeholder="Replace" class="form-control border '.$is_upper.'">';


                    } elseif(in_array($action_e__id, array(5981, 12928, 12930, 5982, 13441))){

                        //Miner search box:

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

                        //Miner Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_e__id.'" class="form-control border">';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($this->config->item('e___6177') /* Source Status */ as $x__type3 => $m3){
                            $input_options .= '<option value="'.$x__type3.'">Update All '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_e__id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($this->config->item('e___6177') /* Source Status */ as $x__type3 => $m3){
                            $input_options .= '<option value="'.$x__type3.'">Set to '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';


                    } elseif($action_e__id == 5865){

                        //Transaction Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_e__id.'" class="form-control border">';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($this->config->item('e___6186') /* Transaction Status */ as $x__type3 => $m3){
                            $input_options .= '<option value="'.$x__type3.'">Update All '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_e__id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($this->config->item('e___6186') /* Transaction Status */ as $x__type3 => $m3){
                            $input_options .= '<option value="'.$x__type3.'">Set to '.$m3['m_name'].'</option>';
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


                $focus_tab .= '<div class="pull-right grey" style="margin:-35px 34px 0 0;">'.( superpower_active(13422, true) && sources_currently_sorted($e['e__id']) ? '<span class="sort_reset hidden icon-block" title="'.$e___11035[13007]['m_name'].'" data-toggle="tooltip" data-placement="top"><a href="javascript:void(0);" onclick="e_sort_reset()">'.$e___11035[13007]['m_icon'].'</a></span>' : '').'<a href="javascript:void(0);" onclick="$(\'.e_editor\').toggleClass(\'hidden\');" title="'.$e___11035[4997]['m_name'].'" data-toggle="tooltip" data-placement="top">'.$e___11035[4997]['m_icon'].'</a></div>';



                $focus_tab .= '<div class="doclear">&nbsp;</div>';
                $focus_tab .= '<div class="e_editor hidden">';
                $focus_tab .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important; margin-left: 33px;">';
                $focus_tab .= '<div class="inline-box">';

                //Drop Down
                $focus_tab .= '<select class="form-control border" name="mass_action_e__id" id="set_mass_action">';
                $focus_tab .= $dropdown_options;
                $focus_tab .= '</select>';

                $focus_tab .= $input_options;

                $focus_tab .= '<div><input type="submit" value="APPLY" class="btn btn-e inline-block"></div>';

                $focus_tab .= '</div>';
                $focus_tab .= '</form>';

                //Also add invisible child IDs for quick copy/pasting:
                $focus_tab .= '<div style="color:transparent;" class="hideIfEmpty">';
                foreach($list_11029s as $e_portfolio) {
                    $focus_tab .= $e_portfolio['e__id'].',';
                }
                $focus_tab .= '</div>';

                $focus_tab .= '</div>';







                //Source Status Filters:
                if(superpower_active(12701, true)){

                    $e_count = $this->E_model->child_count($e['e__id'], $this->config->item('n___7358') /* ACTIVE */);
                    $child_e_filters = $this->X_model->fetch(array(
                        'x__up' => $e['e__id'],
                        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                    ), array('x__down'), 0, 0, array('e__status' => 'ASC'), 'COUNT(e__id) as totals, e__status', 'e__status');

                    //Only show filtering UI if we find child sources with different Status (Otherwise no need to filter):
                    if (count($child_e_filters) > 0 && $child_e_filters[0]['totals'] < $e_count) {

                        //Load status definitions:
                        $e___6177 = $this->config->item('e___6177'); //Source Status

                        //Add 2nd Navigation to UI
                        $focus_tab .= '<div class="nav nav-pills nav-sm">';

                        //Show fixed All button:
                        $focus_tab .= '<li class="nav-item"><a href="#" onclick="e_filter_status(-1)" class="nav-x en-status-filter active en-status--1" data-toggle="tooltip" data-placement="top" title="View all sources"><i class="fas fa-asterisk source"></i><span class="source">&nbsp;' . $e_count . '</span><span class="show-max source">&nbsp;TOTAL</span></a></li>';

                        //Show each specific filter based on DB counts:
                        foreach($child_e_filters as $c_c) {
                            $st = $e___6177[$c_c['e__status']];
                            $extract_icon_color = extract_icon_color($st['m_icon']);
                            $focus_tab .= '<li class="nav-item"><a href="#status-' . $c_c['e__status'] . '" onclick="e_filter_status(' . $c_c['e__status'] . ')" class="nav-x nav-link en-status-filter en-status-' . $c_c['e__status'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['m_desc'] . '">' . $st['m_icon'] . '<span class="' . $extract_icon_color . '">&nbsp;' . $c_c['totals'] . '</span><span class="show-max '.$extract_icon_color.'">&nbsp;' . $st['m_name'] . '</span></a></li>';
                        }

                        $focus_tab .= '</div>';

                    }
                }
            }

            $focus_tab .= '<div id="list_11029" class="list-group">';
            $common_prefix = i_calc_common_prefix($list_11029s, 'e__title');

            foreach($list_11029s as $e_portfolio) {
                $focus_tab .= view_e($e_portfolio,false, null, true, ($miner_is_e || ($session_e && ($session_e['e__id']==$e_portfolio['x__source']))), $common_prefix);
            }
            if ($counter > count($list_11029s)) {
                $focus_tab .= view_e_load_more(1, config_var(11064), $counter);
            }

            //Input to add new child:
            $focus_tab .= '<div id="new_portfolio" current-count="'.$counter.'" class="list-group-item list-adder itemsource no-side-padding '.superpower_active(13422).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$e___12467[12274]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control form-control-thick algolia_search dotransparent add-input"
                           maxlength="' . config_var(6197) . '"
                           placeholder="SOURCE TITLE/URL">
                </div><div class="algolia_pad_search hidden pad_expand"></div></div>';

            $focus_tab .= '</div>';

        } elseif(in_array($x__type, $this->config->item('n___4485'))){

            //IDEA NOTES
            $i_notes_filters = array(
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
                'x__type' => $x__type,
                'x__up' => $e['e__id'],
            );

            //COUNT ONLY
            $item_counters = $this->X_model->fetch($i_notes_filters, array('x__right'), 0, 0, array(), 'COUNT(i__id) as totals');
            $counter = $item_counters[0]['totals'];

            //SHOW LASTEST 100
            if($counter>0){

                $i_notes_query = $this->X_model->fetch($i_notes_filters, array('x__right'), config_var(11064), 0, array('i__weight' => 'DESC'));
                $focus_tab .= '<div class="list-group">';
                foreach($i_notes_query as $count => $i_notes) {
                    $focus_tab .= view_i($i_notes, 0, false, false, $i_notes['x__message'], null, false);
                }
                $focus_tab .= '</div>';

            } else {

                $focus_tab .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> No '.$m['m_name'].' yet</div>';

            }

        } elseif($x__type == 12969 /* MY DISCOVERIES */){

            $i_x_filters = array(
                'x__source' => $e['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___12969')) . ')' => null, //MY DISCOVERIES
                'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            );
            $miner_x = $this->X_model->fetch($i_x_filters, array('x__left'), 1, 0, array(), 'COUNT(x__id) as totals');
            $counter = $miner_x[0]['totals'];

            if($counter > 0){
                $i_x_query = $this->X_model->fetch($i_x_filters, array('x__left'), config_var(11064), 0, array('x__sort' => 'ASC'));
                $focus_tab .= '<div class="list-group">';
                foreach($i_x_query as $count => $i_notes) {
                    $focus_tab .= view_i($i_notes);
                }
                $focus_tab .= '</div>';
            } else {
                $focus_tab .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> No '.$m['m_name'].' yet</div>';
            }

        }

        if(!$counter && (!in_array($x__type, $this->config->item('n___12574')) || !$session_e)){
            //Hide since Zero without exception @12574:
            continue;
        }


        $default_active = ($x__type==$active_x__type);

        $tab_nav .= '<li class="nav-item '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a class="nav-x tab-nav-'.$tab_group.' tab-head-'.$x__type.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m['m_icon']).'" href="javascript:void(0);" onclick="loadtab('.$tab_group.','.$x__type.')" data-toggle="tooltip" data-placement="top" title="'.$m['m_name'].'">'.$m['m_icon'].( is_null($counter) ? '' : ' <span class="en-type-counter-'.$x__type.'">'.view_number($counter).'</span>' ).'<span class="show-max-active">&nbsp;'.$m['m_name'].'</span></a></li>';


        $tab_content .= '<div class="tab-content tab-group-'.$tab_group.' tab-data-'.$x__type.( $default_active ? '' : ' hidden ' ).'">';
        $tab_content .= $focus_tab;
        $tab_content .= '</div>';

    }

    if($tab_nav){

        echo '<ul class="nav nav-tabs nav-sm">';
        echo $tab_nav;
        echo '</ul>';

        //Show All Tab Content:
        echo $tab_content;

    }


    ?>

</div>