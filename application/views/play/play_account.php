<?php
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
?>

<script src="/application/views/play/play_account.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>

<div class="container">

    <h1 class="blue pull-left inline-block"><span class="icon-block-xlg en-icon"><?= $en_all_11035[6225]['m_icon'] ?></span><?= $en_all_11035[6225]['m_name'] ?></h1>
    <div class="pull-right inline-block">
        <?php

        echo '<a href="/play" class="btn btn-play btn-five inline-block" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[11087]['m_name'].'">'.$en_all_11035[11087]['m_icon'].'</a>';

        echo '<a href="/play/'.$session_en['en_id'].'" class="btn btn-play btn-five inline-block" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[12205]['m_name'].'">'.$en_all_11035[12205]['m_icon'].'</a>';

        if(!intval($this->session->userdata('messenger_signin'))){
            //Only give signout option if NOT logged-in from Messenger
            echo '<a href="/play/signout" class="btn btn-play btn-five inline-block" style="padding-top:10px;" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_11035[7291]['m_name'].'">'.$en_all_11035[7291]['m_icon'].'</a>';
        }
        ?>
    </div>

    <div style="clear: both;">&nbsp;</div>

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

        <?php
        echo '<div class="list-group">';
        //Display account fields ordered with their player links:
        foreach($this->config->item('en_all_6225') as $acc_en_id => $acc_detail){

            //Print header:
            echo '<div class="list-group-item">
                    <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="openEn'.$acc_en_id.'">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$acc_en_id.'" aria-expanded="true" aria-controls="collapse'.$acc_en_id.'">
                                <span class="icon-block" style="width:38px;">'.$acc_detail['m_icon'].'</span><b class="montserrat doupper '.extract_icon_color($acc_detail['m_icon']).'">'.$acc_detail['m_name'].'</b>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse'.$acc_en_id.'" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="openEn'.$acc_en_id.'">
                        <div class="panel-body player-list">
                            '.( strlen($acc_detail['m_desc']) > 0 ? '<p>'.$acc_detail['m_desc'].'</p>' : '' );


            //Print account fields that are either Single Selectable or Multi Selectable:
            $is_multi_selectable  = in_array(6122 , $acc_detail['m_parents']);
            $is_single_selectable = in_array(6204 , $acc_detail['m_parents']);
            if($acc_en_id==10956 /* AVATARS */){

                foreach($this->config->item('en_all_10956') as $en_id => $m) {
                    echo '<a href="javascript:void(0);" onclick="update_avatar('.$en_id.')" class="list-group-item itemplay avatar-item item-square '.( one_two_explode('class="','"',$m['m_icon'])==one_two_explode('class="','"',$session_en['en_icon']) ? ' active ' : '' ).'"><div class="avatar-icon">'.$m['m_icon'].'</div></a>';
                }
                echo '<div style="margin-bottom:20px; clear: both;">&nbsp;</div>';

            } elseif($is_multi_selectable || $is_single_selectable){

                echo echo_radio_players($acc_en_id, $session_en['en_id'], ( $is_multi_selectable ? 1 : 0 ));

            } elseif($acc_en_id==6197 /* Full Name */){

                echo '<span class="white-wrapper"><input type="text" id="en_name" class="form-control border montserrat" value="'.$session_en['en_name'].'" /></span>
                        <a href="javascript:void(0)" onclick="save_full_name()" class="btn btn-play">Save</a>
                        <span class="saving-account save_full_name"></span>';

            } elseif($acc_en_id==3288 /* Mench Email */){

                $user_emails = $this->READ_model->ln_fetch(array(
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_child_play_id' => $session_en['en_id'],
                    'ln_type_play_id' => 4255, //Linked Players Text (Email is text)
                    'ln_parent_play_id' => 3288, //Mench Email
                ));

                echo '<span class="white-wrapper"><input type="email" id="en_email" class="form-control border" value="'.( count($user_emails) > 0 ? $user_emails[0]['ln_content'] : '' ).'" placeholder="you@gmail.com" /></span>
                        <a href="javascript:void(0)" onclick="save_email()" class="btn btn-play">Save</a>
                        <span class="saving-account save_email"></span>';

            } elseif($acc_en_id==3286 /* Password */){

                echo '<span class="white-wrapper"><input type="password" id="input_password" class="form-control border" placeholder="Set new password..." /></span>
                        <a href="javascript:void(0)" onclick="account_update_password()" class="btn btn-play">Save</a>
                        <span class="saving-account save_password"></span>';

            } elseif($acc_en_id==4783 /* Phone */){

                $user_phones = $this->READ_model->ln_fetch(array(
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_child_play_id' => $session_en['en_id'],
                    'ln_type_play_id' => 4319, //Phone are of type number
                    'ln_parent_play_id' => 4783, //Phone Number
                ));

                echo '<span class="white-wrapper"><input type="number" id="en_phone" class="form-control border" value="'.( count($user_phones) > 0 ? $user_phones[0]['ln_content'] : '' ).'" placeholder="Set phone number..." /></span>
                        <a href="javascript:void(0)" onclick="save_phone()" class="btn btn-play">Save</a>
                        <span class="saving-account save_phone"></span>';

            } elseif($acc_en_id==6123 /* Social Profiles */){

                $user_social_profiles = $this->READ_model->ln_fetch(array(
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_type_play_id' => 4256, //Generic URL
                    'ln_parent_play_id IN ('.join(',', $this->config->item('en_ids_6123')).')' => null, //Any social profile
                    'ln_child_play_id' => $session_en['en_id'], //For this user
                ));

                echo '<script> var en_ids_6123 = ' . json_encode($this->config->item('en_ids_6123')) . '; </script>'; //Used for JS variables:

                echo '<table style="width: 100%;">';
                foreach($this->config->item('en_all_6123') as $acc_en_id => $acc_detail){
                    //Do we have this social profile?
                    $profile_array = filter_array($user_social_profiles, 'ln_parent_play_id', $acc_en_id);
                    echo '<tr>';
                    echo '<td style="padding:0 5px 2px 0 !important; width: 26px; font-size: 1.2em !important;">'.$acc_detail['m_icon'].'</td>';
                    echo '<td style="width: 100%; padding-bottom:5px;"><span class="white-wrapper"><input type="url" value="'.( $profile_array ? $profile_array['ln_content'] : '' ).'" parent-en-id="'.$acc_en_id.'" class="form-control border social_profile_url" placeholder="'.$acc_detail['m_name'].'" style="display: inline-block;" /></span></td>';
                    echo '</tr>';
                }
                echo '</table>';

                echo '<a href="javascript:void(0)" onclick="save_social_profiles()" class="btn btn-play">Save</a>
                                    <span class="saving-account save_social_profiles"></span>';

            }

            //Print footer:
            echo '</div></div></div></div>';
        }
        echo '</div>';
        ?>

    </div>

</div>