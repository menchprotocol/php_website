
<?php

$en_all_6206 = $this->config->item('en_all_6206'); //MENCH SOURCE
$en_all_4341 = $this->config->item('en_all_4341'); //Link Table
$en_all_2738 = $this->config->item('en_all_2738'); //MENCH
$en_all_6177 = $this->config->item('en_all_6177'); //Source Status
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION
$is_public = in_array($en['en_status_source_id'], $this->config->item('en_ids_7357'));
$is_active = in_array($en['en_status_source_id'], $this->config->item('en_ids_7358'));
$superpower_10967 = superpower_active(10967, true);
$is_source = en_is_source($en['en_id']);


?>


<style>
    /* For a cleaner UI hide the current focused source parent */
    .en_child_icon_<?= $en['en_id'] ?>{ display:none; }
</style>

<script>
    //Set global variables:
    var en_focus_filter = -1; //No filter, show all
    var en_focus_id = <?= $en['en_id'] ?>;
</script>

<script src="/application/views/source/source_coin.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <?php
    //SOURCE NAME
    echo '<div class="itemsource">'.echo_input_text(6197, $en['en_name'], $en['en_id'], ($is_source && $is_active), 0, true, '<span class="en_ui_icon_'.$en['en_id'].'">'.$en['en_icon'].'</span>', extract_icon_color($en['en_icon'])).'</div>';
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
                        <span class="mini-header"><?= $en_all_6206[6177]['m_icon'].' '.$en_all_6206[6177]['m_name'] ?></span>
                        <select class="form-control border" id="en_status_source_id">
                            <?php
                            foreach($this->config->item('en_all_6177') /* Source Status */ as $en_id => $m){
                                echo '<option value="' . $en_id . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                            }
                            ?>
                        </select>
                        <div class="notify_en_delete hidden">

                            <input type="hidden" id="en_link_count" value="0" />
                            <div class="alert alert-danger"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Saving will delete this source and UNLINK ALL <span class="source_delete_stats" style="display:inline-block; padding: 0;"></span> links</div>

                            <span class="mini-header"><span class="tr_in_link_title"></span> Merge Source Into:</span>
                            <input style="padding-left:3px;" type="text" class="form-control algolia_search border en_quick_search" id="en_merge" value="" placeholder="Search source to merge..." />

                        </div>



                        <!-- Player Name -->
                        <span class="mini-header" style="margin-top:20px;"><?= $en_all_6206[6197]['m_icon'].' '.$en_all_6206[6197]['m_name'] ?> [<span style="margin:0 0 10px 0;"><span id="charEnNum">0</span>/<?= config_var(6197) ?></span>]</span>
                        <span class="white-wrapper">
                                <textarea class="form-control text-edit border montserrat doupper" id="en_name"
                                          onkeyup="en_name_word_count()" data-lpignore="true"
                                          style="height:66px; min-height:66px;">
                                </textarea>
                            </span>



                        <!-- Player Icon -->
                        <span class="mini-header"><?= $en_all_6206[6198]['m_icon'].' '.$en_all_6206[6198]['m_name'] ?>

                                <a href="javascript:void(0);" style="margin-left: 5px;" onclick="$('#en_icon').val($('#en_icon').val() + '<i class=&quot;fad fa-&quot;></i>' )" data-toggle="tooltip" title="Insert blank Font-Awesome HTML code" data-placement="top"><i class="far fa-edit"></i><b>FA</b></a>

                                <a href="https://fontawesome.com/icons" style="margin-left: 5px;" target="_blank" data-toggle="tooltip" title="Visit Font-Awesome website for a full list of icons and their HTML code" data-placement="top"><i class="fas fa-external-link"></i></a>

                            </span>
                        <div class="form-group label-floating is-empty"
                             style="margin:1px 0 10px;">
                            <div class="input-group border">
                                <input type="text" id="en_icon" value=""
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


                            <span class="mini-header"><?= $en_all_4341[6186]['m_icon'].' '.$en_all_4341[6186]['m_name'] ?></span>
                            <select class="form-control border" id="ln_status_source_id">
                                <?php
                                foreach($this->config->item('en_all_6186') /* Transaction Status */ as $en_id => $m){
                                    echo '<option value="' . $en_id . '" title="' . $m['m_desc'] . '">' . $m['m_name'] . '</option>';
                                }
                                ?>
                            </select>

                            <div class="notify_unlink_en hidden">
                                <div class="alert alert-warning"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Saving will unlink source</div>
                            </div>




                            <form class="drag-box" method="post" enctype="multipart/form-data">
                                <span class="mini-header" style="margin-top: 20px;"><?= $en_all_4341[4372]['m_icon'].' '.$en_all_4341[4372]['m_name'] ?></span>
                                <span class="white-wrapper">
                                    <textarea class="form-control text-edit border" id="ln_content"
                                              data-lpignore="true"
                                              placeholder="Write, Drop a File or Paste URL"
                                              style="height:126px; min-height:126px;">
                                    </textarea>
                                </span>

                                <span><input class="inputfile" type="file" name="file" id="enFile" /><label class="" for="enFile" data-toggle="tooltip" title="Upload files up to <?= config_var(11063) ?> MB" data-placement="top"><i class="fal fa-cloud-upload"></i> Upload</label></span>
                            </form>


                            <span class="mini-header"><?= $en_all_4341[4593]['m_icon'].' '.$en_all_4341[4593]['m_name'] ?></span>
                            <span id="en_type_link_id"></span>
                            <p id="en_link_preview" class="hideIfEmpty"></p>



                        </div>

                    </div>

                </div>

            </div>

            <table>
                <tr>
                    <td class="save-td"><a href="javascript:en_modify_save();" class="btn btn-source btn-save">Save</a></td>
                    <td class="save-result-td"><span class="save_source_changes"></span></td>
                </tr>
            </table>

        </div>

    </div>

    <?php




    //FOR EDITING ONLY:
    echo '<div class="hidden">'.echo_en($en).'</div>';



    //NAME & STATUS
    echo '<div class="doclear">&nbsp;</div>';
    echo '<div class="pull-right inline-block" style="margin-bottom: -28px;">';

    //REFERENCES
    if(superpower_active(12701, true)){
        echo '<div class="inline-block '.superpower_active(12701).'">'.join('',en_count_db_references($en['en_id'])).'</div>';
    }

    //SOURCE DRAFTING?
    echo '<span class="icon-block en_status_source_id_' . $en['en_id'] . ( $is_public ? ' hidden ' : '' ).'"><span data-toggle="tooltip" data-placement="bottom" title="'.$en_all_6177[$en['en_status_source_id']]['m_name'].': '.$en_all_6177[$en['en_status_source_id']]['m_desc'].'">' . $en_all_6177[$en['en_status_source_id']]['m_icon'] . '</span></span>';

    //Modify
    echo '<a href="javascript:void(0);" onclick="en_modify_load(' . $en['en_id'] . ',0)" class="icon-block '.superpower_active(10967).'" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12275]['m_name'].'">'.$en_all_11035[12275]['m_icon'].'</a>';

    echo '</div>';
    echo '<div class="doclear">&nbsp;</div>';





    //Print Play Layout
    $disable_content_loading = !isset($_GET['load']);

    foreach ($this->config->item('en_all_11089') as $en_id => $m){

        //Don't show empty tabs:
        $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m['m_parents']);
        if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
            continue;
        }

        $this_tab = null;
        $counter = 0;
        $auto_expand_tab = in_array($en_id, $this->config->item('en_ids_12571'));


        //SOURCE
        if($en_id==6225){

            //Account Setting
            if(!$session_en || $session_en['en_id']!=$en['en_id']){
                continue;
            }


            $this_tab .= '<div class="accordion" id="MyAccountAccordion" style="margin-bottom:34px;">';

            //Display account fields ordered with their source links:
            foreach ($this->config->item('en_all_6225') as $acc_en_id => $acc_detail) {

                //Print header:
                $this_tab .= '<div class="card">
<div class="card-header" id="heading' . $acc_en_id . '">
<button class="btn btn-block" type="button" data-toggle="collapse" data-target="#openEn' . $acc_en_id . '" aria-expanded="false" aria-controls="openEn' . $acc_en_id . '">
  <span class="icon-block">' . $acc_detail['m_icon'] . '</span><b class="montserrat source doupper ' . extract_icon_color($acc_detail['m_icon']) . '">' . $acc_detail['m_name'] . '</b><span class="pull-right icon-block"><i class="fas fa-chevron-down"></i></span>
</button>
</div>

<div class="doclear">&nbsp;</div>

<div id="openEn' . $acc_en_id . '" class="collapse" aria-labelledby="heading' . $acc_en_id . '" data-parent="#MyAccountAccordion">
<div class="card-body">';


                //Show description if any:
                $this_tab .= (strlen($acc_detail['m_desc']) > 0 ? '<p>' . $acc_detail['m_desc'] . '</p>' : '');


                //Print account fields that are either Single Selectable or Multi Selectable:
                $is_multi_selectable = in_array(6122, $acc_detail['m_parents']);
                $is_single_selectable = in_array(6204, $acc_detail['m_parents']);

                if ($acc_en_id == 12289) {

                    $en_icon_parts = explode(' ',one_two_explode('class="', '"', $session_en['en_icon']));

                    $this_tab .= '<div class="'.superpower_active(10939).'"><div class="doclear">&nbsp;</div><div class="btn-group avatar-type-group pull-right" role="group" style="margin:0 0 10px 0;">
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'far\')" class="btn btn-far '.( $en_icon_parts[0]=='far' ? ' active ' : '' ).'"><i class="far fa-paw source"></i></a>
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'fad\')" class="btn btn-fad '.( $en_icon_parts[0]=='fad' ? ' active ' : '' ).'"><i class="fad fa-paw source"></i></a>
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'fas\')" class="btn btn-fas '.( $en_icon_parts[0]=='fas' ? ' active ' : '' ).'"><i class="fas fa-paw source"></i></a>
                </div><div class="doclear">&nbsp;</div></div>';


                    //List avatars:
                    foreach ($this->config->item('en_all_12279') as $en_id3 => $m3) {

                        $avatar_icon_parts = explode(' ',one_two_explode('class="', '"', $m3['m_icon']));
                        $avatar_type_match = ($en_icon_parts[0] == $avatar_icon_parts[0]);
                        $superpower_actives3 = array_intersect($this->config->item('en_ids_10957'), $m3['m_parents']);

                        $this_tab .= '<span class="'.( count($superpower_actives3) ? superpower_active(end($superpower_actives3)) : '' ).'">';
                        $this_tab .= '<a href="javascript:void(0);" onclick="account_update_avatar_icon(\'' . $avatar_icon_parts[0] . '\', \'' . $avatar_icon_parts[1] . '\')" icon-css="' . $avatar_icon_parts[1] . '" class="list-group-item itemsource avatar-item item-square avatar-type-'.$avatar_icon_parts[0].' avatar-name-'.$avatar_icon_parts[1].' ' .( $avatar_type_match ? '' : ' hidden ' ). ( $avatar_type_match && $en_icon_parts[1] == $avatar_icon_parts[1] ? ' active ' : '') . '"><div class="avatar-icon">' . $m3['m_icon'] . '</div></a>';
                        $this_tab .= '</span>';

                    }

                } elseif ($acc_en_id == 10957 /* Superpowers */) {

                    //Load Website URLs:
                    $en_all_10876 = $this->config->item('en_all_10876'); //MENCH WEBSITE

                    //List Activated Powers:
                    $this_tab .= '<div class="list-group">';

                    //List avatars:
                    foreach($this->config->item('en_all_10957') as $superpower_en_id => $m3){

                        $extract_icon_color = extract_icon_color($m3['m_icon']);
                        $superpower_actives3 = array_intersect($this->config->item('en_ids_10957'), $m3['m_parents']);
                        $has_req_powers = (!count($superpower_actives3) || superpower_assigned(end($superpower_actives3)));
                        $has_discover_url = ( isset($en_all_10876[$superpower_en_id]['m_desc']) && strlen($en_all_10876[$superpower_en_id]['m_desc']) ? $en_all_10876[$superpower_en_id]['m_desc'] : false );

                        //What is the superpower requirement?
                        if(superpower_assigned($superpower_en_id)){

                            //Allow Toggle
                            $this_tab .= '<a class="list-group-item itemsetting btn-superpower superpower-frame-'.$superpower_en_id.' '.( in_array($superpower_en_id, $this->session->userdata('session_superpowers_activated')) ? ' active ' : '' ).'" href="javascript:void();" onclick="account_toggle_superpower('.$superpower_en_id.')"><span class="icon-block '.$extract_icon_color.'" title="Source @'.$superpower_en_id.'">'.$m3['m_icon'].'</span><b class="montserrat '.$extract_icon_color.'">'.$m3['m_name'].'</b> '.$m3['m_desc'].'</a>';

                        } elseif($has_req_powers && $has_discover_url){

                            //Does not have it, but can get it:
                            $this_tab .= '<a class="list-group-item itemsetting btn-superpower" href="'.$has_discover_url.'"><span class="icon-block"><i class="fas fa-lock-open black"></i></span>'.$m3['m_icon'].'&nbsp;<b class="montserrat '.$extract_icon_color.'">'.$m3['m_name'].'</b> '.$m3['m_desc'].'</a>';

                        }
                    }

                    $this_tab .= '</div>';

                } elseif ($acc_en_id == 3288 /* Email */) {

                    $user_emails = $this->LEDGER_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                        'ln_portfolio_source_id' => $session_en['en_id'],
                        'ln_type_source_id' => 4255, //Linked Players Text (Email is text)
                        'ln_profile_source_id' => 3288, //Mench Email
                    ));

                    $this_tab .= '<span class="white-wrapper"><input type="email" id="en_email" class="form-control border dotransparent" value="' . (count($user_emails) > 0 ? $user_emails[0]['ln_content'] : '') . '" placeholder="you@gmail.com" /></span>
                <a href="javascript:void(0)" onclick="account_update_email()" class="btn btn-source">Save</a>
                <span class="saving-account save_email"></span>';

                } elseif ($acc_en_id == 3286 /* Password */) {

                    $this_tab .= '<span class="white-wrapper"><input type="password" id="input_password" class="form-control border dotransparent" data-lpignore="true" autocomplete="new-password" placeholder="New Password..." /></span>
                <a href="javascript:void(0)" onclick="account_update_password()" class="btn btn-source">Save</a>
                <span class="saving-account save_password"></span>';

                } elseif ($is_multi_selectable || $is_single_selectable) {

                    $this_tab .= echo_radio_sources($acc_en_id, $session_en['en_id'], ($is_multi_selectable ? 1 : 0));

                }

                //Print footer:
                $this_tab .= '<div class="doclear">&nbsp;</div>';
                $this_tab .= '</div></div></div>';

            }

            $this_tab .= '</div>'; //End of accordion

        } elseif($en_id==11030){

            //SOURCE PROFILE
            //FETCH ALL PARENTS
            $en__profiles = $this->LEDGER_model->ln_fetch(array(
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
                'ln_portfolio_source_id' => $en['en_id'],
            ), array('en_profile'), 0, 0, array('en_weight' => 'DESC'));

            $counter = count($en__profiles);
            if(!$counter && !$superpower_10967){
                continue;
            }

            $this_tab .= '<div id="list-parent" class="list-group ">';
            foreach ($en__profiles as $en_profile) {
                $this_tab .= echo_en($en_profile,true, null, true, $is_source);
            }

            //Input to add new parents:
            $this_tab .= '<div id="new-parent" class="list-group-item list-adder itemsource no-side-padding '.superpower_active(10967).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$en_all_2738[4536]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control source form-control-thick montserrat doupper algolia_search dotransparent add-input"
                           maxlength="' . config_var(6197) . '"
                           id="newIdeaTitle"
                           placeholder="NEW SOURCE">
                </div><div class="algolia_pad_search hidden pad_expand"></div></div>';

            $this_tab .= '</div>';

        } elseif($en_id==11029){

            //COUNT TOTAL CHILD
            $en__portfolios_count = $this->LEDGER_model->ln_fetch(array(
                'ln_profile_source_id' => $en['en_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
            ), array('en_portfolio'), 0, 0, array(), 'COUNT(en_id) as totals');
            $counter = $en__portfolios_count[0]['totals'];
            $en__portfolios = array(); //Fetch some


            if(!$counter && !$superpower_10967){
                continue;
            }

            if($counter){
                //Fetch Portfolios
                $en__portfolios = $this->LEDGER_model->ln_fetch(array(
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
                    'ln_profile_source_id' => $en['en_id'],
                ), array('en_portfolio'), config_var(11064), 0, array('ln_order' => 'ASC', 'en_name' => 'ASC'));
            }


            //SOURCE MASS EDITOR
            if($superpower_10967){

                //Mass Editor:
                $dropdown_options = '';
                $input_options = '';
                $editor_counter = 0;

                foreach ($this->config->item('en_all_4997') as $action_en_id => $mass_action_en) {


                    $editor_counter++;
                    $dropdown_options .= '<option value="' . $action_en_id . '">' .$mass_action_en['m_name'] . '</option>';
                    $is_upper = ( in_array($action_en_id, $this->config->item('en_ids_12577') /* SOURCE UPDATER UPPERCASE */) ? ' montserrat doupper ' : false );


                    //Start with the input wrapper:
                    $input_options .= '<span id="mass_id_'.$action_en_id.'" title="'.$mass_action_en['m_desc'].'" class="inline-block '. ( $editor_counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';




                    if(in_array($action_en_id, array(5000, 5001, 10625))){

                        //String Find and Replace:

                        //Find:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" placeholder="Search" class="form-control border '.$is_upper.'">';

                        //Replace:
                        $input_options .= '<input type="text" name="mass_value2_'.$action_en_id.'" placeholder="Replace" class="form-control border '.$is_upper.'">';


                    } elseif(in_array($action_en_id, array(5981, 5982))){

                        //Player search box:

                        //String command:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'"  placeholder="Search sources..." class="form-control algolia_search en_quick_search border '.$is_upper.'">';

                        //We don't need the second value field here:
                        $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';


                    } elseif($action_en_id == 11956){

                        //IF HAS THIS
                        $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'"  placeholder="IF THIS SOURCE..." class="form-control algolia_search en_quick_search border '.$is_upper.'">';

                        //ADD THIS
                        $input_options .= '<input type="text" name="mass_value2_'.$action_en_id.'"  placeholder="ADD THIS SOURCE..." class="form-control algolia_search en_quick_search border '.$is_upper.'">';


                    } elseif($action_en_id == 5003){

                        //Player Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_en_id.'" class="form-control border">';
                        $input_options .= '<option value="">Set Condition...</option>';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($this->config->item('en_all_6177') /* Source Status */ as $en_id3 => $m3){
                            $input_options .= '<option value="'.$en_id3.'">Update All '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($this->config->item('en_all_6177') /* Source Status */ as $en_id3 => $m3){
                            $input_options .= '<option value="'.$en_id3.'">Set to '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';


                    } elseif($action_en_id == 5865){

                        //Transaction Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_en_id.'" class="form-control border">';
                        $input_options .= '<option value="">Set Condition...</option>';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($this->config->item('en_all_6186') /* Transaction Status */ as $en_id3 => $m3){
                            $input_options .= '<option value="'.$en_id3.'">Update All '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($this->config->item('en_all_6186') /* Transaction Status */ as $en_id3 => $m3){
                            $input_options .= '<option value="'.$en_id3.'">Set to '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';


                    } else {

                        //String command:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'"  placeholder="String..." class="form-control border '.$is_upper.'">';

                        //We don't need the second value field here:
                        $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';

                    }

                    $input_options .= '</span>';

                }

                $this_tab .= '<div class="pull-right grey" style="margin:-25px 5px 0 0;"><a href="javascript:void(0);" onclick="$(\'.source_editor\').toggleClass(\'hidden\');" title="'.$en_all_11035[4997]['m_name'].'" data-toggle="tooltip" data-placement="top">'.$en_all_11035[4997]['m_icon'].'</a></div>';
                $this_tab .= '<div class="doclear">&nbsp;</div>';
                $this_tab .= '<div class="source_editor hidden">';
                $this_tab .= '<div class="discover-topic"><span class="icon-block">&nbsp;</span>'.$en_all_11035[4997]['m_name'].'</div>';
                $this_tab .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important; margin-left: 33px;">';
                $this_tab .= '<div class="inline-box">';

                //Drop Down
                $this_tab .= '<select class="form-control border" name="mass_action_en_id" id="set_mass_action">';
                $this_tab .= $dropdown_options;
                $this_tab .= '</select>';

                $this_tab .= $input_options;

                $this_tab .= '<div><input type="submit" value="APPLY" class="btn btn-source inline-block"></div>';

                $this_tab .= '</div>';
                $this_tab .= '</form>';

                //Also add invisible child IDs for quick copy/pasting:
                $this_tab .= '<div style="color:transparent;" class="hideIfEmpty">';
                foreach ($en__portfolios as $en_portfolio) {
                    $this_tab .= $en_portfolio['en_id'].',';
                }
                $this_tab .= '</div>';

                $this_tab .= '</div>';







                //Source Status Filters:
                if(superpower_active(12701, true)){

                    $en_count = $this->SOURCE_model->en_child_count($en['en_id'], $this->config->item('en_ids_7358') /* Source Status Active */);
                    $child_en_filters = $this->LEDGER_model->ln_fetch(array(
                        'ln_profile_source_id' => $en['en_id'],
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                        'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
                    ), array('en_portfolio'), 0, 0, array('en_status_source_id' => 'ASC'), 'COUNT(en_id) as totals, en_status_source_id', 'en_status_source_id');

                    //Only show filtering UI if we find child sources with different Status (Otherwise no need to filter):
                    if (count($child_en_filters) > 0 && $child_en_filters[0]['totals'] < $en_count) {

                        //Load status definitions:
                        $en_all_6177 = $this->config->item('en_all_6177'); //Source Status

                        //Add 2nd Navigation to UI
                        $this_tab .= '<div class="nav nav-pills nav-sm">';

                        //Show fixed All button:
                        $this_tab .= '<li class="nav-item"><a href="#" onclick="en_filter_status(-1)" class="nav-link en-status-filter active en-status--1" data-toggle="tooltip" data-placement="top" title="View all sources"><i class="fas fa-asterisk source"></i><span class="source">&nbsp;' . $en_count . '</span><span class="show-max source">&nbsp;TOTAL</span></a></li>';

                        //Show each specific filter based on DB counts:
                        foreach ($child_en_filters as $c_c) {
                            $st = $en_all_6177[$c_c['en_status_source_id']];
                            $extract_icon_color = extract_icon_color($st['m_icon']);
                            $this_tab .= '<li class="nav-item"><a href="#status-' . $c_c['en_status_source_id'] . '" onclick="en_filter_status(' . $c_c['en_status_source_id'] . ')" class="nav-link en-status-filter en-status-' . $c_c['en_status_source_id'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['m_desc'] . '">' . $st['m_icon'] . '<span class="' . $extract_icon_color . '">&nbsp;' . $c_c['totals'] . '</span><span class="show-max '.$extract_icon_color.'">&nbsp;' . $st['m_name'] . '</span></a></li>';
                        }

                        $this_tab .= '</div>';

                    }
                }
            }

            $this_tab .= '<div id="list-children" class="list-group">';

            foreach ($en__portfolios as $en_portfolio) {
                $this_tab .= echo_en($en_portfolio,false, null, true, $is_source);
            }
            if ($counter > count($en__portfolios)) {
                $this_tab .= echo_en_load_more(1, config_var(11064), $counter);
            }

            //Input to add new child:
            $this_tab .= '<div id="new-children" class="list-group-item list-adder itemsource no-side-padding '.superpower_active(10967).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$en_all_2738[4536]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control source form-control-thick montserrat doupper algolia_search dotransparent add-input"
                           maxlength="' . config_var(6197) . '"
                           id="newIdeaTitle"
                           placeholder="NEW SOURCE">
                </div><div class="algolia_pad_search hidden pad_expand"></div></div>';

            $this_tab .= '</div>';

        } elseif(in_array($en_id, $this->config->item('en_ids_4485'))){

            //Idea Notes
            $in_notes_filters = array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
                'ln_type_source_id' => $en_id,
                '(ln_creator_source_id='.$en['en_id'].' OR ln_portfolio_source_id='.$en['en_id'].' OR ln_profile_source_id='.$en['en_id'].')' => null,
            );

            //COUNT ONLY
            $item_counters = $this->LEDGER_model->ln_fetch($in_notes_filters, array('in_next'), 0, 0, array(), 'COUNT(in_id) as totals');
            $counter = $item_counters[0]['totals'];

            //SHOW LASTEST 100
            if($counter>0 && (!$disable_content_loading || $auto_expand_tab)){

                $in_notes_query = $this->LEDGER_model->ln_fetch($in_notes_filters, array('in_next'), config_var(11064), 0, array('in_weight' => 'DESC'));


                $this_tab .= '<div class="list-group">';
                foreach ($in_notes_query as $count => $in_notes) {
                    if(in_array($en_id, $this->config->item('en_ids_12321'))){

                        $this_tab .= echo_in_discover($in_notes);

                    } elseif(in_array($en_id, $this->config->item('en_ids_12322'))){

                        //Include the message:
                        $infobar_details = null;
                        if($in_notes['ln_content']){
                            $infobar_details .= '<div class="message_content">';
                            $infobar_details .= $this->COMMUNICATION_model->comm_message_send($in_notes['ln_content']);
                            $infobar_details .= '</div>';
                        }

                        $this_tab .= echo_in($in_notes, 0, false, false, $infobar_details, null, false);

                    }
                }
                $this_tab .= '</div>';

            } else {

                if(!superpower_assigned(12701)){
                    continue;
                } else {
                    //TODO Implement this UI:
                    $this_tab .= '<div class="alert alert-warning"><span class="icon-block"><i class="fas fa-info-circle"></i></span>This Section is Under Development...</div>';
                }

            }

        } elseif($en_id == 7347 /* DISCOVER LIST */){

            $player_discoveries = $this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en['en_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            ), array('in_previous'), 1, 0, array(), 'COUNT(ln_id) as totals');
            $counter = $player_discoveries[0]['totals'];

        } elseif(in_array($en_id, $this->config->item('en_ids_12410'))){

            //SOURCE COINS (DISCOVER & IDEA)

            $join_objects = array();
            $match_columns = array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_'.$en_id)) . ')' => null,
            );

            if($en_id == 12273){
                //IDEA COIN
                $match_columns['ln_profile_source_id'] = $en['en_id'];
                $match_columns['in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')'] = null; //Idea Status Public
                $join_objects = array('in_next');
            } elseif($en_id == 6255){
                //DISCOVER COIN
                $match_columns['ln_creator_source_id'] = $en['en_id'];
            }

            //DISCOVER & BOOKMARKS
            $item_counters = $this->LEDGER_model->ln_fetch($match_columns, $join_objects, 1, 0, array(), 'COUNT(ln_id) as totals');

            $counter = $item_counters[0]['totals'];

            if($counter > 0 && (!$disable_content_loading || $auto_expand_tab)){

                //Dynamic Loading when clicked:
                $discover_history_ui = $this->DISCOVER_model->discover_history_ui($en_id, 0, $en['en_id']);
                $this_tab .= $discover_history_ui['message'];

            }

        }

        if(!$counter && (!in_array($en_id, $this->config->item('en_ids_12574')) || !$session_en)){
            continue;
        }


        //HEADER
        echo '<div class="'.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'">';

        echo '<div class="discover-topic"><a href="javascript:void(0);" onclick="$(\'.contentTab'.$en_id.'\').toggleClass(\'hidden\')"><span class="icon-block"><i class="far fa-plus-circle contentTab'.$en_id.( $auto_expand_tab ? ' hidden ' : '' ).'"></i><i class="far fa-minus-circle contentTab'.$en_id.( $auto_expand_tab ? '' : ' hidden ' ).'"></i></span>'.$m['m_name'].( $counter>0 ? '<span title="'.number_format($counter, 0).'" class="'.superpower_active(12701).'">&nbsp;'.echo_number($counter).'</span>' : '').'</a></div>';

        //BODY
        echo '<div class="contentTab'.$en_id.( $auto_expand_tab ? '' : ' hidden ' ).'" style="padding-bottom:34px;">';
        echo $this_tab;
        echo '</div>';
        echo '</div>';

    }

    ?>

</div>